<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\API\ActivitylogsController;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

     /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

     /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }
    public function login(request $request)
    {

        $login = request()->input('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

   
        $user = DB::table('users')->where($fieldType, $request->input($fieldType))->first();

        if (auth()->guard('web')->attempt([$fieldType => $request->input($fieldType), 'password' => $request->input('password')])) {

            $new_sessid   = Session::getId(); //get new session_id after user sign in

            if($user->session_id != '') {
                $last_session = Session::getHandler()->read($user->session_id);

                if ($last_session) {
                    if (Session::getHandler()->destroy($user->session_id)) {

                    }
                }
            }

            DB::table('users')->where('id', $user->id)->update(['session_id' => $new_sessid]);

            $activity_controller = new ActivitylogsController;
            $activity_controller->arenaLogs('login',$user->name,'system',$user->id);

            $user = auth()->guard('web')->user();


            return redirect($this->redirectTo);
        }

        $errors = 'Invalid Credential';
        return Redirect::back()->withErrors($errors);

    }

    public function logout(){

        $user = Auth::user();

        $activity_controller = new ActivitylogsController;
        $activity_controller->arenaLogs('logout',$user->name,'system',$user->id);

        Session::flush();
        Redirect::back();

        return redirect('/');
    }

 }
