<?php

namespace App\Http\Controllers\API;

use App\Models\import;
use Illuminate\Http\Request;
use App\Models\ArenaHasGroup;
use App\Models\OperatorGroups;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\ActivitylogsController;

class OperatorGroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');

    }

    public function index(){
        return response()->json(OperatorGroups::with(['Hasgroup'])->latest()->get());
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
        ]);

        $group = OperatorGroups::create([
            'name' => $request['name'],
            'email' => $request['email'],
        ]);

        // dd($group->name);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('created',$group->name,'group',$group->id);

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
        ]);

        // $this->arena($id,'update',$request->team);

        $group = OperatorGroups::findOrFail($id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$group->name,'group',$group->id);
    }

    public function destroy($id)
    {

        $group =  OperatorGroups::findOrFail($id);
        $hasarena =  ArenaHasGroup::where('group_id',$group->id);

        if(!$group || !$hasarena){
            return false;
        }

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('deleted',$group->name,'group',$group->id);

        $hasarena->delete();
        $group->delete();
    }

    public function getselectedGroup($id){

        return response()->json(OperatorGroups::with(['Hasgroup.SoaDetails.arenaDetails.EmailDetails'])->whereId($id)->get());
    }

    public function Deletegroupsselectedarena($id){

        $group =  ArenaHasGroup::findOrFail($id);
        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('deleted arena in',$group->name,'group',$group->id);

        $group->delete();
    }

    public function Addtogroup(request $request){

        foreach($request->data as $area_code){
            $addarena = ArenaHasGroup::updateOrCreate([
                'area_code' => $area_code['area_code'],
                'group_id' => $request->id
            ]);

            $activity_controller = new ActivitylogsController;
            $activity_controller->arenaLogs('Add arena',$area_code['area_code'],'group',$addarena->id);
        }


    }

    // GET IMPORTS BASED ON GROUP AND DATE
    public function groupSoaSummaryReport(Request $request) {
        return import::with(['BankDetails','arenaDetails.BankDetails'])->select('date_of_soa')->groupBy('date_of_soa')->orderBy('date_of_soa', 'DESC')->get();

    }

    public function fetchSoaByOperatorGroup(Request $request){

        $soa = import::with(['BankDetails','arenaDetails.BankDetails', 'arenaDetails.EmailDetails','arenaDetails.ContactDetails'])->whereIn('areaCode', $request->areaCodes)->whereBetween('date_of_soa',[$request->from, $request->to])->get();
        return $soa;
    }

}
