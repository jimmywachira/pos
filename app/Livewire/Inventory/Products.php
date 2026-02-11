<?php

namespace App\Livewire\Inventory;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shift;
use App\Models\Stock;
use App\Models\StockMovement;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Products extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $perPage = 50;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    public $showModal = false;
    public $editingProductId;

    // Form fields
    public $name, $sku, $description, $categoryId, $isActive = true;
    public $variants = [];
    public $image;
    public $currentImagePath;

    protected $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255|unique:products,sku',
        'description' => 'nullable|string',
        'categoryId' => 'nullable|exists:categories,id',
        'isActive' => 'boolean',
        'image' => 'nullable|image|max:2048',
        'variants.*.label' => 'required|string|max:255',
        'variants.*.barcode' => 'nullable|string|max:255|distinct|unique:product_variants,barcode',
        'variants.*.retail_price' => 'required|numeric|min:0',
        'variants.*.cost_price' => 'required|numeric|min:0',
        'variants.*.initial_stock' => 'nullable|integer|min:0',
    ];

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    public function create()
    {
        $this->resetForm();
        $this->addVariant(); // Add one empty variant by default
        $this->showModal = true;
    }

    public function edit(Product $product)
    {
        $this->resetForm();
        $this->editingProductId = $product->id;

        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description;
        $this->categoryId = $product->category_id;
        $this->isActive = $product->is_active;
        $this->currentImagePath = $product->image_path;

        $this->variants = $product->variants->map(fn ($variant) => [
            'id' => $variant->id,
            'label' => $variant->label,
            'barcode' => $variant->barcode,
            'retail_price' => $variant->retail_price,
            'cost_price' => $variant->cost_price,
            'initial_stock' => 0,
        ])->toArray();

        $this->showModal = true;
    }

    public function save()
    {
        $existingImagePath = null;
        if ($this->editingProductId) {
            $existingImagePath = Product::whereKey($this->editingProductId)->value('image_path');
        }

        $imagePath = $existingImagePath;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        $rules = $this->rules;

        // Dynamically adjust validation rules for updates
        if ($this->editingProductId) {
            $rules['sku'] = 'required|string|max:255|unique:products,sku,' . $this->editingProductId;

            foreach ($this->variants as $index => $variant) {
                if (isset($variant['id'])) {
                    $rules['variants.' . $index . '.barcode'] =
                        'nullable|string|max:255|distinct|unique:product_variants,barcode,' . $variant['id'];
                }
            }
        }

        $this->validate($rules);

        $initialStockRequested = collect($this->variants)->contains(function ($variant) {
            return (int) ($variant['initial_stock'] ?? 0) > 0;
        });
        $activeShift = Shift::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if ($initialStockRequested && ! $activeShift) {
            $this->dispatch('flash-message', message: 'Active shift required to set initial stock.', type: 'error');
            return;
        }

        DB::transaction(function () use ($imagePath) {
            $product = Product::updateOrCreate(
                ['id' => $this->editingProductId],
                [
                    'name' => $this->name,
                    'sku' => $this->sku,
                    'description' => $this->description,
                    'category_id' => $this->categoryId,
                    'is_active' => $this->isActive,
                    'image_path' => $imagePath,
                ]
            );

            $existingVariantIds = array_filter(array_column($this->variants, 'id'));
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();

            $branches = $activeShift ? collect([$activeShift->branch]) : collect();

            foreach ($this->variants as $variantData) {
                if (array_key_exists('barcode', $variantData) && $variantData['barcode'] === '') {
                    $variantData['barcode'] = null;
                }

                $variant = $product->variants()->updateOrCreate(
                    ['id' => $variantData['id'] ?? null],
                    collect($variantData)->except('id')->toArray()
                );

                $initialStock = (int) ($variantData['initial_stock'] ?? 0);
                if (! isset($variantData['id']) && $initialStock > 0) {
                    foreach ($branches as $branch) {
                        $stock = Stock::firstOrCreate(
                            [
                                'branch_id' => $branch->id,
                                'product_variant_id' => $variant->id,
                            ],
                            ['quantity' => 0]
                        );

                        $stock->increment('quantity', $initialStock);

                        StockMovement::create([
                            'product_variant_id' => $variant->id,
                            'branch_id' => $branch->id,
                            'user_id' => auth()->id(),
                            'transaction_type' => 'stock_in',
                            'direction' => 'in',
                            'quantity' => $initialStock,
                            'reference_type' => 'product_variant',
                            'reference_id' => $variant->id,
                            'notes' => 'Initial stock on product creation',
                        ]);
                    }
                }
            }
        });

        if ($this->image && $existingImagePath && $existingImagePath !== $imagePath) {
            Storage::disk('public')->delete($existingImagePath);
        }

        session()->flash('success', $this->editingProductId ? 'Product updated successfully.' : 'Product created successfully.');
        $this->closeModal();
    }

    public function render()
    {
        $products = Product::with(['category', 'variants'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.inventory.products', [
            'products' => $products,
            'categories' => Category::all(),
        ]);
    }

    public function addVariant()
    {
        $this->variants[] = [
            'id' => null,
            'label' => '',
            'barcode' => '',
            'retail_price' => 0,
            'cost_price' => 0,
            'initial_stock' => 0,
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['name', 'sku', 'description', 'categoryId', 'isActive', 'variants', 'editingProductId', 'image', 'currentImagePath']);
    }
}
