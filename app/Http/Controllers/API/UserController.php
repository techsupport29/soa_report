<?php

namespace App\Http\Controllers\API;

use view;
use App\Models\Role;
use App\Models\User;
use App\Models\import;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Artisan;

class UserController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return User::with('permission.roles')->latest()->get();
    }


    public function getUserTeam(Request $request, $teamId) {
        $userTeam = User::with(['positionDetails','teamDetails'])->where('team_id', $teamId)->get();


        return $userTeam;
    }

    public function getStaffs() {
        $getStaffs = User::with(['positionDetails','teamDetails'])->where('type','!=','admin')->where('type', '!=', 'tech')->whereNull('assign')->orWhere('assign', 'computed')->whereNull('team_id')->get();

        return $getStaffs;
    }

    public function getAssigned(Request $request, $teamid) {
        // dd($teamid);
        $getComputedUserTeam = User::with(['positionDetails','teamDetails'])->where('isAssign',true)->where('team_id',$teamid)->first();

        return $getComputedUserTeam;
    }

    public function getAvailableSignatory(Request $request, $signatory) {

        $getUsers = User::with(['positionDetails'])->where('type','!=','admin')->where('type', '!=', 'tech')->whereNull('assign')->orWhere('assign', $signatory)->get();

        return $getUsers;
    }

    public function getUsersSignatory(Request $request, $signatory) {

        $getUsersSignatory = User::with(['positionDetails'])->where('assign', $signatory)->get();

        return $getUsersSignatory;
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
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users',
            'password' => 'required|string|min:6',
            'permission' => 'required'
        ]);

        $user =  User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'team_id' => $request['team_id'],
            'position_id' => $request['position_id'],
            'assign' => $request['assign'],
            'password' => Hash::make($request['password']),
        ]);


        foreach($request->permission as $permission){
           $permision =  Permission::create([
                'user_id' => $user->id,
                'role_id'  => $permission,
            ]);
        }
        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('created',$user->username,'profile',$user->id);

        // return $user;
    }


    public function updateProfile(Request $request)
    {

    $user = auth('api')->user();


        $this->validate($request,[
            'name' => 'required|string|max:191',
            // 'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);



        $currentPhoto = $user->photo;
            if($request->photo != $currentPhoto){
                $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

                Image::make($request->photo)->save(public_path('image/profile/').$name);
                $request->merge(['photo' => $name]);

                $userPhoto = public_path('image/profile/').$currentPhoto;
                if(file_exists($userPhoto)){
                    @unlink($userPhoto);
                }

            }

            if(!empty($request->password)){
                $request->merge(['password' => Hash::make($request['password'])]);
            }


        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$user->username,'profile',$user->id);

        $user->update($request->all());
        return ['message' => "Success"];
    }

    public function profile()
    {
        return auth('api')->user();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function validate_user($password)
    {

        if (Hash::check($password, Auth::user()->password)) {
            // $artisan = Artisan::call('backup:run',[
            //     '--only-db' => true,
            // ]);
             $import =  import::truncate();
             $activity_controller = new ActivitylogsController;
             $activity_controller->arenaLogs('truncate',Auth::user()->name,'profile',Auth::user()->id);
             return 'success';
        }else{
            return 'error';
        }


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

        $user = User::findOrFail($id);



        if($request->type == 'admin'){
            $request['team_id'] = null;
            $request['position_id'] = null;
            $request['assign'] =  null;
        }

        $this->validate($request,[
            'name' => 'required|string|max:191',
            // 'username' => 'required|string|email|max:191|unique:users,'.$user->id,
            'password' => 'sometimes|min:6'
        ]);

        $user->update([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'team_id' => $request['team_id'],
            'position_id' => $request['position_id'],
            'assign' => $request['assign'],
            'password' => $request['password'] == null ? $user->password :  Hash::make($request['password'])
        ]);



        foreach($request->permission as $permission){
             $permisions =  Permission::updateOrCreate([
                    'user_id' => $user->id,
                    'role_id'  => gettype($permission) == 'array' ? $permission['id'] : $permission,
            ]);

            }

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$user->username,'profile',$user->id);


        return ['message' => 'Updated the user info'];
    }

    public function updateUserTeam(Request $request, $id){
        $user = User::findOrFail($id);

        $user->update([
            'team_id' => $request['team_id'],
            'assign' => $request['assign'],
        ]);

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$user->username,'team',$user->id);

        return $user;
    }


    public function updateSelectedUserToTeam(Request $request, $team){

        foreach($request['users'] as $user){
            $user = User::findOrFail($user['id']);

            $user->update([
                'assign' => $request['assign'],
                'team_id' => $team
            ]);
        };


        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$user->username,'team',$user->id);
        return true;
    }

    public function updateAssignedTeam(Request $request, $id){
        $user = User::findOrFail($id);

        $usersTeam = User::where('team_id', $request['team_id']);
        $usersTeam->update(['isAssign' => false]);

        $user->update(['isAssign' => true]);
        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$user->username,'team',$user->id);
        return $user;
    }

    public function updateSignatory(Request $request){

        $userAssigned = User::where('assign', $request['assigned']);
        $getUsers = $userAssigned->get();
        if(count($request['users']) > $request['noOfSign']) throw new \Exception('Assigned User Exceeded');

        if(!$getUsers->isEmpty()) {
            $userAssigned->update([
                'assign' => null
            ]);
        }

        foreach($request['users'] as $users){
            $user = User::findOrFail($users['id']);
            $signatoryUpdate = $user->update([
                'assign' => $request['assigned']
            ]);
        }
        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('updated',$userAssigned->username,'signatory',$userAssigned->id);
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');


        $user = User::findOrFail($id);
        $permission = permission::where('user_id', $id)->delete();

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('deleted',$user->username,'user',$user->id);
        // delete the user
        $user->delete();

        return ['message' => 'User Deleted'];

    }
    public function roles(){
       return Role::latest()->get();
    }

    public function getpermission($id){
        return permission::with('roles')->where('user_id', $id)->get();
    }

    public function DeletePermissions(request $request){
        $permission = permission::where('user_id', $request->userid)
                        ->where('role_id',$request->roleid)
                        ->delete();

        return permission::with('roles')->where('user_id', $request->userid)->get();
    }



}
