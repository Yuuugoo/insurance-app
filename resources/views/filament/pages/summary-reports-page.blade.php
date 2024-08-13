<x-filament-panels::page>
    <x-filament::button outlined
        href="{{ route('filament.admin.pages.per-branch-page') }}"
        tag="a"
        size="xl"
        color="aap-blue"
        tooltip="View Per Branch Summary Reports"
    >
        Summary Per Branch
    </x-filament::button>

    <x-filament::button outlined
        href="{{ route('filament.admin.pages.per-month-page') }}"
        tag="a"
        size="xl"
        color="aap-blue"
        tooltip="View Per Branch Summary Reports"
    >
        Summary Per Month
    </x-filament::button>

    <x-filament::button outlined
        href="{{ route('filament.admin.pages.per-salesperson-page') }}"
        tag="a"
        size="xl"
        color="aap-blue"
        tooltip="View Per Branch Summary Reports"
    >
        Summary Per Salesperson
    </x-filament::button>
    
</x-filament-panels::page>
