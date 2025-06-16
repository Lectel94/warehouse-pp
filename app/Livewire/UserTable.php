<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'user-table-7u1ekp-table';

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
        return User::query();
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
            ->add('avatar',  fn ($item) => $item->profile_photo_path ? '<img class="w-8 h-8 rounded-full shrink-0 grow-0" src="' . asset("storage/{$item->profile_photo_path}") . '">': '')
            ->add('email')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            /* Column::make('Id', 'id'), */
            Column::make('Name', 'name')
                ->sortable()
                ->searchable()
                ->editOnClick(hasPermission:true),

                Column::make('Avatar', 'avatar'),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            /* Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(), */

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
        $user = User::find($rowId);
        if ($user) {
            $this->dispatch('edit-user', ['id' => $user->id, 'name' => $user->name]);
        } else {
            $this->dispatch('user-not-found');
        }
    }


    #[\Livewire\Attributes\On('dell')]
    public function dell($rowId): void
    {
        $user = User::find($rowId);
        if ($user) {
            $name = $user->name;
            $user->delete();
            $this->dispatch('swal', [
                'title' => trans('users.eliminado'),
                'icon' => 'success',
                'timer' => 3000,
            ]);
            $this->resetPage();
        } else {
            $this->dispatch('swal', [
                'title' => trans('users.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }

    public function actions(User $row): array
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
    User::query()->find($id)->update([
        $field=>$value
    ]);
}


}
