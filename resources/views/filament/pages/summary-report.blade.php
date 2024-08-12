<x-filament::page>

    <!-- Month Picker and Provider Filter Form -->
    <div class="mb-4">
        <form method="GET" action="{{ static::getUrl() }}" class="space-y-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="selected_month" class="block text-sm font-medium text-gray-700">Select Month</label>
                    <input
                        type="month"
                        id="selected_month"
                        name="selected_month"
                        value="{{ $this->selectedMonth }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                </div>
            </div>
            <div>
                <label for="provider" class="block text-sm font-medium text-gray-700">Select Provider</label>
                <select
                    id="provider"
                    name="provider"
                    class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                    <option value="">Select a provider</option>
                    @foreach ($this->getInsuranceProviders() as $provider)
                        <option value="{{ $provider->insurance_provider_id }}" {{ $provider->insurance_provider_id == request()->query('provider') ? 'selected' : '' }}>
                            {{ $provider->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-black bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Apply Filters
                </button>
                <button type="button" onclick="location.href='{{ static::getUrl() }}'" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-orange bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Reset Filters
                </button>
            </div>
        </form>
        <form method="GET" action="{{ route('exportData') }}" class="inline-flex items-center">
    <input type="hidden" name="selected_month" value="{{ $this->selectedMonth }}">
    <input type="hidden" name="provider" value="{{ request()->query('provider') }}">
    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-black bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
        Export
    </button>
</form>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-slate-300" colspan="{{ count($this->getProviderHeaders()) + 2 }}">
                    {{ $this->selectedMonth ? \Illuminate\Support\Carbon::parse($this->selectedMonth)->format('F Y') : 'All Time' }}
                    </th>
                </tr>
                <tr>
                    <th class="border border-slate-300">Per Branch</th>
                    @foreach ($this->getProviderHeaders() as $header)
                        <th class="border border-slate-300">{{ $header }}</th>
                    @endforeach
                    <th class="border border-slate-300">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->getCostCenters() as $costCenter)
        
                    <tr>
                        <td class="border border-slate-300">{{ $costCenter->name }}</td>
                        <!-- Add appropriate data columns based on the selected provider and insurance types -->
                        @foreach ($this->getProviderHeaders() as $header)
                            <td class="border border-slate-300">{{ number_format( $this->getGrossPremium($costCenter->cost_center_id, $header), 2, '.', ',') }}</td>
                        @endforeach
                        <td class="border border-slate-300">{{ number_format( $this->getTotalGrossPremium($costCenter->cost_center_id), 2, '.', ',') }}</td>
                       
                    </tr>
                   
                @endforeach


                <tr>
                    <td class="border border-slate-300 font-bold">TOTAL</td>
                    @foreach ($this->getProviderHeaders() as $header)
                        <td class="border border-slate-300 font-bold">{{ number_format( $this->getTotalForHeader($header) , 2, '.', ',') }}</td>
                    @endforeach
                    <td class="border border-slate-300 font-bold">{{ number_format( $this->getGrandTotal() , 2, '.', ',') }}</td>
                </tr>
                    
            </tbody>
        </table>
    </div>
    
</x-filament::page>
