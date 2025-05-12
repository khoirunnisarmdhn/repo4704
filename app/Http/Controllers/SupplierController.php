<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoresupplierRequest;
use App\Http\Requests\UpdatesupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pegawai = Pegawai::all();
        return view('Supplier/view',
                        [ 
                            'Supplier'=>$Supplier,
                            'title'=>'contoh m2',
                            'nama'=>'Putri Valina'
                        ]
                    ); 
        //
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
    public function store(StoresupplierRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $Supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $Supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatesupplierRequest $request, Supplier $Supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $Supplier)
    {
        //
    }
}