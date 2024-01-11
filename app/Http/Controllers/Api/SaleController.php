<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getSale()
    {
        $sale = Sale::with(
            'dealerVehicle',
            'dealerVehicle.manufacturerVehicle',
            'dealerVehicle.manufacturerVehicle.dealer',
            'dealerVehicle.manufacturerVehicle.carModel',
            'dealerVehicle.manufacturerVehicle.carModel.brand',
            'customer',

        )->get();
        return response()->json($sale);
    }


    // get sale by dealer id

    public function getSaleByDealer($dealerId)
    {
        try {
            $sale = Sale::with(
                'dealerVehicle',
                'dealerVehicle.manufacturerVehicle',
                'dealerVehicle.manufacturerVehicle.dealer',
                'dealerVehicle.manufacturerVehicle.carModel',
                'dealerVehicle.manufacturerVehicle.carModel.brand',
                'customer',

            )->whereHas('dealerVehicle.manufacturerVehicle.dealer', function ($query) use ($dealerId) {
                $query->where('id', $dealerId);
            })
                ->get();

            return response()->json($sale);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function searchSaleByVin(Request $request)
    {
        $queryParam = $request->input('query');

        try {
            $query = Sale::with(
                'dealerVehicle',
                'dealerVehicle.manufacturerVehicle',
                'customer',
            );

            if ($queryParam) {
                $query->whereHas('dealerVehicle.manufacturerVehicle', function ($q) use ($queryParam) {
                    $q->where('vin', 'LIKE', '%' . $queryParam . '%');
                });
            }

            // Execute the query and get the results
            $results = $query->get();

            return response()->json([
                $results[0] // Return the fetched data
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createSale(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'dealer_vehicle_id' => 'required|exists:dealer_vehicles,id',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $manufacturerVehicle = Sale::create($validatedData);
        return response()->json($manufacturerVehicle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
