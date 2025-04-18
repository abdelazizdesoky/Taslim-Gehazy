<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\CustomerDataTable;
use App\Models\Customers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CustomersController extends Controller
{
  
    public function index(CustomerDataTable $datatable)
    {

        if (request()->ajax()) {
            return $datatable->ajax();
        }
        return $datatable->render('Dashboard.Admin.customers.index');
    }


    public function create()
    {
        return view('Dashboard.Admin.customers.create');
    }

    public function store(request $request)
    {

    try {

       $customers = new Customers();
       $customers->code = $request->code;
       $customers->name = $request->name;
       $customers->address = $request->address;
       $customers->phone = $request->phone;
       $customers->status = $request->status;
     
       $customers->save();


      session()->flash('add');
      return redirect()->route('admin.customers.index');

        }

        catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $customer = Customers::findorfail($id);
        return view('Dashboard.Admin.customers.edit',compact('customer'));
    }

    public function update(request $request)
    {
        

        $customers = Customers::findOrFail($request->id);

        $customers->update($request->all());

        $customers->save();

        session()->flash('edit');
        return redirect()->route('admin.customers.index');
    }



    public function destroy(request $request)
    {
        Customers::destroy($request->id);
        session()->flash('delete');
        return redirect()->back();
    }


}
