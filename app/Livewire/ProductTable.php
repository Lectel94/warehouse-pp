<?php

namespace App\Livewire;


use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;



final class ProductTable extends PowerGridComponent
{
    public string $tableName = 'product-table-24zj6z-table';


    use WithExport;

    public int $categoryId = 0;
    public int $warehouseId = 0;
    public int $variantId = 0;
    public int $vendorId = 0;



    public function boot(): void
    {
        config(['livewire-powergrid.filter' => 'inline']);
    }

    public function setUp(): array
    {
        $this->showCheckBox();



        return [
            PowerGrid::header()

                ->showToggleColumns()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()


                ->showRecordCount(),
                PowerGrid::exportable(fileName: 'my-export-file')
                ->striped()
               ->type( Exportable::TYPE_CSV)
               ->csvSeparator(separator: '|')
               ->queues(10)
               ->onQueue('my-products'),

        ];
    }

    public function header(): array
    {
        return [
            Button::add('bulk-delete')
            ->id('selectDell')
                ->slot('Bulk delete (<span id="count_check" onclick="DellSelect()" x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')"></span>)')
                ->class('btn btn-soft btn-sm')
                ,
        ];
    }

    public function datasource(): Builder
    {

        /* return Product::with(['category'])
            ->when(
                $this->categoryId,
                fn ($builder) => $builder->whereHas(
                    'category',
                    fn ($builder) => $builder->where('category_id', $this->categoryId)
                )
            ); */

        return Product::query()
        ->leftJoin('warehouses', 'products.warehouse_id', '=', 'warehouses.id')
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('vendors', 'products.vendor_id', '=', 'vendors.id')
        ->leftJoin('variants', 'products.variant_id', '=', 'variants.id')

        ->select('products.*',
        'warehouses.name as warehouse_name',
        'categories.name as category_name',
        'vendors.name as vendor_name',
        'variants.name as variant_name');
    }

    public function relationSearch(): array
    {
        return [
            'category' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('name')
            ->add('created_at')

            ->add('warehouse_id', fn ($p) => intval($p->warehouse_id))
            ->add('warehouse_name')

            ->add('category_id', fn ($p) => intval($p->category_id))
            ->add('category_name')

            ->add('vendor_id', fn ($p) => intval($p->vendor_id))
            ->add('vendor_name')

            ->add('variant_id', fn ($p) => intval($p->variant_id))
            ->add('variant_name')


            ->add('sku')
            ->add('barcode')
            ->add('stock')
            ->add('list_price')
            ->add('cost_unit')
            ->add('total_value')
            ->add('potencial_revenue')

            ->add('potencial_profit')
            ->add('profit_margin')
            ->add('markup')
            ->add('warehouse_id')
            ->add('category_id')


            ->add('vendor_id');



    }

    public function columns(): array
    {


        return [
            Column::make('Name', 'name')->sortable()->searchable()->editOnClick(hasPermission:true)->visibleInExport(true),

            Column::make('In Stock', 'stock')->sortable()->searchable()->editOnClick(hasPermission:true)->visibleInExport(true),
            Column::make('Variant', 'variant_name', 'variant_id')->sortable()->visibleInExport(true),
            Column::make('Warehouse', 'warehouse_name', 'warehouse_id')->sortable()->visibleInExport(true),

            Column::make('Category', 'category_name', 'category_id')->sortable()->visibleInExport(true),
            Column::make('Vendor', 'vendor_name', 'vendor_id')->sortable()->visibleInExport(true),
            Column::make('SKU', 'sku')->sortable()->searchable()->visibleInExport(true),
            Column::make('Barcode', 'barcode')->sortable()->searchable()->visibleInExport(true),
            Column::make('List Price', 'list_price')->sortable()->searchable()->editOnClick(hasPermission:true)->visibleInExport(true),
            Column::make('Cost Unit', 'cost_unit')->sortable()->searchable()->editOnClick(hasPermission:true)->visibleInExport(true),
            Column::make('Total Value', 'total_value')->sortable()->searchable()->visibleInExport(true),
            Column::make('Potencial Revenue', 'potencial_revenue')->sortable()->searchable()->visibleInExport(true),
            Column::make('Potencial Profit', 'potencial_profit')->sortable()->searchable()->visibleInExport(true),
            Column::make('Profit Margin', 'profit_margin')->sortable()->searchable()->visibleInExport(true),
            Column::make('Markup', 'markup')->sortable()->searchable()->visibleInExport(true),




            /* Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(), */

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [


            Filter::inputText('name', 'products.name')->placeholder('Product Name'),

            Filter::inputText('sku', 'sku')->placeholder('Product SKU'),

            Filter::inputText('barcode', 'barcode')->placeholder('Product Barcode'),

            Filter::select('category_name', 'category_id')
                ->dataSource(Category::all())
                ->optionLabel('name')
                ->optionValue('id'),

                Filter::select('warehouse_name', 'warehouse_id')
                ->dataSource(Warehouse::all())
                ->optionLabel('name')
                ->optionValue('id'),

                Filter::select('vendor_name', 'vendor_id')
                ->dataSource(Vendor::all())
                ->optionLabel('name')
                ->optionValue('id'),

                Filter::select('variant_name', 'variant_id')
                ->dataSource(Variant::all())
                ->optionLabel('name')
                ->optionValue('id'),

                Filter::number('list_price', 'list_price')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),

                Filter::number('total_value', 'total_value')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),

                Filter::number('cost_unit', 'cost_unit')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),

                Filter::number('potencial_revenue', 'potencial_revenue')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),

                Filter::number('potencial_profit', 'potencial_profit')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),

                Filter::number('profit_margin', 'profit_margin')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),

                Filter::number('markup', 'markup')
                ->thousands('.')
                ->decimal(',')
                ->placeholder('lowest', 'highest'),




        ];
    }

    #[On('bulkDelete')]
    public function handleBulkDelete( )
    {
        // Obtén los IDs seleccionados desde `window.pgBulkActions.get()`
        $ids = $this->checkboxValues;

        // Realiza la eliminación de los registros
        if (!empty($ids)) {
            Product::whereIn('id', $ids)->delete();
            session()->flash('message', 'Registros eliminados');
            $this->dispatch('swal', [
                'title' => trans('product.eliminados'),
                'icon' => 'success',
                'timer' => 3000,
            ]);
           $this->resetPage();


        }else{
            $this->dispatch('swal', [
                'title' => trans('product.noencontrados'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
           $this->resetPage();
        }
    }



    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $product = Product::find($rowId);
        if ($product) {
            $this->dispatch('edit-product', [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'barcode' => $product->barcode,
            'stock' => $product->stock,
            'list_price' => $product->list_price,
            'cost_unit' => $product->cost_unit,
            'total_value' => $product->total_value,
            'potencial_revenue' => $product->potencial_revenue,
            'potencial_profit' => $product->potencial_profit,
            'profit_margin' => $product->profit_margin,
            'markup' => $product->markup,
            'warehouse_id' => $product->warehouse_id,
            'category_id' => $product->category_id,
            'variant_id' => $product->variant_id,
            'vendor_id' => $product->vendor_id,
        ]);
        } else {
            $this->dispatch('product-not-found');
        }
    }


    #[\Livewire\Attributes\On('dell')]
    public function dell($rowId): void
    {
        $product = Product::find($rowId);
        if ($product) {
            $name = $product->name;
            $product->delete();
            $this->dispatch('swal', [
                'title' => trans('product.eliminado'),
                'icon' => 'success',
                'timer' => 3000,
            ]);
            $this->resetPage();
        } else {
            $this->dispatch('swal', [
                'title' => trans('product.noencontrado'),
                'icon' => 'warning',
                'timer' => 3000,
            ]);
        }
    }

    public function actions(Product $row): array
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

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {
        Product::query()->find($id)->update([
            $field=>$value
        ]);
    }



   /*  public function saveProduct(array $validatedData)
    {
        $this->validate([
        'editProduct.name' => 'required|string|max:255',                      // Nombre obligatorio, máximo 255 caracteres
        'editProduct.sku' => 'nullable|string|max:100',                        // SKU opcional, máximo 100 caracteres
        'editProduct.barcode' => 'nullable|string|max:100',                    // Barcode opcional, máximo 100 caracteres
        'editProduct.stock' => 'required|integer|min:0',                        // Stock obligatorio, entero mínimo 0
        'editProduct.list_price' => 'required|numeric|min:0',                   // Precio de lista obligatorio, numérico, mínimo 0
        'editProduct.cost_unit' => 'nullable|numeric|min:0',                   // Costo por unidad opcional, numérico, mínimo 0
        'editProduct.total_value' => 'nullable|numeric|min:0',                 // Valor total opcional, numérico, mínimo 0
        'editProduct.potencial_revenue' => 'nullable|numeric|min:0',           // Potencial ingreso opcional, numérico
        'editProduct.potencial_profit' => 'nullable|numeric|min:0',            // Potencial ganancia opcional, numérico
        'editProduct.profit_margin' => 'nullable|numeric|min:0|max:100',       // Margen de ganancia en %, entre 0 y 100
        'editProduct.markup' => 'nullable|numeric|min:0',                       // Margen de ganancia (markup), numérico, mínimo 0
        'editProduct.warehouse_id' => 'required|exists:warehouses,id',         // ID de almacén obligatorio y existente
        'editProduct.category_id' => 'required|exists:categories,id',          // ID de categoría obligatorio y existente
        'editProduct.variant_id' => 'nullable|exists:variants,id',             // ID de variante opcional y existente
        'editProduct.vendor_id' => 'nullable|exists:vendors,id',               // ID de proveedor opcional y existente
    ]);

    $product = Product::find($this->editProduct->id);
    if ($product) {
        $product->update([
            'name' => $this->editProduct->name,
            'sku' => $this->editProduct->sku,
            'barcode' => $this->editProduct->barcode,
            'stock' => $this->editProduct->stock,
            'list_price' => $this->editProduct->list_price,
            'cost_unit' => $this->editProduct->cost_unit,
            'total_value' => $this->editProduct->total_value,
            'potencial_revenue' => $this->editProduct->potencial_revenue,
            'potencial_profit' => $this->editProduct->potencial_profit,
            'profit_margin' => $this->editProduct->profit_margin,
            'markup' => $this->editProduct->markup,
            'warehouse_id' => $this->editProduct->warehouse_id,
            'category_id' => $this->editProduct->category_id,
            'variant_id' => $this->editProduct->variant_id,
            'vendor_id' => $this->editProduct->vendor_id,
        ]);

        $this->dispatch('swal', [
            'title' => 'Producto actualizado correctamente',
            'icon' => 'success',
            'timer' => 2000,
        ]);
        $this->resetPage();
        // Ocultar modal en la vista
        $this->dispatch('hide-edit-modal');
    }
} */
}
