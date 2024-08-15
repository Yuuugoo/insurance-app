<x-filament-panels::page.simple>
    <div class="flex place-content-center">
    <img src="{{ asset('images/aap-logo.png') }}" alt="AAP logo" class="aap-login-logo w-48 -mt-0 h-auto">
    </div>
    <div class="flex flex-col items-center space-y-6 w-full">
        <!-- Move the heading here -->
        <div class="-mt-10">
            <h1 class="text-center text-2xl tracking-wide text-gray-950 dark:text-white">{{ __('Insurance Report System') }}</h1>
            <h1 class="text-center text-2xl font-bold tracking-tight text-gray-950 dark:text-white">{{ __('Login') }}</h1>
        </div>

        <x-filament-panels::form wire:submit="authenticate" class="w-full">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :full-width="true"
                :actions="$this->getCachedFormActions()"
            />
        </x-filament-panels::form>
    </div>
</x-filament-panels::page.simple>
