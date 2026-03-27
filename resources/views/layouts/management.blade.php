<div class="p-6 max-w-4xl mx-auto text-slate-900 dark:text-slate-100">
    <h2 class="text-2xl font-bold mb-6">Shift Management</h2>

    @if(session()->has('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4 dark:bg-green-900/40 dark:text-green-300">{{ session('success') }}</div>
    @endif
    @if(session()->has('error'))
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4 dark:bg-red-900/40 dark:text-red-300">{{ session('error') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg p-6 dark:bg-slate-900 dark:border dark:border-slate-700">
        @if ($currentShift)
        {{-- Clocked In State --}}
        <div class="text-center">
            <h3 class="text-xl font-semibold text-green-600">You are currently Clocked In</h3>
            <div class="mt-4 text-gray-600 space-y-2 dark:text-slate-300">
                <p><strong>Branch:</strong> {{ $currentShift->branch->name }}</p>
                <p><strong>Clock In Time:</strong> {{ $currentShift->clock_in->format('d M Y, h:i A') }}</p>
                <p><strong>Duration:</strong> {{ $currentShift->clock_in->diffForHumans(null, true) }}</p>
            </div>
            <div class="mt-6">
                <button wire:click="clockOut" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-bold text-lg">
                    Clock Out
                </button>
            </div>
        </div>
        @else
        {{-- Clocked Out State --}}
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-slate-100">You are currently Clocked Out</h3>
            <p class="text-gray-500 mt-2 dark:text-slate-400">Start a new shift to begin making sales.</p>
            <div class="mt-6">
                <button wire:click="openClockInModal" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-bold text-lg">
                    Start Shift / Clock In
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Clock In Modal -->
    @if($showClockInModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md dark:bg-slate-900 dark:border dark:border-slate-700">
            <h3 class="text-xl font-bold mb-4">Start New Shift</h3>
            <form wire:submit.prevent="clockIn">
                <div class="space-y-4">
                    <div>
                        <label for="branchId" class="block">Branch</label>
                        <select wire:model="branchId" id="branchId" class="w-full border rounded p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                            <option value="">Select a branch</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branchId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="startingCash" class="block">Starting Cash (Float)</label>
                        <input type="number" step="0.01" wire:model="startingCash" id="startingCash" class="w-full border rounded p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:placeholder-slate-400" placeholder="e.g., 5000.00">
                        @error('startingCash') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" wire:click="$set('showClockInModal', false)" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Clock In</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
