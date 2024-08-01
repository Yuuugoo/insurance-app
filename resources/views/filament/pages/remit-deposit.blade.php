
<div class="min-w-0 flex-1">
    <div class="min-w-max">
        <div class="inline-block min-w-full">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 md:w-1 lg:w-3 ">
                <thead>
                    <tr>
                        <th class="px-0 py-0 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Deposit Slip</th>
                        <th class="md:px-3 sm:px-2 px-6 py-0"></th>
                        <th class="md:px-3 sm:px-2 px-6 py-0 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Remit Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @foreach($remit_depo as $remitDepo)
                        @if($remitDepo)
                            <tr>
                                <td class="px-0 md:py-3 sm:py-2 py-4 whitespace-nowrap">
                                    <div class="text-sm leading-6 text-custom-600 dark:text-custom-400 overflow-hidden text-ellipsis"
                                        style="--c-400:var(--primary-400);--c-600:var(--primary-600); max-width: 100px;"
                                        title="{{ $remitDepo['depo_slip_filename'] }}">
                                        {{ $remitDepo['depo_slip_filename'] }}
                                    </div>
                                </td>
                                <td class="md:px-3 sm:px-2 px-6 md:py-3 sm:py-2 py-4 whitespace-nowrap">
                                    <div class="flex lg:flex-row flex-col items-start lg:items-center gap-2">
                                        <a href="/storage/{{ $remitDepo['depo_slip'] }}"
                                            target="_blank"
                                            class="px-3 py-1 md:w-auto lg:w-auto w-full text-center" style="background-color: #004CB5; color: white; border-radius: 0.25rem; transition-property: background-color; transition-duration: 300ms; transition-timing-function: ease-in-out;">
                                            View
                                        </a>
                                        <a href="/storage/{{ $remitDepo['depo_slip'] }}"
                                            download
                                            class="px-3 py-1 lg:w-auto w-full text-center" style="background-color: #004CB5; color: white; border-radius: 0.25rem; transition-property: background-color; transition-duration: 300ms; transition-timing-function: ease-in-out;">
                                            Download
                                        </a>
                                    </div>
                                </td>
                                <td class="md:px-3 sm:px-2 px-6 md:py-3 sm:py-2 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium leading-6 dark:text-white">{{ $remitDepo['remit_date'] }}</span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>