<x-app-admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Produts Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-10xl sm:px-6 lg:px-12">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding: 2%;">
                <h1>Hola, esta es la gestion de productos</h1>
                <livewire:product-table />

                <div id="editModal"
                    class="fixed inset-0 flex items-center justify-center hidden bg-gray-600 bg-opacity-50">
                    <div class="w-full max-w-3xl p-4 mx-auto my-4 bg-white rounded shadow-lg">
                        <h2 class="mb-4 text-lg font-semibold">Editar Producto</h2>
                        <form id="editProductForm">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <!-- Campo: name -->
                                <div>
                                    <label for="editName" class="block mb-1 font-medium">Nombre</label>
                                    <input type="text" id="editName" class="w-full p-2 border" placeholder="Nombre">
                                </div>

                                <!-- Select: warehouse_id -->
                                <div>
                                    <label for="editWarehouse" class="block mb-1 font-medium">Almacén</label>
                                    <select id="editWarehouse" class="w-full p-2 border">
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>

                                <!-- Select: category_id -->
                                <div>
                                    <label for="editCategory" class="block mb-1 font-medium">Categoría</label>
                                    <select id="editCategory" class="w-full p-2 border">
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>

                                <!-- Select: variant_id -->
                                <div>
                                    <label for="editVariant" class="block mb-1 font-medium">Variante</label>
                                    <select id="editVariant" class="w-full p-2 border">
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>

                                <!-- Select: vendor_id -->
                                <div>
                                    <label for="editVendor" class="block mb-1 font-medium">Vendedor</label>
                                    <select id="editVendor" class="w-full p-2 border">
                                        <!-- Opciones dinámicas -->
                                    </select>
                                </div>

                                <!-- Campo: sku -->
                                <div>
                                    <label for="editSku" class="block mb-1 font-medium">SKU</label>
                                    <input type="text" id="editSku" class="w-full p-2 border" placeholder="SKU">
                                </div>

                                <!-- Campo: barcode -->
                                <div>
                                    <label for="editBarcode" class="block mb-1 font-medium">Barcode</label>
                                    <input type="text" id="editBarcode" class="w-full p-2 border" placeholder="Barcode">
                                </div>

                                <!-- Campo: stock -->
                                <div>
                                    <label for="editStock" class="block mb-1 font-medium">Stock</label>
                                    <input type="number" id="editStock" class="w-full p-2 border" placeholder="Stock">
                                </div>

                                <!-- Campo: list_price -->
                                <div>
                                    <label for="editListPrice" class="block mb-1 font-medium">Precio de Lista</label>
                                    <input type="number" step="0.01" id="editListPrice" class="w-full p-2 border"
                                        placeholder="Precio de Lista">
                                </div>

                                <!-- Campo: cost_unit -->
                                <div>
                                    <label for="editCostUnit" class="block mb-1 font-medium">Costo Unitario</label>
                                    <input type="number" step="0.01" id="editCostUnit" class="w-full p-2 border"
                                        placeholder="Costo Unitario">
                                </div>

                                <!-- Campo: total_value -->
                                <div>
                                    <label for="editTotalValue" class="block mb-1 font-medium">Valor Total</label>
                                    <input type="number" step="0.01" id="editTotalValue" class="w-full p-2 border"
                                        placeholder="Valor Total">
                                </div>

                                <!-- Campo: potencial_revenue -->
                                <div>
                                    <label for="editPotencialRevenue" class="block mb-1 font-medium">Reventa
                                        Potencial</label>
                                    <input type="number" step="0.01" id="editPotencialRevenue" class="w-full p-2 border"
                                        placeholder="Reventa Potencial">
                                </div>

                                <!-- Campo: potencial_profit -->
                                <div>
                                    <label for="editPotencialProfit" class="block mb-1 font-medium">Ganancia
                                        Potencial</label>
                                    <input type="number" step="0.01" id="editPotencialProfit" class="w-full p-2 border"
                                        placeholder="Ganancia Potencial">
                                </div>

                                <!-- Campo: profit_margin -->
                                <div>
                                    <label for="editProfitMargin" class="block mb-1 font-medium">Margen de
                                        Ganancia</label>
                                    <input type="number" step="0.01" id="editProfitMargin" class="w-full p-2 border"
                                        placeholder="Margen de Ganancia">
                                </div>

                                <!-- Campo: markup -->
                                <div>
                                    <label for="editMarkup" class="block mb-1 font-medium">Markup (%)</label>
                                    <input type="number" step="0.01" id="editMarkup" class="w-full p-2 border"
                                        placeholder="Markup %">
                                </div>



                            </div>

                            <!-- Botones -->
                            <div class="flex justify-end mt-4">
                                <button type="button" onclick="saveProduct()"
                                    class="px-4 py-2 text-white bg-blue-500 rounded">Guardar</button>
                                <button type="button" onclick="closeModal()"
                                    class="px-4 py-2 ml-2 bg-gray-300 rounded">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                @push('js-livewire')
                <script>
                    Livewire.on('swal', function(data) {


                        swal({
                            type: data[0].icon,
                            title: data[0].title,
                            showConfirmButton: true,
                            timer: data[0].timer
                        })


                    });
                    // Logo del ejemplo
                    let currentProductId = null;

                    Livewire.on('edit-product', function(data) {
                        // Asignar el ID del producto actual
                        currentProductId = data[0].id;

                        // Rellenar los campos del formulario
                        document.getElementById('editName').value = data[0].name;
                        document.getElementById('editSku').value = data[0].sku;
                        document.getElementById('editBarcode').value = data[0].barcode;
                        document.getElementById('editStock').value = data[0].stock;
                        document.getElementById('editListPrice').value = data[0].list_price;
                        document.getElementById('editCostUnit').value = data[0].cost_unit;
                        document.getElementById('editTotalValue').value = data[0].total_value;
                        document.getElementById('editPotencialRevenue').value = data[0].potencial_revenue;
                        document.getElementById('editPotencialProfit').value = data[0].potencial_profit;
                        document.getElementById('editProfitMargin').value = data[0].profit_margin;
                        document.getElementById('editMarkup').value = data[0].markup;

                        // Selects - asignar las opciones seleccionadas
                        document.getElementById('editWarehouse').value = data[0].warehouse_id;
                        document.getElementById('editCategory').value = data[0].category_id;
                        document.getElementById('editVariant').value = data[0].variant_id;
                        document.getElementById('editVendor').value = data[0].vendor_id;

                        // Mostrar el modal
                        document.getElementById('editModal').classList.remove('hidden');
                    });

                    function DellSelect(){
                        Livewire.dispatch('bulkDelete', { tableName: 'product-table-24zj6z-table' });
                    }



                    function saveProduct() {
                        const name = document.getElementById('editName').value;
                        // Aquí puedes hacer una llamada AJAX o emitir un evento para guardar
                        alert('Guardando ' + name);
                        closeModal();
                    }

                    // Escucha el evento

                        Livewire.on('updateBulkCounter', ({count}) => {
                            document.getElementById('count_check').innerHTML  = count;
                        });


                    function closeModal() {
                        document.getElementById('editModal').classList.add('hidden');
                    }
                </script>
                @endpush
            </div>
        </div>
    </div>
</x-app-admin>