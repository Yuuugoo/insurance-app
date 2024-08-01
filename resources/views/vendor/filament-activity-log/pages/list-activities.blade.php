@php
    use \Illuminate\Support\Js;
@endphp
<x-filament-panels::page>

    <div class="space-y-6">
        @foreach($this->getActivities() as $activityItem)
            @php
                $changes = $activityItem->getChangesAttribute();
                $fieldLabels = [
                    'arpr_num' => 'AR/PR NO.',
                    'arpr_date' => 'AR/PR Date',
                    'sale_person' => 'Sales Person',
                    'depo_slip' => 'Deposit Slip',
                    'final_depo_slip' => 'Final Deposit Slip',
                    'insurance_prod' => 'Insurance Provider',
                    'insurance_type' => 'Insurance Type',
                    'inception_date' => 'Inception Date',
                    'assured' => 'Assured',
                    'policy_num' => 'Policy Number',
                    'application' => 'Mode of Application',
                    'cashier_remarks' => 'Cashier Remarks',
                    'remit_date' => 'Date Remitted',
                    'acct_remarks' => 'Accounting Remarks',
                    'policy_file' => 'Policy File',
                    'terms' => 'Terms',
                    'gross_premium' => 'Gross Premium',
                    'payment_balance' => 'Payment Balance',
                    'payment_mode' => 'Mode of Payment',
                    'total_payment' => 'Total Payment',
                    'plate_num' => 'Plate Number',
                    'car_details' => 'Car Details',
                    'policy_status' => 'Policy Status',
                    'financing_bank' => 'Mortgagee/Financing Bank',
                    'payment_status' => 'Payment Status',
                    'remit_date_partial' => 'Final Remittance Date',
                    'others_insurance_type' => 'Others Insurance Type',
                    'others_insurance_prod' => 'Others Insurance Provider',
                    'others_application' => 'Others Mode of Application'
                    
                ];
            @endphp
            <div class="bg-white rounded-xl shadow overflow-hidden dark:bg-gray-800">
                <div class="p-4 border-b dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 gap-4">
                        @if ($activityItem->causer && $activityItem->causer->avatar_url)
                            <x-filament-panels::avatar.user
                                :user="$activityItem->causer"
                                :src="asset($activityItem->causer->avatar_url)"
                                class="w-10 h-10"
                            />
                        @else
                            <img src="{{ asset('default-avatar.png') }}" class="w-10 h-10 rounded-full">
                        @endif
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $activityItem->causer?->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @lang('filament-activity-log::activities.events.' . $activityItem->event) 
                                    <span class="font-medium">{{ $activityItem->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @if ($this->canRestoreActivity() && $changes->isNotEmpty())
                            <x-filament::button
                                size="sm"
                                icon="heroicon-o-arrow-path"
                                wire:click="restoreActivity({{ \Illuminate\Support\Js::from($activityItem->getKey()) }})"
                            >
                                @lang('filament-activity-log::activities.table.restore')
                            </x-filament::button>
                        @endif
                    </div>
                </div>
                @if ($changes->isNotEmpty())
                    <div class="p-4">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs uppercase">
                                    <th class="pb-2">Field</th>
                                    <th class="pb-2">Old Value</th>
                                    <th class="pb-2">New Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (data_get($changes, 'attributes', []) as $field => $newValue)
                                    @php
                                        $oldValue = data_get($changes, "old.{$field}");
                                        $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
                                    @endphp
                                    @if ($oldValue !== $newValue)
                                        <tr>
                                            <td class="py-2 pr-4 font-medium">{{ $label }}</td>
                                            <td class="py-2 pr-4">
                                                <span class="inline-block px-2 py-1">
                                                    @if(is_array($oldValue))
                                                        <pre class="text-xs">{{ json_encode($oldValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    @else
                                                        {{ $oldValue }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="py-2">
                                                <div style="color: #004cb5; ">
                                                <span class="inline-block px-2 py-1 rounded">
                                                    @if(is_array($newValue))
                                                        <pre class="text-xs">{{ json_encode($newValue, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    @else
                                                        {{ $newValue }}
                                                    @endif
                                                </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
