<x-app-admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Invoices Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding: 3%;">
                <h1>Hola, esta es la gestion de facturas</h1>
                <livewire:invoice-table />
            </div>
        </div>
    </div>
</x-app-admin>