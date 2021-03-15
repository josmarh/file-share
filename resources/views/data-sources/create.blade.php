<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Source') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <form method="POST" action="{{ route('data-sources.store') }}" id="dsource">
            @csrf
            <div class="form-group">
                <label for="datasource">Data source</label>
                <input type="text" id="ds-field" class="form-control block mt-1 w-full border-gray-300 focus:border-indigo-300 
                        focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"  name="datasource" required />
            </div>
            <button type="submit" class="btn btn-primary float-right" id="btn">Save </button>
        </form>

        </div>
    </div>

</x-app-layout>