<?php

namespace App\Livewire;

use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class VendorTable extends PowerGridComponent
{
    public string $tableName = 'vendor-table-cl1r8n-table';

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
        return Vendor::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
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
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $v = Vendor::find($rowId);
        if ($v) {
            $this->dispatch('edit-vendor', ['id' => $v->id, 'name' => $v->name]);
        } else {
            $this->dispatch('vendor-not-found');
        }
    }


    #[\Livewire\Attributes\On('dell')]
    public function dell($rowId): void
    {
        $v = Vendor::find($rowId);
        if ($v) {
            $name = $v->name;
            $v->delete();
            $this->dispatch('swal', [
                'title' => trans('vendor.eliminado'),
                'icon' => 'success',
                'timer' => 1000,
            ]);
            $this->resetPage();
        } else {
            $this->dispatch('swal', [
                'title' => trans('vendor.noencontrado'),
                'icon' => 'warning',
                'timer' => 1000,
            ]);
        }
    }

    public function actions(Vendor $row): array
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
        Vendor::query()->find($id)->update([
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
