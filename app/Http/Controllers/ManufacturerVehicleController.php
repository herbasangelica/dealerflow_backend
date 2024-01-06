<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ManufacturerVehicle;
use Illuminate\Http\Request;

class ManufacturerVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getManufacturerVehicle()
    {
        $manufacturerVehicles = ManufacturerVehicle::with('manufacturer', 'carModel.brand', 'dealer')->get();
        return response()->json($manufacturerVehicles);
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
    public function createManufacturerVehicle(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'car_model_id' => 'required|exists:car_models,id',
            'dealer_id' => 'required|exists:dealers,id',
            'vin' => 'required',
            'price' => 'required|'
        ]);

        $manufacturerVehicle = ManufacturerVehicle::create($validatedData);
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
