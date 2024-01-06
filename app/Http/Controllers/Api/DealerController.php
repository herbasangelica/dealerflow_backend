<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
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
