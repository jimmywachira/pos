<?php

namespace App\Livewire\Reports;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\StockAdjustment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.print')]
class SalesReportPrint extends Component
{
    public $startDate;
    public $endDate;
    public $branchId;
    public $customerId;
    
    public $sales;
    public $aggregates;
    public $branch;
    public $customer;
    
    public $storeName;
    public $storeLogo;
    public $currency;

    public function mount($startDate = null, $endDate = null, $branchId = null, $customerId = null)
    {
        $this->startDate = $startDate ?? Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = $endDate ?? Carbon::now()->endOfMonth()->toDateString();
        $this->branchId = $branchId;
        $this->customerId = $customerId;
        
        // Load settings
        $this->storeName = Setting::get('store_name', config('app.name'));
        $this->storeLogo = Setting::get('store_logo');
        $this->currency = Setting::get('currency', 'Ksh');
        
        // Load branch and customer if filtered
        if ($this->branchId && $this->branchId !== 'all') {
            $this->branch = Branch::find($this->branchId);
        }
        
        if ($this->customerId && $this->customerId !== 'all') {
            $this->customer = Customer::find($this->customerId);
        }
        
        // Load sales data
        $this->loadSalesData();
    }

    private function loadSalesData()
    {
        $query = Sale::query()
            ->with(['customer', 'branch', 'user', 'items.productVariant.product'])
            ->when($this->startDate, fn ($q) => $q->whereDate('sales.created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('sales.created_at', '<=', $this->endDate))
            ->when($this->branchId && $this->branchId !== 'all', fn ($q) => $q->where('branch_id', $this->branchId))
            ->when($this->customerId && $this->customerId !== 'all', fn ($q) => $q->where('customer_id', $this->customerId))
            ->orderBy('created_at', 'desc');

        $this->sales = $query->get();

        // Calculate aggregates
        $totalSales = $this->sales->sum('total');
        $totalTax = $this->sales->sum('tax');
        $totalDiscount = $this->sales->sum('discount');

        $lossQuery = StockAdjustment::query()
            ->where('type', 'loss')
            ->when($this->startDate, fn ($q) => $q->whereDate('stock_adjustments.created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('stock_adjustments.created_at', '<=', $this->endDate))
            ->when($this->branchId && $this->branchId !== 'all', fn ($q) => $q->where('branch_id', $this->branchId));

        $totalLoss = $lossQuery->sum('total_cost');

        // Calculate profit
        $totalProfit = Sale::query()
            ->when($this->startDate, fn ($q) => $q->whereDate('sales.created_at', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('sales.created_at', '<=', $this->endDate))
            ->when($this->branchId && $this->branchId !== 'all', fn ($q) => $q->where('branch_id', $this->branchId))
            ->when($this->customerId && $this->customerId !== 'all', fn ($q) => $q->where('customer_id', $this->customerId))
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('product_variants', 'sale_items.product_variant_id', '=', 'product_variants.id')
            ->sum(DB::raw('sale_items.quantity * (sale_items.unit_price - product_variants.cost_price)'));

        $this->aggregates = [
            'total_sales' => $totalSales,
            'total_tax' => $totalTax,
            'total_discount' => $totalDiscount,
            'total_profit' => $totalProfit,
            'total_loss' => $totalLoss,
            'transaction_count' => $this->sales->count(),
        ];
    }

    public function render()
    {
        return view('livewire.reports.sales-report-print');
    }
}
