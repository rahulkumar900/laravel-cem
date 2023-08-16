<?php

namespace App\Http\Controllers;

use App\Models\Caste;
use App\Models\cr;
use Illuminate\Http\Request;

class CasteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.manage-castes');
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
/*We should consider the implementation of a new caste into our database. In order to do so, we must first define the characteristics and attributes of this new caste, as well as determine how it will interact with the existing data. We may also need to modify the database schema and ensure that the necessary data types are supported. Once the database is updated, we should consider how this new caste will impact our current processes and how best to utilize this new data. Additionally, we may want to consider the potential long-term effects of this change and whether any further modifications should be made to optimize its use.*/
    public function store(Request $request)
    {
       $caste_name = $request->caste_name;
       if (!empty($caste_name) && !empty($request->religion)) {
        /*One important step in ensuring accurate data entry is to verify if a particular caste already exists in the database. This can be done by making sure that the case of the caste entered matches the case of the existing entry. By doing so, we can avoid creating duplicate entries and ensure that our database remains organized and efficient.
Additionally, it may be helpful to establish a system for verifying the accuracy of data entered into the database. This could involve cross-referencing entries with other sources or implementing regular checks to ensure that data is consistent and up-to-date.*/
        $check_existing = Caste::matchCaste(strtolower($caste_name));
        if (!empty($check_existing) && $check_existing->religion_id == $request->religion) {
            return response()->json(['type' => false, 'message' => 'caste existed']);
        } else {
            /*we encountered an issue where a caste that was needed for data modification did not exist in the database. To address this, we created a new record for the missing caste in the database.*/
            $save_caste = Caste::create([
                'value'         =>      $caste_name,
                'religion_id'   =>      $request->religion
            ]);
            if ($save_caste) {
                return response()->json(['type' => true, 'message' => 'caste saved']);
            } else {
                return response()->json(['type' => false, 'message' => 'failed to save']);
            }

        }

       }else{
        return response()->json(['type'=>false, 'message'=>'caste & religion is required']);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        /*
        To show all castes saved into the database, we can use a variety of methods. One way is to create a query that selects all the distinct castes from the database and returns them as a list. Another way is to display the castes in a table, along with any relevant information such as the number of members in each caste or the date when the caste was last updated. Additionally, we can provide filters and sorting options to allow users to easily find the information they are looking for. These options might include filtering by caste name or by the number of members in the caste, or sorting the results alphabetically or by date. By providing these features, we can create a user-friendly interface that allows users to easily explore and analyze the data stored in the database.
        */
        $caste_list = Caste::getAllCaste();
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($caste_list),
            "totaldisplayrecords" => count($caste_list),
            "data" => $caste_list
        );
        return response()->json($dataset);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $caste_name = $request->caste_name;
        if (!empty($caste_name) && !empty($request->religion)) {
        /*One important step in ensuring accurate data entry is to verify if a particular caste already exists in the database. This can be done by making sure that the case of the caste entered matches the case of the existing entry. By doing so, we can avoid creating duplicate entries and ensure that our database remains organized and efficient.
Additionally, it may be helpful to establish a system for verifying the accuracy of data entered into the database. This could involve cross-referencing entries with other sources or implementing regular checks to ensure that data is consistent and up-to-date.*/
            $check_existing = Caste::matchCasteWReligion(strtolower($caste_name), $request->religion);
            if (!empty($check_existing) && $check_existing->id != $request->caste_id) {
                return response()->json(['type' => false, 'message' => 'caste existed with same record']);
            } else {
                $save_caste = Caste::where('id',$request->caste_id)->update([
                    'value'         =>      $caste_name,
                    'religion_id'   =>      $request->religion
                ]);
                if ($save_caste) {
                    return response()->json(['type' => true, 'message' => 'caste updated']);
                } else {
                    return response()->json(['type' => false, 'message' => 'failed to update']);
                }
            }
        } else {
            return response()->json(['type' => false, 'message' => 'caste & religion is required']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy(cr $cr)
    {
        //
    }
}
