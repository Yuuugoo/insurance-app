<x-filament::page>
    <div class="flex grid-cols-2 place-content-end">
        <x-filament::button outlined
            href="{{ route('filament.admin.pages.summary-reports-page') }}"
            tag="a"
            size="xl"
            color="aap-blue"
        >
            Back
        </x-filament::button>
    </div>

    <div class="mb-1 bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ static::getUrl() }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="quarter" class="block text-sm font-medium text-gray-700">Select Quarter</label>
                    <select
                        id="quarter"
                        name="quarter"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                        <option value="">Select Quarter</option>
                        <option value="Q1" {{ $this->quarter == 'Q1' ? 'selected' : '' }}>Q1 (Jan-Mar)</option>
                        <option value="Q2" {{ $this->quarter == 'Q2' ? 'selected' : '' }}>Q2 (Apr-Jun)</option>
                        <option value="Q3" {{ $this->quarter == 'Q3' ? 'selected' : '' }}>Q3 (Jul-Sep)</option>
                        <option value="Q4" {{ $this->quarter == 'Q4' ? 'selected' : '' }}>Q4 (Oct-Dec)</option>
                    </select>
                </div>
                <div>
                    <label for="start_month" class="block text-sm font-medium text-gray-700">Select Start Month</label>
                    <input
                        type="month"
                        id="start_month"
                        name="start_month"
                        value="{{ $this->startMonth }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                </div>
                <div>
                    <label for="end_month" class="block text-sm font-medium text-gray-700">Select End Month</label>
                    <input
                        type="month"
                        id="end_month"
                        name="end_month"
                        value="{{ $this->endMonth }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                </div>
                <div>
                    <label for="provider" class="block text-sm font-medium text-gray-700">Select Provider</label>
                    <select
                        id="provider"
                        name="provider"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                        <option value="">Select Insurance Provider</option>
                        @foreach ($this->getInsuranceProviders() as $provider)
                            <option value="{{ $provider->insurance_provider_id }}" {{ $provider->insurance_provider_id == request()->query('provider') ? 'selected' : '' }}>
                                {{ $provider->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex flex-col place-content-end md:flex-row space-y-1 md:space-y-0 md:space-x-3">
                <button type="submit" class="w-full md:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Apply Filters
                </button>
                <button type="button" onclick="location.href='{{ static::getUrl() }}'" class="md:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Reset Filters
                </button>
                <button type="submit" form="export-form" class="px-3 py-2 bg-green text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Export
                </button>
            </div>
        </form>
        <form id="export-form" method="GET" action="{{ route('exportData') }}" class="hidden">
            <input type="hidden" name="start_month" value="{{ $this->startMonth }}">
            <input type="hidden" name="end_month" value="{{ $this->endMonth }}">
            <input type="hidden" name="provider" value="{{ request()->query('provider') }}">
            <input type="hidden" name="quarter" value="{{ $this->quarter }}">
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-3 py-3 text-center text-xs text-gray-900 uppercase tracking-wider" colspan="{{ count($this->getProviderHeaders()) + 2 }}">
                    @foreach ($this->getInsuranceProviders() as $provider)
                        @if ($provider->insurance_provider_id == request()->query('provider'))
                            {{ $provider->name }}
                        @endif
                    @endforeach

                    @if($this->quarter)
                        {{ $this->quarter }} - {{ Carbon\Carbon::now()->year }}
                    @elseif($this->startMonth !== $this->endMonth)
                        {{ Carbon\Carbon::parse($this->startMonth)->format('F Y') }} - 
                        {{ Carbon\Carbon::parse($this->endMonth)->format('F Y') }}
                    @else
                    All Reports {{ Illuminate\Support\Carbon::now()->format('Y') }}
                    @endif
                    </th>
                </tr>
                <tr class="bg-gray-50">
                    <th class="px-3 py-1 text-left text-xs text-gray-900 uppercase tracking-wider">Branches</th>
                    @foreach ($this->getProviderHeaders() as $header)
                        <th class="px-3 py-1 text-center text-xs text-gray-900 uppercase tracking-wider">{{ $header }}</th>
                    @endforeach
                    <th class="px-3 py-1 text-center text-xs text-gray-900 uppercase tracking-wider">TOTAL</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($this->getCostCenters() as $costCenter)
                    <tr>
                        <td class="px-3 py-0 whitespace-nowrap text-sm font-medium text-gray-900">{{ $costCenter->name }}</td>
                        @foreach ($this->getProviderHeaders() as $header)
                            <td class="px-3 py-0 whitespace-nowrap text-center text-sm text-gray-500">{{ number_format($this->getGrossPremium($costCenter->cost_center_id, $header), 2, '.', ',') }}</td>
                        @endforeach
                        <td class="px-3 py-0 whitespace-nowrap text-center text-sm text-gray-500">{{ number_format($this->getTotalGrossPremium($costCenter->cost_center_id), 2, '.', ',') }}</td>
                    </tr>
                @endforeach
                <tr class="bg-gray-50 font-bold">
                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">TOTAL</td>
                    @foreach ($this->getProviderHeaders() as $header)
                        <td class="px-3 py-3 whitespace-nowrap text-center text-sm text-gray-900">{{ number_format($this->getTotalForHeader($header), 2, '.', ',') }}</td>
                    @endforeach
                    <td class="px-3 py-3 whitespace-nowrap text-center text-sm text-gray-900">{{ number_format($this->getGrandTotal(), 2, '.', ',') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-filament::page>