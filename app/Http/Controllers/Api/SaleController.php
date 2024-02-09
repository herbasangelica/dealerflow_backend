<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\DealerVehicle;
use App\Models\ManufacturerVehicle;
use App\Models\CarModel;
use App\Models\Customer;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //get the overall sale to display
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

    //salesforthepast3years
    public function getSalesForThePastThreeYears(Request $request)
    {
        // Calculate the start and end date for the past three years
        $endDate = now();
        $startDate = now()->subYears(3);

        $queryParam = $request->input('query');

        $sales = Sale::with(
            'dealerVehicle',
            'dealerVehicle.manufacturerVehicle',
            'dealerVehicle.manufacturerVehicle.dealer',
            'dealerVehicle.manufacturerVehicle.carModel',
            'dealerVehicle.manufacturerVehicle.carModel.brand',
            'customer'
        )
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($queryParam) {
            $sales->whereHas('customer', function ($query) use ($queryParam) {
                $query->where('customerGender', $queryParam);
            });
        }

        $sales = $sales->get();

        return response()->json($sales);
    }



    //create a sale for vehicle
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


    // get the sale by dealer id

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


    //search the sales using the VIN 
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


    //Find the top 2 brands by dollar-amount sold in the past year
    public function getTopBrandsByDollarAmount()
    {
        $yearAgo = Carbon::now()->subYear();

        $topBrandsByDollar = DB::table('sales')
            ->join('dealer_vehicles', 'sales.dealer_vehicle_id', '=', 'dealer_vehicles.id')
            ->join('manufacturer_vehicles', 'dealer_vehicles.manufacturer_vehicle_id', '=', 'manufacturer_vehicles.id')
            ->join('car_models', 'manufacturer_vehicles.car_model_id', '=', 'car_models.id')
            ->join('brands', 'car_models.brand_id', '=', 'brands.id')
            ->select('brands.brandName', DB::raw('SUM(manufacturer_vehicles.price) as totalAmount'))
            ->where('sales.created_at', '>', $yearAgo)
            ->groupBy('brands.id')
            ->orderByDesc('totalAmount')
            ->limit(2)
            ->get();

        return response()->json($topBrandsByDollar);
    }

    /**
     * Get the top 2 brands by unit sales in the past year.
     */
    public function getTopBrandsByUnitSales()
    {
        $yearAgo = Carbon::now()->subYear();

        $topBrandsByUnitSales = DB::table('sales')
            ->join('dealer_vehicles', 'sales.dealer_vehicle_id', '=', 'dealer_vehicles.id')
            ->join('manufacturer_vehicles', 'dealer_vehicles.manufacturer_vehicle_id', '=', 'manufacturer_vehicles.id')
            ->join('car_models', 'manufacturer_vehicles.car_model_id', '=', 'car_models.id')
            ->join('brands', 'car_models.brand_id', '=', 'brands.id')
            ->select('brands.brandName', DB::raw('COUNT(sales.id) as totalUnitSales'))
            ->where('sales.created_at', '>', $yearAgo)
            ->groupBy('brands.id')
            ->orderByDesc('totalUnitSales')
            ->limit(2)
            ->get();

        return response()->json($topBrandsByUnitSales);
    }


    //Show sales trends for various brands over the past 3 years, by year, month, week. Then break 
    //these data out by gender of the buyer and then by income range.

    // public function salesTrends()
    // {
    //     // Get sales data with necessary relationships
    //     $salesData = Sale::with(
    //         'dealerVehicle',
    //         'dealerVehicle.manufacturerVehicle',
    //         'dealerVehicle.manufacturerVehicle.dealer',
    //         'dealerVehicle.manufacturerVehicle.carModel',
    //         'dealerVehicle.manufacturerVehicle.carModel.brand',
    //         'customer',
    //     )->get();

    //     // Initialize arrays to store sales trends
    //     $salesTrends = [];

    //     // Process each sale
    //     foreach ($salesData as $sale) {
    //         // Extract relevant information
    //         $saleDate = Carbon::parse($sale->created_at);
    //         $year = $saleDate->year;
    //         $month = $saleDate->month;
    //         $week = $saleDate->weekOfYear;

    //         // Extract buyer information
    //         $gender = $sale->customer->gender;
    //         $incomeRange = $sale->customer->incomeRange;

    //         // Build the sales trends array
    //         $salesTrends[$year][$month][$week][$gender][$incomeRange][] = $sale;
    //     }

    //     // Check if the key exists before accessing it
    //     $selectedYear = 2021; // Change to the desired year

    //     if (isset($salesTrends[$selectedYear])) {
    //         // Example: Displaying sales trends by month for a specific year
    //         foreach ($salesTrends[$selectedYear] as $month => $monthData) {
    //             echo "Month: $month\n";
    //             // Process $monthData as needed
    //         }
    //     } else {
    //         echo "No sales data available for the year $selectedYear";
    //     }

    //     // ... Continue similar iterations for week, gender, and income range
    // }


    //In what month(s) do convertibles sell best? 

    public function bestSellingMonthsForConvertibles()
    {
        // Assuming 'style' is a column in the CarModel table indicating the car style (e.g., convertible)
        $bestSellingMonths = Sale::join('dealer_vehicles', 'sales.dealer_vehicle_id', '=', 'dealer_vehicles.id')
            ->join('manufacturer_vehicles', 'dealer_vehicles.manufacturer_vehicle_id', '=', 'manufacturer_vehicles.id')
            ->join('car_models', 'manufacturer_vehicles.car_model_id', '=', 'car_models.id')
            ->join('manufacturers', 'manufacturer_vehicles.manufacturer_id', '=', 'manufacturers.id')
            ->where('car_models.style', '=', 'convertible')
            ->selectRaw('DATE_FORMAT(sales.created_at, "%M %Y") as month_year, COUNT(sales.id) as sales_count, manufacturers.manufacturerName as manufacturer')
            ->groupByRaw('DATE_FORMAT(sales.created_at, "%M %Y"), manufacturers.manufacturerName')
            ->orderByDesc('sales_count')
            ->take(1) // You can adjust this to get the top N months
            ->get();

        return $bestSellingMonths;
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
