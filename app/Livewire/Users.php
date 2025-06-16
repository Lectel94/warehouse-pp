<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Export;
use Illuminate\Database\Eloquent\Builder;
class Users extends PowerGridComponent
{

    /* public $users;

    public function mount()
    {
        // Cargar los datos cuando inicia el componente
        $this->users = User::all()->toArray();
    }



    public function eliminar($id)
    {
        User::find($id)->delete();

        $this->users = User::all()->toArray();

        $this->emit('loadDataTable');
        // O actualizar la lista
        session()->flash('message', 'Usuario eliminado');
    }
    public function render()
    {
        return view('livewire.users');
    } */

     // Nombre único para la tabla
    public string $tableName = 'users_table';

    // Fuente de datos
    public function datasource(): Builder
    {
        return User::query();
    }

    // Configuración de las columnas (todas las funciones activadas)
    public function addColumns(): array
    {
        return [
            Column::make('ID', 'id')->sortable()->searchable()->filtred(),
            Column::make('Name', 'name')->sortable()->searchable()->filtred(),
            Column::make('Email', 'email')->sortable()->searchable()->filtred(),
            Column::make('Created At', 'created_at')->sortable(),
        ];
    }

    public function columns(): array
    {
        return $this->addColumns();
    }

    // Configuración avanzada para activar funciones
    public function headers(): array
    {
        return [
            Header::make()
                ->showSearchInput()
                ->showToggleColumns(),
        ];
    }

    public function footer(): array
    {
        return [
            Footer::make()
                ->showPerPage()
                ->showRecordCount()
                ->showPagination()
        ];
    }

    // Opciones de exportación (CSV, Excel, PDF)
    public function header(): array
    {
        return [
            Header::make()->showSearchInput(),
            Export::make('export')
                ->striped()
                ->type('csv', 'CSV')
                ->type('xlsx', 'Excel')
                ->type('pdf', 'PDF'),
        ];
    }

    // Acciones por fila (por ejemplo: editar, eliminar)
    public function actions(): array
    {
        return [
            Button::make('edit', 'Editar')
                ->class('bg-blue-500 text-white p-2 rounded')
                ->route('users.edit', ['user' => 'id']),
            Button::make('delete', 'Eliminar')
                ->class('bg-red-500 text-white p-2 rounded')
                ->emit('delete', ['user' => 'id']),
        ];
    }
}
