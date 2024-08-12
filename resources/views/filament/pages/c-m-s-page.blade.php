<x-filament-panels::page>
    <x-filament::button outlined
        href="{{ route('filament.admin.resources.cost-center-simples.index') }}"
        tag="a"
        size="xl"
        color="aap-blue"
        tooltip="Create a new cost center"
    >
        Add Cost Center
    </x-filament::button>

    <x-filament::button outlined
        href="{{ route('filament.admin.resources.insurance-provider-simples.index') }}"
        tag="a"
        size="xl"
        color="aap-blue"
        tooltip="Create a new Insurance Provider"
    >
        Add Insurance Provider
    </x-filament::button>

    <x-filament::button outlined
        href="{{ route('filament.admin.resources.insurance-type-simples.index') }}"
        tag="a"
        size="xl"
        color="aap-blue"
        tooltip="Create a new Insurance Type"
    >
        Add Insurance Type
    </x-filament::button>

    <x-filament::button outlined
        href="{{ route('filament.admin.resources.insurance-type-simples.index') }}"
        tag="a"
        size="xl"
        color="aap-blue"
        tooltip="Create a new Sales Person"
    >
        Add Sales Person
    </x-filament::button>
</x-filament-panels::page>