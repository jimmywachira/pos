<div class="flex items-start gap-6 max-md:flex-col">
    <x-ui.section-card class="me-0 w-full md:w-[240px]" bodyClass="p-3">
        <flux:navlist>
            <flux:navlist.item :href="route('settings.profile')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
            <flux:navlist.item :href="route('settings.password')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item :href="route('two-factor.show')" wire:navigate>{{ __('Two-Factor Auth') }}</flux:navlist.item>
            @endif
            <flux:navlist.item :href="route('settings.appearance')" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
        </flux:navlist>
    </x-ui.section-card>

    <flux:separator class="md:hidden" />

    <x-ui.section-card class="flex-1 self-stretch" :title="$heading ?? ''" :subtitle="$subheading ?? ''" bodyClass="p-6">
        <flux:heading class="sr-only">{{ $heading ?? '' }}</flux:heading>
        <flux:subheading class="sr-only">{{ $subheading ?? '' }}</flux:subheading>

        <div class="w-full max-w-2xl">
            {{ $slot }}
        </div>
    </x-ui.section-card>
</div>
