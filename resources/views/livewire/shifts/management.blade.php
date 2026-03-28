<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 p-4 sm:p-6 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900">
    <x-ui.section-card class="mb-4" bodyClass="p-4">
        <nav wire:navigate class="flex flex-wrap items-center gap-2">
            <a
                wire:navigate.hover
                href="{{ route('settings') }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('settings*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}"
            >
                <ion-icon name="settings-outline"></ion-icon>
                <span>Settings</span>
            </a>

            <a
                wire:navigate.hover
                href="{{ route('shifts.management') }}"
                class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('shifts.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}"
            >
                <ion-icon name="people-outline"></ion-icon>
                <span>Shifts</span>
            </a>

            @if (auth()->user()?->hasRole('admin'))
                <a
                    wire:navigate.hover
                    href="{{ route('users.management') }}"
                    class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100' }}"
                >
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <span>Users & Roles</span>
                </a>
            @endif
        </nav>
    </x-ui.section-card>

    @if (session()->has('success'))
        <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ session('success') }}</div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800 dark:bg-red-900/30 dark:text-red-300">{{ session('error') }}</div>
    @endif

    <x-ui.section-card title="Shift Management" subtitle="Clock in and clock out from your active branch" bodyClass="p-6">
        @if ($currentShift)
            <div class="space-y-6 text-center">
                <div class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-300">
                    Active Shift
                </div>

                <div class="mx-auto max-w-md space-y-2 text-sm text-slate-600 dark:text-slate-300">
                    <p><strong>Branch:</strong> {{ $currentShift->branch->name }}</p>
                    <p><strong>Clock In Time:</strong> {{ $currentShift->clock_in->format('d M Y, h:i A') }}</p>
                    <p><strong>Duration:</strong> {{ $currentShift->clock_in->diffForHumans(null, true) }}</p>
                </div>

                <div>
                    <button wire:click="clockOut" class="rounded-lg bg-red-600 px-6 py-3 text-base font-semibold text-white transition hover:bg-red-700">
                        Clock Out
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-6 text-center">
                <div class="inline-flex items-center rounded-full bg-slate-200 px-3 py-1 text-sm font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                    No Active Shift
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-slate-100">You are currently clocked out</h3>
                    <p class="text-slate-500 dark:text-slate-400">Start a new shift to begin making sales.</p>
                </div>

                <div>
                    <button wire:click="openClockInModal" class="rounded-lg bg-blue-600 px-6 py-3 text-base font-semibold text-white transition hover:bg-blue-700">
                        Start Shift / Clock In
                    </button>
                </div>
            </div>
        @endif
    </x-ui.section-card>

    @if ($showClockInModal)
        <x-ui.modal-shell maxWidth="max-w-md" title="Start New Shift" description="Choose your branch and starting float before clocking in.">
            <form wire:submit.prevent="clockIn" class="space-y-4">
                <div>
                    <label for="branchId" class="block text-sm text-slate-700 dark:text-slate-300">Branch</label>
                    <select wire:model="branchId" id="branchId" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        <option value="">Select a branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    @error('branchId') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="startingCash" class="block text-sm text-slate-700 dark:text-slate-300">Starting Cash (Float)</label>
                    <input type="number" step="0.01" wire:model="startingCash" id="startingCash" class="w-full rounded-lg border border-slate-300 p-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" placeholder="e.g., 5000.00">
                    @error('startingCash') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" wire:click="$set('showClockInModal', false)" class="rounded-lg bg-slate-200 px-4 py-2 text-slate-800 transition hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">Cancel</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">Clock In</button>
                </div>
            </form>
        </x-ui.modal-shell>
    @endif
</div>
