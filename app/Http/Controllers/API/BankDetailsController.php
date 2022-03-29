<?php

namespace App\Http\Controllers\API;

use App\Models\arena;
use App\Models\BankAccount;
use App\Models\Activitylogs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankDetailsController extends Controller
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
        return BankAccount::with(['arenaDetails'])->latest()->get();
    }
    public function Companybank()
    {
        return BankAccount::with(['arenaDetails'])->where('isAdmin',true)->get();
    }

    
    public function show_arena()
    {
          return arena::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function FilterBank($id)
    {
        return BankAccount::where('id',$id)->first();
    }

    public function arenaSelectedBank($bankId)
    {
         return BankAccount::where('id',$bankId)->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        $this->validate($request,[
       
            'bank_name' => 'required|string|max:191',
            'bank_number' => 'required|string'
        ]);

        $bank = BankAccount::create([
            // 'arenas_id' => $request['arenas_id'],
            'area_code' => $request['area_code'],
            'account_name'=> $request['account_name'],
            'bank_name' => $request['bank_name'],
            'bank_number' => $request['bank_number'],
            'isAdmin' => $request['isAdmin'],
        ]);
        
        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('created',$bank->account_name,'bank',$bank->id);

        return $bank;
    }



    public function bankStore(Request $request)
    {
    
        // $bankImport = BankAccount::upsert($request->all(),['area_code']);
        foreach ($request->all() as $data){
         
            if(!BankAccount::where('account_name',$data['account_name'])->where('area_code', $data['area_code'])->where('bank_number', $data['bank_number'])->where('bank_name', $data['bank_name'])->exists()) {
                    BankAccount::updateOrCreate([
                    'area_code' => $data['area_code'],
                    'account_name' => $data['account_name'],
                    'bank_name' => $data['bank_name'],
                    'bank_number' => $data['bank_number'],
                    
                ]);
            }
      
       }

    
        return true;
        

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $bank = BankAccount::findOrFail($id);

        $this->validate($request,[  
            'bank_name' => 'required|string|max:191',
            'bank_number' => 'required|string'
        ]);

        $bank->update([
            // 'arenas_id' => $request['arenas_id'],
            'account_name'=> $request['account_name'],
            'area_code' => $request['area_code'],
            'bank_name' => $request['bank_name'],
            'bank_number' => $request['bank_number'],
            'isAdmin' => $request['isAdmin'],
        ]);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$bank->account_name,'bank',$bank->id);
        
        return ['message' => 'Updated the areana details'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = BankAccount::findOrFail($id);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('deleted',$account->account_name,'bank',$account->id);
        $account->delete();
       
        
        return ['message' => 'User Deleted'];
    }
}
