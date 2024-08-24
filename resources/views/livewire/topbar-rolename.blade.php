<div class="role_name absolute right-20 text-gray-700 text-sm">
    @php
        $roleName = Auth::user()->roles->first()->name ?? '';
    @endphp

    @if ($roleName == 'acct-staff')
        Accounting Staff
    @elseif ($roleName == 'cashier')
        Cashier
    @elseif ($roleName == 'acct-manager')
        Accounting Manager
    @elseif ($roleName == 'cfo')
        Chief Financial Officer
    @elseif ($roleName == 'super-admin')
        Super Admin
    @elseif ($roleName == 'agent')
        Agent
    @else
        {{ $roleName }}
    @endif
</div>
