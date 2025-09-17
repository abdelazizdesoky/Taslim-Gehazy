<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::with('productTypes')->get();
        return view('Dashboard.Admin.Product.brands.index', compact('brands'));
        
    }

    public function create()
    {
        return view('Dashboard.Admin.Product.brands.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required'
        ]);

         Brand::create([
        'brand_name' => $request->brand_name
    ]);

        session()->flash('add');
        return redirect()->back();
    }



}
