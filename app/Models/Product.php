<?php

namespace App\Models;

use App\Models\ProductType;
use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;

class Product extends Model
{
    use HasFactory;

       protected $fillable = [
       
        'product_name',
        'detail_name',
        'product_code',
        'vendor_code',
        'type_id',
       
       
    ];


    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    public function brand()
    {
        return $this->hasOneThrough(Brand::class, ProductType::class, 'id', 'id', 'type_id', 'brand_id');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class);
    }

    // العلاقة مع جدول invoice_products
    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);
    }

    public static function latestProduct(int $count = 5): Collection
    {
        return self::query()
            ->select(
                'id',
                'product_name',
                'product_code'
              
            )
            ->latest()
            ->take($count)
            ->get();
    }

    use TracksActivity;

    protected static $logAttributes = ['product_name', 'product_code', 'detail_name'];
    protected static $logName = 'Product';
    protected static $logOnlyDirty = true;
}
