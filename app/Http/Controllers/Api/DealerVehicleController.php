<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DealerVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DealerVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDealerVehicle()
    {
        $dealerVehicle = DealerVehicle::with(
            'manufacturerVehicle',
            'manufacturerVehicle.manufacturer',
            "manufacturerVehicle.carModel",
            'manufacturerVehicle.carModel.brand',
            'manufacturerVehicle.dealer',
        )->get();

        return response()->json($dealerVehicle);
    }


    // get dealer vehicle by id

    public function getDealerVehicleByDealer(Request $request, $dealerId)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        try {
            $query = DealerVehicle::with(
                'manufacturerVehicle',
                'manufacturerVehicle.manufacturer',
                "manufacturerVehicle.carModel",
                'manufacturerVehicle.carModel.brand',
                'manufacturerVehicle.dealer',
            );

            if ($dealerId) {
                $query->whereHas('manufacturerVehicle.dealer', function ($q) use ($dealerId) {
                    $q->where('id', $dealerId); // Filter by brand ID
                });
            }

            $paginator = $query->paginate($limit, ['*'], 'page', $page);

            $models = $paginator->items();
            $currentPage = $paginator->currentPage();
            $totalPages = $paginator->lastPage();

            return response()->json([
                'models' => $models,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    //get dealer vehicle by car model id

    public function getDealerVehicleByCarModel(Request $request, $modelId)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        try {
            $query = DealerVehicle::with(
                'manufacturerVehicle',
                'manufacturerVehicle.manufacturer',
                "manufacturerVehicle.carModel",
                'manufacturerVehicle.carModel.brand',
                'manufacturerVehicle.dealer',
            );

            if ($modelId) {
                $query->whereHas('manufacturerVehicle.carModel', function ($q) use ($modelId) {
                    $q->where('id', $modelId)->where('status', '=', 'ON SALE');
                });
            }

            $paginator = $query->paginate($limit, ['*'], 'page', $page);

            $models = $paginator->items();
            $currentPage = $paginator->currentPage();
            $totalPages = $paginator->lastPage();

            return response()->json([
                'models' => $models,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function getDealerVehicleVINSearch(Request $request)
    {
        $queryParam = $request->input('query');

        try {
            $query = DealerVehicle::with(
                'manufacturerVehicle',
                'manufacturerVehicle.manufacturer',
                'manufacturerVehicle.carModel',
                'manufacturerVehicle.carModel.brand',
                'manufacturerVehicle.dealer'
            );

            if ($queryParam) {
                $query->whereHas('manufacturerVehicle', function ($q) use ($queryParam) {
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


    //search max to min price
    public function getDealerVehiclePriceSearch(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');

        try {
            $query = DealerVehicle::with(
                'manufacturerVehicle',
                'manufacturerVehicle.manufacturer',
                'manufacturerVehicle.carModel',
                'manufacturerVehicle.carModel.brand',
                'manufacturerVehicle.dealer'
            );

            if ($minPrice !== null && $maxPrice !== null) {
                // Cast to integers before using whereBetween
                $query->whereBetween('price', [(int)$minPrice, (int)$maxPrice]);
            } elseif ($minPrice !== null) {
                $query->where('price', '>=', (int)$minPrice);
            } elseif ($maxPrice !== null) {
                $query->where('price', '<=', (int)$maxPrice);
            }

            // Additional conditions, if any
            $query->where('status', '=', 'On sale');

            // Order the results by price in descending order
            $query->orderBy('price', 'asc');

            $paginator = $query->paginate($limit);

            $models = $paginator->items();
            $currentPage = $paginator->currentPage();
            $totalPages = $paginator->lastPage();

            return response()->json([
                'dealerVehicles' => $models,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
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
    public function createDealerVehicle(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'manufacturer_vehicle_id' => 'required|exists:manufacturer_vehicles,id',
            'status' => 'required',
            'price' => 'required'
        ]);

        $dealerVehicle = DealerVehicle::create($validatedData);
        return response()->json($dealerVehicle, 201);
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
