<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use App\Models\Vendor;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/Admin', function () {
        return view('Admin.index-admin');
    })->name('admin');

    Route::get('/Admin/Users', function () {
        $users=User::all();
       /*  User::factory()->count(100)->create(); */
        return view('Admin.users', compact('users'));
    })->name('admin-users');

    Route::get('/Admin/Warehouses', function () {
        return view('Admin.warehouse-admin');
    })->name('admin-warehouse');

    Route::get('/Admin/Categorys', function () {
        return view('Admin.category-admin');
    })->name('admin-category');

    Route::get('/Admin/Products', function () {
        return view('Admin.product-admin');
    })->name('admin-product');

    Route::get('/Admin/Orders', function () {
        return view('Admin.order-admin');
    })->name('admin-order');

    Route::get('/Admin/Invoices', function () {
        return view('Admin.invoice-admin');
    })->name('admin-invoice');

    Route::get('/Admin/Vendors', function () {
        return view('Admin.vendor-admin');
    })->name('admin-vendor');

    Route::get('/Admin/Variant', function () {
        return view('Admin.variant-admin');
    })->name('admin-variant');

    Route::post('/import', function (Request $request) {

        // Validar que se envió un archivo
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt'
    ]);

    // Obtener archivo
    $file = $request->file('csv_file');

    // Leer el archivo
    if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
        // Opcional: leer encabezados
        /* $header = fgetcsv($handle, 2590, ','); */
        $w=null;
        $c=null;
        $va=null;
        $ve=null;

        while (($data = fgetcsv($handle, 2590, ',')) !== false) {

                if($data[1]!='' && $data[1]!=null){
                    $aux=Variant::where('name',$data[1])->first();
                    if ($aux) {
                        $va=$aux->id;
                    } else {
                        $va=null;
                    }
                }

                if($data[2]!='' && $data[2]!=null){
                    $aux2=Warehouse::where('name',$data[2])->first();
                    if ($aux2) {
                        $w=$aux2->id;
                    } else {
                        $w=null;
                    }
                }

                if($data[3]!='' && $data[3]!=null){
                    $aux3=Category::where('name',$data[3])->first();
                    if ($aux3) {
                        $c=$aux3->id;
                    } else {
                        $c=null;
                    }
                }

                if($data[4]!='' && $data[4]!=null){
                    $aux4=Vendor::where('name',$data[4])->first();
                    if ($aux4) {
                        $ve=$aux4->id;
                    } else {
                        $ve=null;
                    }
                }

                Product::create([
                    'name' => $data[0],
                    'sku'=> $data[5],
                    'barcode'=> $data[6],

                    'stock'=> ($data[7]==''  || $data[7]=='In Stock') ? null : intval(floatval(str_replace(',', '.', $data[7]))),
                    'list_price'=> $data[8]=='' ? null : $data[8],
                    'cost_unit'=> $data[9]=='' ? null : ((strpos($data[9], '-') !== false) ? str_replace(',', '',substr($data[9], strpos($data[9], '-') + 1)) : str_replace(',', '', $data[9])),
                    'total_value'=> $data[10]=='' ? null : ((strpos($data[10], '-') !== false) ? str_replace(',', '',substr($data[10], strpos($data[10], '-') + 1)) : str_replace(',', '', $data[10])),
                    'potencial_revenue'=> $data[11]=='' ? null : str_replace(',', '', $data[11]),
                    'potencial_profit'=> ($data[12]=='' || $data[12]=='#VALUE!') ? null : str_replace(',', '', $data[12]),
                    'profit_margin'=> $data[13]=='' ? null : str_replace(',', '',rtrim($data[13], '%')),
                    'markup'=> $data[14]=='' ? null : str_replace(',', '',rtrim($data[14], '%')),
                    'warehouse_id'=> $w,
                    'category_id'=> $c,
                    'variant_id'=> $va,
                    'vendor_id'=> $ve,

                    // otros campos...
                ]);
            }
            // Aquí puedes mapear los datos a tus campos de base
            // Ejemplo para un modelo User, adaptalo a tu estructura


        fclose($handle);
    }

    return back()->with('success', 'Datos importados correctamente.');
    })->name('import');

    /* 'list_price'=> $data[8]=='' ? null : str_replace(',', '', $data[8]),
                    'cost_unit'=> $data[9]=='' ? null : str_replace(',', '', $data[9]),
                    'total_value'=> $data[10]=='' ? null : str_replace(',', '', $data[10]),
                    'potencial_revenue'=> $data[11]=='' ? null : str_replace(',', '', $data[11]),
                    'potencial_profit'=> $data[12]=='' ? null : str_replace(',', '', $data[12]), */

});
