<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ArenaHasGroup;
use App\Models\OperatorGroups;
use App\Models\import;
use App\Http\Controllers\Controller;

class OperatorGroupController extends Controller
{
    public function index(){
        return response()->json(OperatorGroups::with(['Hasgroup'])->latest()->get());
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
        ]);

        $team = OperatorGroups::create([
            'name' => $request['name'],
            'email' => $request['email'],
        ]);




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
    }

    public function destroy($id)
    {

        $group =  OperatorGroups::findOrFail($id);
        $hasarena =  ArenaHasGroup::where('group_id',$group->id);

        $hasarena->delete();
        $group->delete();
    }

    public function getselectedGroup($id){

        return response()->json(OperatorGroups::with(['Hasgroup.SoaDetails.arenaDetails.EmailDetails'])->whereId($id)->get());
    }

    public function Deletegroupsselectedarena($id){

        $group =  ArenaHasGroup::findOrFail($id);
        $group->delete();
    }

    public function Addtogroup(request $request){

        foreach($request->data as $area_code){
            $addarena = ArenaHasGroup::updateOrCreate([
                'area_code' => $area_code['areaCode'],
                'group_id' => $request->id
            ]);

        }
    }

    // GET IMPORTS BASED ON GROUP AND DATE

    public function fetchSoaByOperatorGroup(Request $request){
    
        $soa = import::with(['BankDetails','arenaDetails.BankDetails', 'arenaDetails.EmailDetails'])->whereIn('areaCode', $request->areaCodes)->where('date_of_soa',$request->from)->get();
        return $soa;
    }

}
