<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

use App\Http\Controllers\Dashboard\{
    BrandController,
    ProductController,
    InvoicesController,
    LocationController,
    SupplierController,
    CustomersController,
    ProductTypeController,
    SerialNumberController,
    AdminController,
    ActivityLogController,
    BackupController,
    ReportController,
    
};
use App\Http\Controllers\User\UserInvoicesController;
use App\Http\Controllers\Employee\EmployeeInvoiceController;
use App\Http\Controllers\Viewer\ViewerInvoicesController;
use App\Http\Controllers\ImportController;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:admin'])->group(function () {
    
    /**
     * Admin Routes (Permission Level 1)
     */
    Route::middleware('permission:1')->prefix('admin')->name('admin.')->group(function () {
        // Supplier Management
        Route::resource('supplier', SupplierController::class);
        
        // Customer Management
        Route::resource('customers', CustomersController::class);
     
        // Invoice Management
        Route::resource('invoices', InvoicesController::class);
        Route::post('invoices/cancel', [InvoicesController::class, 'cancel'])->name('invoices.cancel');
        Route::post('invoices/cancelserial', [InvoicesController::class, 'cancelserial'])->name('invoices.cancelserial');
        Route::get('/invoice-chart-data', [InvoicesController::class, 'getInvoiceChartData'])->name('invoice-chart-data');
        Route::get('/invoice-chart', [InvoicesController::class, 'getInvoice']);
      
        Route::get('/download-template', [ImportController::class, 'downloadTemplate'])->name('invoices.downloadTemplate');
        Route::post('/import-invoices', [ImportController::class, 'importExcel'])->name('invoices.import');

        // Location Management
        Route::resource('locations', LocationController::class);
       
        // logs Management
        Route::get('admin/activity-logs', [ActivityLogController::class, 'index'])->name('activityLogs.index');
        Route::get('/logs/{id}', [ActivityLogController::class, 'show'])->name('logs.show');

        
        // user Management
        Route::controller(AdminController::class)->prefix('admins')->name('admins.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'delete')->name('delete');
            Route::post('/update-password', 'update_password')->name('update-password');
        });
        
        // Product Management
        Route::resource('brands', BrandController::class);
        Route::resource('product-types', ProductTypeController::class);
        Route::resource('products', ProductController::class);
        
        // Serial Number Management
        Route::get('/',[SerialNumberController::class, 'index'])->name('serial.index');
        Route::post('/invoices/search',[SerialNumberController::class, 'searchInvoices'])->name('invoices.search');

       //backup Management
        Route::post('/admin/manual-backup', [BackupController::class, 'manualBackup'])->name('manual-backup');
        Route::get('/show-backups', [BackupController::class, 'showBackups'])->name('show-backups');
        Route::get('/download-backup/{id}', [BackupController::class, 'downloadBackup'])->name('download-backup');
        Route::delete('/delete-backup/{id}', [BackupController::class, 'deleteBackup'])->name('delete-backup');

        //report 
        Route::get('report', [ReportController::class, 'index'])->name('report.index');
        Route::post('/report/generate', [ReportController::class, 'generate'])->name('report.generate');
        Route::get('/report/inventory', [ReportController::class, 'inventoryReport'])->name('report.inventory');

        Route::get('/test-session', function () {
            session(['name' => 'Aziz']);
            return 'Session set';
        });
        
        // Data Migration
        Route::get('/migrate-data', [DataController::class, 'migrateData'])->name('migrate-data');
    });
    
   
    /**
     * User Routes (Permission Level 2)
     */
    Route::middleware('permission:2')->name('user.')->group(function () {
        Route::resource('invoices', UserInvoicesController::class)->names([
            'index' => 'invoices.index',
            'create' => 'invoices.create',
            'store' => 'invoices.store',
            'show' => 'invoices.show',
            'edit' => 'invoices.edit',
            'update' => 'invoices.update',
            'destroy' => 'invoices.destroy',
        ]);
        Route::post('invoices/cancel', [UserInvoicesController::class, 'cancel'])->name('invoices.cancel');

        Route::get('/download-template', [ImportController::class, 'downloadTemplate'])->name('invoices.downloadTemplate');
        Route::post('/import-invoices', [ImportController::class, 'importExcel'])->name('invoices.import');

    });


     /**
     * Deliver Routes (Permission Level 3)
     */
    Route::middleware('permission:3,4,6')->prefix('employee')->name('employee.')->group(function () {

      Route::controller(EmployeeInvoiceController::class)->name('invoices.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('show/{id}', 'show')->name('show');
        Route::get('update/{id}', 'update')->name('update');
        Route::post('/', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');

        Route::get('completed-invoices', 'Compinvoice')->name('completed');
        Route::get('/check-serial/{invoice}/{serial}', 'checkSerial');


    });

    
     
  });


    
    /**
     * viewer Routes (Permission Level 5)
     */
    Route::middleware('permission:5')->prefix('viewer')->name('viewer.')->group(function () {

        Route::controller(ViewerInvoicesController::class)->name('invoices.')->group(function () {

            Route::get('index', 'index')->name('index');
            Route::get('show/{id}', 'show')->name('show');
            Route::get('/', 'create')->name('create');
            Route::post('/invoices/search', 'searchInvoices')->name('search');
            Route::get('prodact/view', 'viewProduct')->name('prodact');

    });

    //report 
    Route::get('report', [ReportController::class, 'index'])->name('report.index');
    Route::post('/report/generate', [ReportController::class, 'generate'])->name('report.generate');
    Route::get('/report/inventory', [ReportController::class, 'inventoryReport'])->name('report.inventory');
    Route::get('/invoice-chart-data', [InvoicesController::class, 'getInvoiceChartData'])->name('invoice-chart-data');
    Route::get('/invoice-chart', [InvoicesController::class, 'getInvoice']);
       
    });

  
  

  
});

require __DIR__.'/auth.php';