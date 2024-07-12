<x-filament-panels::page.simple>
    <div class="flex flex-col items-center space-y-6 w-full">
        <img src="{{ asset('images/aap-logo.png') }}" alt="AAP logo" class="aap-login-logo w-48 h-auto">

        <!-- Move the heading here -->
        <h1 class="text-center text-2xl font-bold tracking-tight text-gray-950 dark:text-white">{{ __('Login') }}</h1>
        
        <x-filament-panels::form wire:submit="authenticate" class="w-full">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :full-width="true"
                :actions="$this->getCachedFormActions()"
            />
        </x-filament-panels::form>
    </div>
</x-filament-panels::page.simple>
