<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use App\Models\RolesUser;
use Illuminate\Support\Facades\Auth;
class GoogleController extends Controller
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

     /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $userSocial = Socialite::driver('google')->user();


        //check if user exists and log user in

        $user = User::where('email', $userSocial->email)->first();
        if($user){
            if(Auth::loginUsingId($user->id)){
               return redirect()->route('home');
            }
        }

     //else sign the user up
     //return $userSocial->name;
     $userSignup = User::create([
            'nombre' => $userSocial->name,
            'username' => $userSocial->id,
            'email' => $userSocial->email,
            'password' => bcrypt('1234'),
            'google_id'=> $userSocial->id,
            'name'=> $userSocial->nickname
        ]);


        $cat_rolUser = new RolesUser();
        $cat_rolUser->id_user = $userSignup->id;
        $cat_rolUser->id_rol = "2";
        $cat_rolUser->save();

      
        //finally log the user in
        if($userSignup){
            if(Auth::loginUsingId($userSignup->id)){
                return redirect()->route('home');
            }
        }

    }


}
