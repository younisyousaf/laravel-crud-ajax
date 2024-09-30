<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Pest\ArchPresets\Custom;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::all();
        if ($request->type == 'options') {
            return response()->json(['customers' => $customers], 200);
        }
        return view('customers');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Add a new record
        $request->validate([
            'customername' => 'required|max:255',
            'customeremail' => 'required|email|max:255',
            'customeraddress' => 'required|max:255',
            'customerimage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $customer = new Customer();
        $customer->customername = $request->customername;
        $customer->customeremail = $request->customeremail;
        $customer->customeraddress = $request->customeraddress;

        if ($request->hasFile('customerimage')) {
            $file = time() . '.' . $request->customerimage->extension();
            $request->customerimage->move(public_path('images'), $file);
            $customer->customerimage = $file;
        }
        $customer->save();
        if ($customer) {
            return response()->json(['message' => 'Customer added successfully']);
        } else {
            return response()->json(['error' => 'Customer not added'], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Show all the records

        $customer = Customer::find($id);
        if ($customer) {
            return response()->json(['customer' => $customer], 200);
        } else {
            return response()->json(['message' => 'Customer Not Found!'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Update the Single Record
        // dd($id);
        // dd($request->all());
        $request->validate([
            'customername' => 'required|max:255',
            'customeremail' => 'required|email|max:255',
            'customeraddress' => 'required|max:255',
        ]);

        $customer = Customer::find($id);
        if ($customer) {
            $customer->customername = $request->input('customername');
            $customer->customeremail = $request->input('customeremail');
            $customer->customeraddress = $request->input('customeraddress');
            if ($request->hasFile('customerimage')) {
                unlink(public_path('images/' . $customer->customerimage));
                $file = time() . '.' . $request->customerimage->extension();
                $request->customerimage->move(public_path('images'), $file);
                $customer->customerimage = $file;
            }
            $customer->save();

            return response()->json(['message' => 'Customer updated successfully']);
        } else {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Delete Single Record
        $customer = Customer::find($id);
        if ($customer) {
            // Delete the image if it exists
            if ($customer->customerimage && file_exists(public_path('images/' . $customer->customerimage))) {
                unlink(public_path('images/' . $customer->customerimage));
            }
            $customer->delete();
            return response()->json(['message' => 'Customer deleted successfully']);
        } else {
            return response()->json(['error' => 'Customer not found'], 404);
        }
    }
}
