<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Activitylogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActivitylogsController extends Controller
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

    public function AcitivityIndex(){
       return  Activitylogs::with(['User'])->latest()->get();
    }

    // public function logsIndex()
    // {
    //     return Activitylogs::where('subject_type','arena')->orWhere('subject_type','bank')->latest()->get();
    // }

    public function arenaLogs($description,$properties,$model,$subject_id){

        $activity =  DB::connection('mysql2')->table('activityslogs')->insert([
             'log_name'     => Auth::user()->name,
             'description'  => $description,
             'subject_type' => $model,
             'subject_id'   => $subject_id,
             'causer_type'  => Auth::user()->type,
             'properties'   => $properties,
             'ip_address'   => \Request::getClientIp(true),
             'causer_id'    => Auth::user()->id,
             'created_at'   => Carbon::now()->toDateTimeString()

         ]);

         return $activity;
     }

     public function BankAccount(request $request){

         foreach($request->all() as $data){
             foreach($data as $val){

                return Activitylogs::with(['BankActivity'])
                        ->where('subject_id',$val['id'])->get();
             }
         }
    }


}
