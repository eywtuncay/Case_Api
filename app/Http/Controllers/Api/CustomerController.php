<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::get();
        if($customers->count() > 0)
        {
            return CustomerResource::collection($customers);
        }
        else
        {
            return response()->json(['message' => 'Kayıt Yok.'],200);
        }
    }


    public function store(Request $request)
	{
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'since' => 'required|date',
            'revenue' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tüm alanlar zorunludur.',
                'error' => $validator->errors(),
            ], 422);
        }

        $customer = Customer::create([
            'name' => $request->name,
            'since' => $request->since,
            'revenue' => $request->revenue,
        ]);

        return response()->json([
            'message' => 'Ürün Başarıyla Eklendi',
            'data' => new CustomerResource($customer)
        ], 201);
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }


    public function update(Request $request, Customer $customer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'since' => 'required|date',
            'revenue' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Tüm alanlar zorunludur.',
                'error' => $validator->errors(),
            ], 422);
        }
    
        $customer->update([
            'name' => $request->name,
            'since' => $request->since,
            'revenue' => $request->revenue,
        ]);
    
        return response()->json([
            'message' => 'Ürün Başarıyla Güncellendi',
            'data' => new CustomerResource($customer)
        ], 201);
    }



    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([
            'message' => 'Ürün Başarıyla Silindi',
        ], 201);
    }


}
