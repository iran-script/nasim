<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function sendsms(Request $request)
    {
        $user = User::where('mobile', $request->mobile)->first();
        if ($user) {
            // $verifycode = mt_rand(1000, 9999);
            $verifycode = 123456;
            $user->password = Hash::make($verifycode);
            $user->save();
            return response()->json(['data' => $verifycode, 'massage' => 'کد را وارد نمایید.', 'status' => 200], 200);
        } else {
            return response()->json(['data' => null, 'massage' => 'شماره معتبر نمی باشد.', 'status' => 401], 401);
        }
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['mobile' => request('mobile'), 'password' => request('password'), 'user_status' => 1])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['data' => ['token' => $success, 'userinfo' => User::find(Auth::id())], 'massage' => 'احراز هویت موفقیت آموز بود', 'status' => 200], $this->successStatus);
        } else {
            return response()->json(['data' => null, 'massage' => 'لاگین موفقیت آموز نبود.', 'status' => 401], 401);
        }
    }
}
