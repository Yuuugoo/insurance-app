<div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-3 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div class="grid gap-y-2">
        <div class="flex items-center gap-x-2">
            <span class="fi-wi-stats-overview-stat-label text-sm font-medium text-gray-500 dark:text-gray-400">
                Due Dates Today: {{ $count }}
            </span>
        </div>

       

        @if ($dueRecords->isEmpty())
            <div class="text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                No due payments today
            </div>
        @else
            @php
                $showFirstPaymentDate = $dueRecords->contains(fn($record) => $record->getAttribute('1st_is_paid') !== 1);
                $show2ndPaymentDate = $dueRecords->contains(fn($record) => $record->getAttribute('2nd_is_paid') !== 1);
            @endphp

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                           

                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                AR/PR No
                            </th>

                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Policy Number
                            </th>

                            @if ($showFirstPaymentDate)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    1st Payment Date
                                </th>
                            @endif


                            @if ($show2ndPaymentDate)
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    2nd Payment Date
                                </th>
                            @endif
                           
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @foreach ($dueRecords as $record)
                            <tr onclick="window.open('{{ route('filament.admin.resources.reports.view', ['record' => $record->getKey()]) }}', '_blank')"
                                class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800">
                               
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $record->getAttribute('arpr_num') ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $record->getAttribute('policy_num') ?? 'N/A' }}
                                </td>

                                @if ($showFirstPaymentDate)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $record->getAttribute('1st_payment_date') ? \Carbon\Carbon::parse($record->getAttribute('1st_payment_date'))->format('M d, Y') : 'N/A' }}
                                    </td>
                                @endif

                                @if ($show2ndPaymentDate)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $record->getAttribute('2nd_payment_date') ? \Carbon\Carbon::parse($record->getAttribute('2nd_payment_date'))->format('M d, Y') : 'N/A' }}
                                    </td>
                                @endif
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
