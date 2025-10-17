<?php

namespace App\Livewire\Inventory;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Products extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    public $showModal = false;
    public $editingProductId;

    // Form fields
    public $name, $sku, $description, $categoryId, $isActive = true;
    public $variants = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:255|unique:products,sku',
        'description' => 'nullable|string',
        'categoryId' => 'nullable|exists:categories,id',
        'isActive' => 'boolean',
        'variants.*.label' => 'required|string|max:255',
        'variants.*.barcode' => 'nullable|string|max:255|distinct|unique:product_variants,barcode',
        'variants.*.retail_price' => 'required|numeric|min:0',
        'variants.*.cost_price' => 'required|numeric|min:0',
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

        $this->variants = $product->variants->map(fn ($variant) => [
            'id' => $variant->id,
            'label' => $variant->label,
            'barcode' => $variant->barcode,
            'retail_price' => $variant->retail_price,
            'cost_price' => $variant->cost_price,
        ])->toArray();

        $this->showModal = true;
    }

    public function save()
    {
        // Dynamically adjust validation rules for updates
        if ($this->editingProductId) {
            $this->rules['sku'] .= ',' . $this->editingProductId;
            foreach ($this->variants as $index => $variant) {
                if (isset($variant['id'])) {
                    $this->rules['variants.' . $index . '.barcode'] .= ',' . $variant['id'];
                }
            }
        }

        $this->validate();

        DB::transaction(function () {
            $product = Product::updateOrCreate(
                ['id' => $this->editingProductId],
                [
                    'name' => $this->name,
                    'sku' => $this->sku,
                    'description' => $this->description,
                    'category_id' => $this->categoryId,
                    'is_active' => $this->isActive,
                ]
            );

            $existingVariantIds = array_filter(array_column($this->variants, 'id'));
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();

            foreach ($this->variants as $variantData) {
                $product->variants()->updateOrCreate(
                    ['id' => $variantData['id'] ?? null],
                    collect($variantData)->except('id')->toArray()
                );
            }
        });

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
        ])->layout('layouts.app');
    }

    public function addVariant()
    {
        $this->variants[] = ['id' => null, 'label' => '', 'barcode' => '', 'retail_price' => 0, 'cost_price' => 0];
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
        $this->reset(['name', 'sku', 'description', 'categoryId', 'isActive', 'variants', 'editingProductId']);
    }
}
