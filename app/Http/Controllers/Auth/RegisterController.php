<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function tRegister(Request $request){
        //get details of the teacher and register them here
        // DB::table('users')->insert(
        //     ['email' => 'john@example.com', 'votes' => 0]
        // );

        // $user = User::create([
        //     'name' => $request['name'],
        //     'email' => $request['email'],
        //     'password' => bcrypt($request['password']),
        // ]);

        // DB::table('role_user')->insert(
        //     ['role_id' => 2, 'user_id' => $user->id]
        // );
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $user->role()->sync([2]);

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($request->input('redirect_url'));

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $user->role()->sync([3]);

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($request->input('redirect_url'));
    }

}
