<div>
    @php
        $role = auth()->check() ? auth()->user()->role : null;
        $loginRoute = route('filament.admin.auth.login');
    @endphp

    <footer class="z-20 w-full p-4 bg-aap-footer-bg border-t border-gray-200 shadow md:p-6 dark:bg-aap-footer-label dark:border-aap-footer-label text-center">
        <span class="text-sm text-gray-500 dark:text-gray-400">© 2024
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
            " class="hover:underline">Automobile Association Philippines</a>. All Rights Reserved.
        </span>
    </footer>
</div>
