@php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3 ">
            <x-filament-panels::avatar.user size="lg" :user="$user" />

            <div class="flex-1">
                <h2
                    class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white"
                >
                    {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ filament()->getUserName($user) }}
                </p>
            </div>

            <form
                action="{{ filament()->getLogoutUrl() }}"
                method="post"
                class="my-auto"
            >
                @csrf

                <x-filament::button
                    color="gray"
                    icon="heroicon-m-arrow-left-on-rectangle"
                    icon-alias="panels::widgets.account.logout-button"
                    labeled-from="sm"
                    tag="button"
                    type="submit"
                    x-data="{}"
                    x-on:click.prevent="$dispatch('open-modal', { id: 'logout-confirmation' })"
                    >
                    {{ __('filament-panels::widgets/account-widget.actions.logout.label') }}
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>

    <x-filament::modal
    id="logout-confirmation"
    :heading="__('Confirm Logout')"
    alignment="center"
    >
        <p>{{ __('Are you sure you want to log out?') }}</p>

        <x-slot name="footerActions">
            <x-filament::button
                color="gray"
                x-on:click="$dispatch('close-modal', { id: 'logout-confirmation' })"
            >
                {{ __('Cancel') }}
            </x-filament::button>

            <x-filament::button
                color="danger"
                type="submit"
                form="logout-form"
            >
                {{ __('Logout') }}
            </x-filament::button>
        </x-slot>
    </x-filament::modal>

    <form id="logout-form" action="{{ filament()->getLogoutUrl() }}" method="post" class="hidden">
        @csrf
    </form>
</x-filament-widgets::widget>
