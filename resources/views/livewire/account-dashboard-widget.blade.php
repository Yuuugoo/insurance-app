@php
    $user = filament()->auth()->user();
    $currentTime = now();
    $greeting = 'Good ' . ($currentTime->hour < 12 ? 'morning' : ($currentTime->hour < 18 ? 'afternoon' : 'evening'));
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="$user" />
            <div class="flex-1">
                <h2 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                    {{ $greeting }}, {{ $user->name }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $currentTime->format('l, M j H:i') }}
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>


