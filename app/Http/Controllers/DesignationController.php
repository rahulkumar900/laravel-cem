<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Validator;
use DB;
use Illuminate\Support\Facades\Cache;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('masters.designation-management');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getallDesignation()
    {
        $i=1;
        $all_departments = '';
        $all_departments =  Cache::remember('alldesignations', 5, function () {
            return Designation::getAllDesignations();
        });

        $button = '';
        /*foreach($all_departments as $all_department){
            $button = '<button class="btn btn-warning btn-rounded waves-effect waves-light btn_edit" id="'.$all_department->id.'" desig_name="'.$all_department->designation.'" dept_id="'.$all_department->department->id.'">Edit</button>
            <button class="btn btn-danger btn-rounded waves-effect waves-light btn_delete" id="'.$all_department->id.'">Delete</button>';
            $data[] =array(
                'sl'                =>      $i++,
                'department_name'   =>      strtoupper($all_department->department->department_name),
                'designation'       =>      strtoupper($all_department->designation),
                'created_at'        =>      date('d-m-Y H:i:s',strtotime($all_department->created_at)),
                'button'            =>      $button
            );
        }*/
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($all_departments),
            "totaldisplayrecords" => count($all_departments),
            "data" => $all_departments
        );

        return response()->json($dataset);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation =  Validator::make($request->all(),[
            'department_name'       =>      'required|integer',
            'designation_name.*'    =>      'required|string|unique:designations,designation'
        ]);
        if($validation->fails()){
            return response()->json(['type'=>false,'message'=>$validation->errors()]);
        }else{
            $type = ''; $message = '';
            // update record
            if($request->designation_id>0){
                $update_designation = Designation::where('id',$request->designation_id)->update([
                    'department_id'      =>      $request->department_name,
                    'designation'        =>      $request->designation_name[0]
                ]);
                if($update_designation){
                    $type = true; $message = 'designations updated successfully';
                }else{
                    $type = false; $message = 'failed to update';
                }
            }
            // add new record
            else{
                DB::beginTransaction();
                foreach ($request->designation_name as $designation) {
                    $save_record = Designation::create([
                        'department_id'     =>      $request->department_name,
                        'designation'       =>      $designation,
                        'is_active'         =>      1
                    ]);
                }
                if($save_record){
                    DB::commit();
                    $type = true; $message = 'designations added successfully';
                }else{
                    DB::rollback();
                    $type = false; $message = 'failed to save record';
                }
            }
            return response()->json(['type'=>$type,'message'=>$message]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit(Designation $designation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designation $designation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Designation $designation)
    {
        //
    }
}
