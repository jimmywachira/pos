<?php

namespace App\Livewire\Reports;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Sale;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Sales extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $branchId = 'all';
    public $customerId = 'all';
    public $search = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $field;
    }

    public function getSalesQueryProperty()
    {
        return Sale::with(['customer', 'branch', 'user', 'items.productVariant'])
            ->when($this->startDate, fn ($q) => $q->whereDate('sales.created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('sales.created_at', '<=', $this->endDate))
            ->when($this->branchId !== 'all', fn ($q) => $q->where('branch_id', $this->branchId))
            ->when($this->customerId !== 'all', fn ($q) => $q->where('customer_id', $this->customerId))
            ->when($this->search, fn ($q) => $q->where('invoice_no', 'like', '%' . $this->search . '%'));
    }

    public function getAggregatesProperty()
    {
        // Clone the query to avoid affecting the main sales list pagination
        $aggregatesQuery = clone $this->salesQuery;

        $totalSales = $aggregatesQuery->sum('total');
        $totalTax = $aggregatesQuery->sum('tax');
        $totalDiscount = $aggregatesQuery->sum('discount');

        // Efficiently calculate total cost using a subquery and joins
        $totalCost = (clone $this->salesQuery)
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('product_variants', 'sale_items.product_variant_id', '=', 'product_variants.id')
            ->sum(DB::raw('sale_items.quantity * product_variants.cost_price'));

        // Profit is calculated as (Revenue - Cost of Goods Sold).
        // Revenue = Total Sales - Tax + Discount (if discount is applied before tax)
        $revenue = $totalSales - $totalTax;
        $totalProfit = $revenue - $totalCost;

        return [
            'total_sales' => $totalSales,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount, // Kept for display purposes
            'total_profit' => $totalProfit,
        ];
    }

    public function render()
    {
        $sales = $this->salesQuery
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(15);

        return view('livewire.reports.sales', [
            'sales' => $sales,
            'branches' => Branch::all(),
            'customers' => Customer::all(),
        ])->layout('layouts.app');
    }

    public function sortIcon($field)
    {
        if ($this->sortBy !== $field) {
            return '<ion-icon name="remove-outline" class="ml-1 text-gray-400"></ion-icon>';
        }

        return $this->sortDirection === 'asc'
            ? '<ion-icon name="caret-up-outline" class="ml-1"></ion-icon>'
            : '<ion-icon name="caret-down-outline" class="ml-1"></ion-icon>';
    }
}
