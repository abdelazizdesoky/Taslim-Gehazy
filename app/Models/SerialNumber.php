<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SerialNumber extends Model
{
    use HasFactory;
    protected $fillable = [
        'serial_number',
        'invoice_id'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    } 

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

use TracksActivity;

protected static $logAttributes = ['serial_number', 'invoice_id'];
protected static $logName = 'serial_number';
protected static $logOnlyDirty = true;

}
