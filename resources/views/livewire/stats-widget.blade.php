<div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-3 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div class="grid gap-y-2">
        <div class="flex items-center justify-between gap-x-2">
            <span wire:click="toggleTable" class="fi-wi-stats-overview-stat-label text-sm font-medium text-orange dark:text-orange cursor-pointer flex items-center">
                Due Dates Today: {{ $count }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform duration-200 ease-in-out {{ $showTable ? 'transform rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </div>

        @if (!$showTable)
            @if ($dueRecords->isEmpty())
                <div class="text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                    No due payments today
                </div>
            @endif
        @else
            @if ($dueRecords->isEmpty())
                <div class="text-lg font-semibold tracking-tight text-gray-950 dark:text-white">
                    No due payments today
                </div>
            @else
                @php
                    $today = \Carbon\Carbon::today();

                    $showFirstPaymentDate = $dueRecords->contains(fn($record) => $record->getAttribute('1st_payment_date') && \Carbon\Carbon::parse($record->getAttribute('1st_payment_date'))->isSameDay($today) && $record->getAttribute('1st_is_paid') !== 1);
                    $show2ndPaymentDate = $dueRecords->contains(fn($record) => $record->getAttribute('2nd_payment_date') && \Carbon\Carbon::parse($record->getAttribute('2nd_payment_date'))->isSameDay($today) && $record->getAttribute('2nd_is_paid') !== 1);
                    $show3rdPaymentDate = $dueRecords->contains(fn($record) => $record->getAttribute('3rd_payment_date') && \Carbon\Carbon::parse($record->getAttribute('3rd_payment_date'))->isSameDay($today) && $record->getAttribute('3rd_is_paid') !== 1);
                    $show4thPaymentDate = $dueRecords->contains(fn($record) => $record->getAttribute('4th_payment_date') && \Carbon\Carbon::parse($record->getAttribute('4th_payment_date'))->isSameDay($today) && $record->getAttribute('4th_is_paid') !== 1);
                    $show5thPaymentDate = $dueRecords->contains(fn($record) => $record->getAttribute('5th_payment_date') && \Carbon\Carbon::parse($record->getAttribute('5th_payment_date'))->isSameDay($today) && $record->getAttribute('5th_is_paid') !== 1);
                    $show6thPaymentDate = $dueRecords->contains(fn($record) => $record->getAttribute('6th_payment_date') && \Carbon\Carbon::parse($record->getAttribute('6th_payment_date'))->isSameDay($today) && $record->getAttribute('6th_is_paid') !== 1);
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Cost Center
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

                                @if ($show3rdPaymentDate)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        3rd Payment Date
                                    </th>
                                @endif

                                @if ($show4thPaymentDate)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        4th Payment Date
                                    </th>
                                @endif

                                @if ($show5thPaymentDate)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        5th Payment Date
                                    </th>
                                @endif

                                @if ($show6thPaymentDate)
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        6th Payment Date
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $record->costCenter->name ?? 'N/A' }}
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

                                    @if ($show3rdPaymentDate)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $record->getAttribute('3rd_payment_date') ? \Carbon\Carbon::parse($record->getAttribute('3rd_payment_date'))->format('M d, Y') : 'N/A' }}
                                        </td>
                                    @endif

                                    @if ($show4thPaymentDate)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $record->getAttribute('4th_payment_date') ? \Carbon\Carbon::parse($record->getAttribute('4th_payment_date'))->format('M d, Y') : 'N/A' }}
                                        </td>
                                    @endif

                                    @if ($show5thPaymentDate)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $record->getAttribute('5th_payment_date') ? \Carbon\Carbon::parse($record->getAttribute('5th_payment_date'))->format('M d, Y') : 'N/A' }}
                                        </td>
                                    @endif

                                    @if ($show6thPaymentDate)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $record->getAttribute('6th_payment_date') ? \Carbon\Carbon::parse($record->getAttribute('6th_payment_date'))->format('M d, Y') : 'N/A' }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="px-6 py-4">
                        {{ $dueRecords->links() }} <!-- Pagination links -->
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>