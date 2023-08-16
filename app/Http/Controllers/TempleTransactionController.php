<?php

namespace App\Http\Controllers;

use App\Models\TempleTransaction;
use Illuminate\Http\Request;
use Auth;

class TempleTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TempleTransaction  $templeTransaction
     * @return \Illuminate\Http\Response
     */
    public function templeTransctions(TempleTransaction $templeTransaction)
    {
        $temple_transacion = TempleTransaction::getTempleTransactions(Auth::user()->temple_id)->toArray();
        for ($i=0; $i < count($temple_transacion); $i++) {
             $temple_transacion[$i]['formatted_date'] = date('Y-m-d H:i:s', strtotime($temple_transacion[$i]['created_at']));
        }
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($temple_transacion),
            "totaldisplayrecords" => count($temple_transacion),
            "data" => $temple_transacion
        );

        return response()->json($dataset);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TempleTransaction  $templeTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(TempleTransaction $templeTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TempleTransaction  $templeTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TempleTransaction $templeTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TempleTransaction  $templeTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(TempleTransaction $templeTransaction)
    {
        //
    }
}
