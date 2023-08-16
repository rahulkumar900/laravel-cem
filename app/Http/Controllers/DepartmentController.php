<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Validator;
class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('masters.department-management');
    }

    /**
     * Show the departments which is active.
     *
     * @return \Illuminate\Http\Response
     */
    public function getallDepartment()
    {
        $i=1;
        $all_departments = Department::where('is_active',1)->orderBy('id','desc')->get();
        $button = '';
        foreach($all_departments as $all_department){
            $button = '<button class="btn btn-warning btn-rounded waves-effect waves-light btn_edit" id="'.$all_department->id.'" dept_name="'.$all_department->department_name.'">Edit</button>
            <button class="btn btn-danger btn-rounded waves-effect waves-light btn_delete" id="'.$all_department->id.'">Delete</button>';
            $data[] =array(
                'sl'                =>      $i++,
                'department_name'   =>      strtoupper($all_department->department_name),
                'created_at'        =>      date('d-m-Y H:i:s',strtotime($all_department->created_at)),
                'button'            =>      $button
            );
        }
        $dataset = array(
            "echo" => 1,
            "totalrecords" => count($data),
            "totaldisplayrecords" => count($data),
            "data" => $data
        );

        return response()->json($dataset);
    }

    /**
     * Store a newly created resource & update in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'department_name'       =>      'required|string|unique:departments,department_name'
        ]);

        if ($validation->fails()){
            return response()->json(['type'=>false,'message'=>$validation->errors()]);
        }else{
            $department_create = array(); $action = '';
            // update record
            if($request->department_id > 0){
                $department_create = Department::where('id',$request->department_id)->update([
                    'department_name'       =>   strtolower($request->department_name)
                ]);
                $action = 'updated';
            }
            // add new record
            else{
                $department_create = Department::create([
                    'department_name'       =>   strtolower($request->department_name)
                ]);
                $action = 'added';
            }
            if($department_create){
                return response()->json(['type'=>true,'message'=>"departnemt $action successfully"]);
            }
        }
    }



    /**
     * Remove the specified resource from display.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function deleteDepartment(Request $request)
    {
        $department = Department::where('id',$request->dept_id)->update([
            'is_active'     =>      0
        ]);
        if($department){
            return response()->json(['type'=>true,'message'=>'deleted successfully']);
        }else{
            return response()->json(['type'=>false,'message'=>'failed to delete']);
        }
    }

    // get department list for dropdown
    public function getDepartmentJson(){
        $dept_lsit = Department::getallDepartmentList()->get()->toArray();

        return response()->json(['type'=>true,'data'=>$dept_lsit]);
    }

}
