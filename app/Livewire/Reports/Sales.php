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

    // Chart data
    public array $salesTrendData = [];
    public array $salesByBranchData = [];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();
    }

    public function updated($property)
    {
        if (in_array($property, ['startDate', 'endDate', 'branchId', 'customerId', 'search'])) {
            $this->resetPage();
        }
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

    private function getBaseQuery()
    {
        return Sale::query()
            ->when($this->startDate, fn ($q) => $q->whereDate('sales.created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('sales.created_at', '<=', $this->endDate))
            ->when($this->branchId !== 'all', fn ($q) => $q->where('branch_id', $this->branchId))
            ->when($this->customerId !== 'all', fn ($q) => $q->where('customer_id', $this->customerId))
            ->when($this->search, fn ($q) => $q->where('invoice_no', 'like', '%' . $this->search . '%'));
    }

    public function getAggregatesProperty()
    {
        // Clone the query to avoid affecting the main sales list pagination
        $aggregatesQuery = $this->getBaseQuery();

        $totalSales = $aggregatesQuery->sum('total');
        $totalTax = $aggregatesQuery->sum('tax');
        $totalDiscount = $aggregatesQuery->sum('discount');

        // To calculate profit, we need to sum the profit from each sale item.
        // Profit per item = (unit_price - cost_price) * quantity
        $totalProfit = (clone $this->getBaseQuery())
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('product_variants', 'sale_items.product_variant_id', '=', 'product_variants.id')
            ->sum(DB::raw('sale_items.quantity * (sale_items.unit_price - product_variants.cost_price)'));


        return [
            'total_sales' => $totalSales,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount, // Kept for display purposes
            'total_profit' => $totalProfit,
        ];
    }

    private function prepareChartData()
    {
        $baseQuery = $this->getBaseQuery();

        // Sales Trend Data
        $trendData = (clone $baseQuery)
            ->select(DB::raw('DATE(sales.created_at) as date'), DB::raw('SUM(total) as total_sales'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $this->salesTrendData = [
            'labels' => $trendData->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('d M'))->toArray(),
            'values' => $trendData->pluck('total_sales')->toArray(),
        ];

        // Sales by Branch Data
        $branchData = (clone $baseQuery)
            ->join('branches', 'sales.branch_id', '=', 'branches.id')
            ->select('branches.name', DB::raw('SUM(sales.total) as total_sales'))
            ->groupBy('branches.name')
            ->get();

        $this->salesByBranchData = [
            'labels' => $branchData->pluck('name')->toArray(),
            'values' => $branchData->pluck('total_sales')->toArray(),
        ];

        // Dispatch event to update charts on the frontend
        $this->dispatch('update-charts', [
            'salesTrendData' => $this->salesTrendData,
            'salesByBranchData' => $this->salesByBranchData,
        ]);
    }

    public function render()
    {
        $this->prepareChartData();

        $sales = $this->getBaseQuery()
            ->with(['customer', 'branch', 'user', 'items.productVariant'])
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
