<?php

namespace App\Livewire\Shifts;

use App\Models\Branch;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Management extends Component
{
    public ?Shift $currentShift;

    // Clock-in form properties
    public $showClockInModal = false;
    public $branchId;
    public $startingCash;

    protected $rules = [
        'branchId' => 'required|exists:branches,id',
        'startingCash' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->loadCurrentShift();
    }

    public function loadCurrentShift()
    {
        $this->currentShift = Shift::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('branch')
            ->first();
    }

    public function openClockInModal()
    {
        $this->reset(['branchId', 'startingCash']);
        $this->showClockInModal = true;
    }

    public function clockIn()
    {
        $this->validate();

        if ($this->currentShift) {
            session()->flash('error', 'You are already clocked in for an active shift.');
            return;
        }

        DB::transaction(function () {
            $shift = Shift::create([
                'user_id' => Auth::id(),
                'branch_id' => $this->branchId,
                'clock_in' => now(),
                'status' => 'active',
            ]);

            $shift->cashDrawer()->create([
                'starting_cash' => $this->startingCash,
            ]);
        });

        $this->loadCurrentShift();
        $this->showClockInModal = false;
        session()->flash('success', 'You have successfully clocked in.');
    }

    public function clockOut()
    {
        if (!$this->currentShift) {
            session()->flash('error', 'No active shift found to clock out from.');
            return;
        }

        // In a full implementation, you would handle ending cash reconciliation here.
        $this->currentShift->update([
            'clock_out' => now(),
            'status' => 'completed',
        ]);

        $this->loadCurrentShift();
        session()->flash('success', 'You have successfully clocked out.');
    }

    public function render()
    {
        return view('livewire.shifts.management', [
            'branches' => Branch::all(),
        ]);
    }
}