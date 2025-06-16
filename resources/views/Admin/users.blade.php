<style>
    /* Ejemplo si el icono tiene clase 'powergrid-search-icon' */
    .h-5 w-5 text-pg-primary-300 {
        display: none;
    }
</style>
<x-app-admin>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Users Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-10">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg" style="padding: 3%;">
                <h1>Hola, esta es la gestion de usuarios</h1>
                {{-- @livewire('users') --}}
                <livewire:user-table />
                <div id="editModal"
                    class="fixed inset-0 flex items-center justify-center hidden bg-gray-600 bg-opacity-50">
                    <div class="p-4 bg-white rounded">
                        <h2 class="mb-4 text-lg font-semibold">Editar usuario</h2>
                        <form {{-- aquí tu formulario --}}>
                            <input type="text" id="editName" class="w-full p-2 mb-4 border" placeholder="Nombre">
                            <button type="button" onclick="saveUser()"
                                class="px-4 py-2 text-white bg-blue-500 rounded">Guardar</button>
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 ml-2 bg-gray-300 rounded">Cancelar</button>
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
                    let currentUserId = null;

                    window.addEventListener('edit-user', event => {
                        currentUserId = event.detail.id;
                        document.getElementById('editName').value = event.detail.name;
                        document.getElementById('editModal').classList.remove('hidden');
                    });

                    function saveUser() {
                        const name = document.getElementById('editName').value;
                        // Aquí puedes hacer una llamada AJAX o emitir un evento para guardar
                        alert('Guardando ' + name);
                        closeModal();
                    }

                    function closeModal() {
                        document.getElementById('editModal').classList.add('hidden');
                    }
                </script>
                @endpush
            </div>
        </div>
    </div>
</x-app-admin>