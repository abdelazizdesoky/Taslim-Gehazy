<?php

namespace App\Models;

use App\Models\ProductType;
use App\Traits\TracksActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

       protected $fillable = [
        'brand_name',
      
    ];

 
    public function productTypes()
    {
        return $this->hasMany(ProductType::class, 'brand_id');
    }
    
    use TracksActivity;

    protected static $logAttributes = ['type_name','brand_id'];
    protected static $logName = 'Brand';
    protected static $logOnlyDirty = true;
}
