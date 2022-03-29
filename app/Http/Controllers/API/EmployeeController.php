<?php

namespace App\Http\Controllers\API;

use Throwable;
use App\Models\User;
use App\Models\employee;
use App\Models\position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ActivitylogsController;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

        
    public function __construct()
    {
        $this->middleware('auth:api');

    }

    public function index()
    {
        $computed =  employee::where('group','computed')->get();
        $prepared = employee::where('group','prepared')->get();
        $checked = employee::where('group','checked')->get();
        $employee = employee::all();
        return response()->json([
            'computed' => $computed,
            'prepared' => $prepared,
            'checked' => $checked,
            'employee' => $employee
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    

    public function selectedbyUser(Request $request, $old)
    {   

      
            if($old != 'undefined') {

                $olded = employee::where('id',$old)->first();

                $olded->update([ 'assign' => false
            ]);

        }
            if($request['new']) {
                $newed = employee::where('id',$request['new'])->first();
                $newed->update([
                    'assign' => true
                ]);
            
            }

            return true;
   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        dd($request->all());
        // $this->validate($request,[
        //     'name' => 'required|string|max:191',
        //     'email' => 'required|string|email|max:191|unique:users',
        //     'group' => 'required',
        //     'position' => 'required',
        // ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'group' => $request['group'],
            'position' =>$request['position'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function countEmployee()
    {
        $computed =  employee::where('group','computed')->where('assign','1')->count();
        $prepared = employee::where('group','prepared')->where('assign','1')->count();
        $checked = employee::where('group','checked')->where('assign','1')->count();

        return response()->json([
            'computed' => $computed,
            'prepared' => $prepared,
            'checked' => $checked,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function savepostionNow(request $request)
    {
        $this->validate($request,[
            'position' => 'required',
        ]);

     
        $position = position::create([
            'position' => $request->position
        ]);


        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('created',$position->position,'position',$position->id);


    }

    public function getpostionNow()
    {
        return position::latest()->get();
    }
    public function updatepostionNowwith(request $request,$id){
        
        $this->validate($request,[
            'position' => 'required',
        ]);

        $position = position::findOrFail($id)->update([
            'position' => $request->position
        ]);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$request->position,'position',$id);
     

        return ['message' => 'Updated the user info'];

    }
    

    public function deletepostionNow($id)
    {
  
        $position = position::findorfail($id);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('deleted',$position->position,'position',$id);
        
        $position->delete();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = employee::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191',
            'contact' => 'required',
            'address' => 'required',
            'group' => 'required',
            'position' => 'required',
        ]);

        $employee->update($request->all());
        return ['message' => 'Updated the user info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
        $employee = employee::findOrFail($id);
        // delete the user

        $employee->delete();

        return ['message' => 'User Deleted'];
    }
}
