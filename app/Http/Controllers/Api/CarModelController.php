<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getCarModel(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);
        $brandFilter = $request->input('brand');

        try {
            $query = CarModel::with('brand.manufacturer');

            if ($brandFilter) {
                $query->whereHas('brand', function ($q) use ($brandFilter) {
                    $q->where('brandName', $brandFilter);
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
                'page' => $paginator->currentPage(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function getCarModelByBrand(Request $request, $brandId)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);

        try {
            $query = CarModel::with('brand.manufacturer');

            if ($brandId) {
                $query->whereHas('brand', function ($q) use ($brandId) {
                    $q->where('id', $brandId); // Filter by brand ID
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
    public function createCarModel(Request $request)
    {
        //
        $validatedData = $request->validate([
            'modelName' => 'required',
            'image' => 'required',
            'style' => 'required',
            'brand_id' => 'required|exists:brands,id'
        ]);

        $model = CarModel::create($validatedData);
        return response()->json($model, 201);
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
