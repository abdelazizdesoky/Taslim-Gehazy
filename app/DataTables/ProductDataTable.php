<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ProductDataTable extends DataTable
{
 
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('actions', function ($item) {
        if (Auth::guard('admin')->user()->permission == 1){
            return view('Dashboard.Admin.Product._actions', ['product' => $item]);
        }
            })
           
            ->setRowId('id');
    }

 
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
      
    }

  
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                     ->dom('Bfrtip') 
                   // ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                      
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
            ->title('#')
            ->searchable(false)
            ->orderable(false),
            Column::make('product_name')
            ->title('اسم المنتج'),
            Column::make('product_code')
            ->title('كود المنتج'),
            // Column::make('detail_name')
            // ->title('تفاصيل المنتج'),
            Column::make('vendor_code')
            ->title('كود المورد'),
            Column::computed('actions')
                  ->exportable(false)
                  ->printable(false)
                  ->addClass('text-center'),
        ];
    }

 
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
