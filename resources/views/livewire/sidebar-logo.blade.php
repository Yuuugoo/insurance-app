<div class="aap-logo">

    @php
        $role = auth()->check() ? auth()->user()->role : null;
    @endphp

    <a href="
        @if ($role == 'cashier')
            {{ route('filament.admin.pages.cashier-dashboard') }}
        @elseif ($role == 'acct-staff')
            {{ route('filament.admin.pages.staff-dashboard') }}
        @elseif ($role == 'acct-manager')
            {{ route('filament.admin.pages.manager-dashboard') }}
        @elseif ($role == 'super-admin')
            {{ route('filament.admin.pages.super-admin-dashboard') }}
        @else 
            {{ route('filament.admin.auth.login') }}
        @endif
    ">
        <img src="{{ asset('images/aap-logo-2.png') }}" alt="aap-logo">
    </a>

</div>