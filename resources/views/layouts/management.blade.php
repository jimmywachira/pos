<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 p-4 sm:p-6 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
    <div class="mx-auto max-w-4xl text-slate-900 dark:text-slate-100">
    <h2 class="mb-6 text-2xl font-bold">Shift Management</h2>

    @if(session()->has('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4 dark:bg-green-900/40 dark:text-green-300">{{ session('success') }}</div>
    @endif
    @if(session()->has('error'))
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4 dark:bg-red-900/40 dark:text-red-300">{{ session('error') }}</div>
    @endif

    <x-ui.section-card bodyClass="p-6">
        @if ($currentShift)
        {{-- Clocked In State --}}
        <div class="text-center">
            <h3 class="text-xl font-semibold text-green-600">You are currently Clocked In</h3>
            <div class="mt-4 space-y-2 text-slate-600 dark:text-slate-300">
                <p><strong>Branch:</strong> {{ $currentShift->branch->name }}</p>
                <p><strong>Clock In Time:</strong> {{ $currentShift->clock_in->format('d M Y, h:i A') }}</p>
                <p><strong>Duration:</strong> {{ $currentShift->clock_in->diffForHumans(null, true) }}</p>
            </div>
            <div class="mt-6">
                <button wire:click="clockOut" class="rounded-xl bg-red-600 px-6 py-3 text-lg font-bold text-white transition hover:bg-red-700">
                    Clock Out
                </button>
            </div>
        </div>
        @else
        {{-- Clocked Out State --}}
        <div class="text-center">
            <h3 class="text-xl font-semibold text-slate-700 dark:text-slate-100">You are currently Clocked Out</h3>
            <p class="mt-2 text-slate-500 dark:text-slate-400">Start a new shift to begin making sales.</p>
            <div class="mt-6">
                <button wire:click="openClockInModal" class="rounded-xl bg-blue-600 px-6 py-3 text-lg font-bold text-white transition hover:bg-blue-700">
                    Start Shift / Clock In
                </button>
            </div>
        </div>
        @endif
    </x-ui.section-card>

    <!-- Clock In Modal -->
    @if($showClockInModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl dark:border-slate-700 dark:bg-slate-900">
            <h3 class="mb-4 text-xl font-bold">Start New Shift</h3>
            <form wire:submit.prevent="clockIn">
                <div class="space-y-4">
                    <div>
                        <label for="branchId" class="mb-1.5 block text-sm font-semibold tracking-wide text-slate-700 dark:text-slate-200">Branch</label>
                        <select wire:model="branchId" id="branchId" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100">
                            <option value="">Select a branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branchId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="startingCash" class="mb-1.5 block text-sm font-semibold tracking-wide text-slate-700 dark:text-slate-200">Starting Cash (Float)</label>
                        <input type="number" step="0.01" wire:model="startingCash" id="startingCash" class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:placeholder-slate-400" placeholder="e.g., 5000.00">
                        @error('startingCash') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="$set('showClockInModal', false)" class="rounded-xl bg-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-800 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                    <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Clock In</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    </div>
</div>
