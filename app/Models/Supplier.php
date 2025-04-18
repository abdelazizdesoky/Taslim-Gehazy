<?php

namespace App\Models;

use App\Traits\TracksActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $guarded=[];

    use TracksActivity;

    protected static $logAttributes = ['code', 'name', 'address','phone','status'];
    protected static $logName = 'suppliers';
    protected static $logOnlyDirty = true;
    
}
