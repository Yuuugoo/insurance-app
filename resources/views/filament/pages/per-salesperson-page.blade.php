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
                    <label for="start_month" class="block text-sm font-medium text-gray-700">Start Month</label>
                    <input
                        type="month"
                        id="start_month"
                        name="start_month"
                        value="{{ $this->startMonth }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                </div>
                <div>
                    <label for="end_month" class="block text-sm font-medium text-gray-700">End Month</label>
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
                    <option value="{{ $provider->insurance_provider_id }}" {{ $provider->insurance_provider_id == $this->selectedProvider ? 'selected' : '' }}>
                    {{ $provider->name }}
                    </option>
                    @endforeach
                    </select>
                </div>
                <div>
                    <label for="cost_center" class="block text-sm font-medium text-gray-700">Select Cost Center</label>
                    <select
                        id="cost_center"
                        name="cost_center"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                    <option value="">Select Cost Center</option>
                    @foreach ($this->getCostCenters() as $costCenter)
                    <option value="{{ $costCenter->cost_center_id }}" {{ $costCenter->cost_center_id == $this->selectedCostCenter ? 'selected' : '' }}>
                    {{ $costCenter->name }}
                    </option>
                    @endforeach
                    </select>
                </div>
            </div>
        <div class="flex flex-col md:flex-row place-content-end space-y-1 md:space-y-0 md:space-x-2">
            <button type="submit" class="md:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
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
        <form id="export-form" method="GET" action="{{ route('exportPerSalesperson') }}" class="hidden">
        <input type="hidden" name="start_month" value="{{ $this->startMonth }}">
        <input type="hidden" name="end_month" value="{{ $this->endMonth }}">
            <input type="hidden" name="provider" value="{{ $this->selectedProvider }}">
            <input type="hidden" name="cost_center" value="{{ $this->selectedCostCenter }}">
        </form>

    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-3 py-3 text-center text-xs text-gray-900 uppercase tracking-wider" colspan="{{ count($this->getInsuranceTypes()) + 2 }}">
                    @php
                        $headerParts = [];
                        if ($this->selectedProvider) {
                            $provider = $this->getInsuranceProviders()->where('insurance_provider_id', $this->selectedProvider)->first();
                            if ($provider) {
                                $headerParts[] = $provider->name;
                            }
                        }
                        if ($this->selectedCostCenter) {
                            $costCenter = $this->getCostCenters()->where('cost_center_id', $this->selectedCostCenter)->first();
                            if ($costCenter) {
                                $headerParts[] = $costCenter->name;
                            }
                        }
                        if (empty($headerParts)) {
                            $headerParts[] = 'ALL REPORTS';
                        }
                        $startDate = Carbon\Carbon::createFromFormat('Y-m', $this->startMonth);
                        $endDate = Carbon\Carbon::createFromFormat('Y-m', $this->endMonth);
                        if($startDate->format('Y-m') == $endDate->format('Y-m')) {
                            $headerParts[] = $startDate->format('F Y');
                        } else {
                            $headerParts[] = $startDate->format('F Y') . ' - ' . $endDate->format('F Y');
                        }
                    @endphp
                        {{ strtoupper(implode(' - ', $headerParts)) }}
                    </th>
                </tr>
                <tr class="bg-gray-50">
                    <th class="px-3 py-1 text-left text-xs text-gray-900 uppercase tracking-wider">SALESPERSON</th>
                    @if ($this->selectedProvider == null)
                        @foreach ($this->getInsuranceTypes() as $insuranceType)
                            <th class="px-3 py-1 text-center text-xs text-gray-900 uppercase tracking-wider">{{ strtoupper($insuranceType->name) }}</th>
                        @endforeach
                    @else
                        @foreach ($this->getProviderHeaders() as $header)
                            <th class="px-3 py-1 text-center text-xs text-gray-900 uppercase tracking-wider">{{ $header }}</th>
                        @endforeach    
                    @endif
                    <th class="px-3 py-1 text-center text-xs text-gray-900 uppercase tracking-wider">TOTAL</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($this->getSalespersons() as $salesperson)
                    <tr>
                        <td class="px-3 py-0 whitespace-nowrap text-sm font-medium text-gray-900">{{ $salesperson->name }}</td>
                        @foreach ($this->getInsuranceTypes() as $insuranceType)
                            <td class="px-3 py-0 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ number_format($this->getSalespersonGrossPremium($salesperson->id, $insuranceType->insurance_type_id), 2, '.', ',') }}
                            </td>
                        @endforeach
                        <td class="px-3 py-0 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                            {{ number_format($this->getTotalForSalesperson($salesperson->id), 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-gray-50 font-bold">
                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">TOTAL</td>
                    @foreach ($this->getInsuranceTypes() as $insuranceType)
                        <td class="px-3 py-3 whitespace-nowrap text-center text-sm text-gray-900">
                            {{ number_format($this->getTotalForInsuranceType($insuranceType->insurance_type_id), 2, '.', ',') }}
                        </td>
                    @endforeach
                    <td class="px-3 py-3 whitespace-nowrap text-center text-sm text-gray-900">
                        {{ number_format($this->getGrandTotal(), 2, '.', ',') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-filament::page>