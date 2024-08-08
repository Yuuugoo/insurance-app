<x-filament::page>
    <!-- Provider Filter Dropdown -->
    <div class="mb-4">
        <form method="GET" action="{{ url()->current() }}">
            <label for="provider" class="block text-sm font-medium text-gray-700">Select Provider</label>
            <select
                id="provider"
                name="provider"
                onchange="this.form.submit()"
                class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
                <option value="">Select a provider</option>
                @foreach ($this->getInsuranceProviders() as $provider)
                    <option value="{{ $provider->insurance_provider_id }}" {{ $provider->insurance_provider_id == request()->query('provider') ? 'selected' : '' }}>
                        {{ $provider->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-slate-300">Year to Date</th>
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
                            <td class="border border-slate-300">{{ $this->getGrossPremium() }}</td>
                        @endforeach
                        <td class="border border-slate-300"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <!-- component -->





<!-- ====== Table Section End -->
</x-filament::page>
