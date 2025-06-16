<?php

namespace App\Livewire;

use App\Models\Warehouse;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class WarehouseTable extends PowerGridComponent
{
    public string $tableName = 'warehouse-table-ihj2u1-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Warehouse::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Name', 'name')
            ->editOnClick(hasPermission:true)
            ->sortable()
            ->searchable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'name')->placeholder('Warehouse Name'),

            /* Filter::boolean('in_stock', 'in_stock')
                ->label('In Stock', 'Out of Stock'),

            Filter::number('price_BRL', 'price')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'), */

        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $w = Warehouse::find($rowId);
        if ($w) {
            $this->dispatch('edit-warehouse', ['id' => $w->id, 'name' => $w->name]);
        } else {
            $this->dispatch('warehouse-not-found');
        }
    }


    #[\Livewire\Attributes\On('dell')]
    public function dell($rowId): void
    {
        $w = Warehouse::find($rowId);
        if ($w) {
            $name = $w->name;
            $w->delete();
            $this->dispatch('swal', [
                'title' => trans('warehouse.eliminado'),
                'icon' => 'success',
                'timer' => 3000,
            ]);
            $this->resetPage();
        } else {
            $this->dispatch('swal', [
                'title' => trans('warehouse.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }

    public function actions(Warehouse $row): array
    {
        $dell=asset('img/icodel.png');
        $edit=asset('img/ico25.png');
            return [
                Button::add('edit')
                ->slot('<img src="'.$edit.'">')
                    ->id()
                    ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                    ->dispatch('edit', ['rowId' => $row->id]),

                    Button::add('dell')
                    ->slot('<img src="'.$dell.'">')
                    ->id()
                    ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                    ->dispatch('dell', ['rowId' => $row->id])
            ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        Warehouse::query()->find($id)->update([
            $field=>$value
        ]);
    }



    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
