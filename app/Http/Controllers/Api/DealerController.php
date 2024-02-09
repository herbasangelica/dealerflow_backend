<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\DealerVehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDealer()
    {
        return Dealer::all();
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
    public function createDealer(Request $request)
    {
        //
        $manufacturer = Dealer::create([
            'image'      => $request->image,
            'dealerName'      => $request->dealerName,
            'dealerAddr'  => $request->dealerAddr,
            'dealerPhone'  => $request->dealerPhone,
            'dealerEmail'  => $request->dealerEmail,
        ]);
        return $manufacturer;
    }

    // public function getDealersWithLongestAvgInventoryTime()
    // {
    //     try {
    //         $dealersWithAvgTime = DealerVehicle::select(
    //             'dealers.id',
    //             'dealers.dealerName',
    //             DB::raw('AVG(DATEDIFF(NOW(), dealer_vehicles.created_at)) as avgInventoryTime')
    //         )
    //             ->join('manufacturer_vehicles', 'dealer_vehicles.manufacturer_vehicle_id', '=', 'manufacturer_vehicles.id')
    //             ->join('dealers', 'manufacturer_vehicles.dealer_id', '=', 'dealers.id')
    //             ->groupBy('dealers.id', 'dealers.dealerName')
    //             ->orderByDesc('avgInventoryTime')
    //             ->limit(5) // Adjust the limit as needed
    //             ->get();

    //         return response()->json($dealersWithAvgTime);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }


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
