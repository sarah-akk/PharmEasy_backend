<?php

namespace App\Http\Controllers;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\password;

class AuthController extends Controller
{ public function register_user (Request $request){

    $RegisterData = $request->validate([
           'name'=> 'required |string',
           'phone'=>'required |unique:users',
           'password' => 'required|min:6|confirmed',
       ],[
          'phone.unique'=> 'Phone already exists!!'
    ]);

       $user=User::query()->create([
           'name'=>$request->name,
           'phone'=>$request->phone,
        'password'=>bcrypt($request->password),
      ]);
       $token=$user->createToken ('personal Access Token')->plainTextToken;
       //$user['remember_token']=$token;
       //$user->save();

       return response()->json([
           'user'=>$user,
           'token'=>$token,
           'message'=>'registered successfully'
       ],201);

   }
   public function login_user(Request $request){
       $request->validate([
           'phone'=>['required','exists:users,phone'],
           'password'=>['required']
       ]);
       if(!Auth::attempt($request->only(['phone','password']))){

           return response ()->json(['message'=>' Password is Wrong !.']);

       }
   $user = User ::query()->where('phone','=',$request['phone'])->first();
       $token=$user->createToken ('personal Access Token')->plainTextToken;

       $data=[];

       $data['user']=$user;
       $data['token']=$token;
       return response()->json([
           'token'=>$token,
           'user' => $user,
           'message'=>'user log in successfully'
       ],200);
   }


   public function logout_user(){
   Auth::user()->currentAccessToken()->delete();

       return response()->json([
           'message'=>'user log out successfully'
       ],200);
   }



   public function logintoAdmin (Request $request)
   {
       $input = Validator::make($request->all(), [
           'phone' => ['required', 'exists:users,phone'],
           'password' => ['required'],

       ]
    );
           if ($input->fails()) {
           return response()->json($input->errors(),);
       }
if (!Auth::attempt($request->only(['phone', 'password']))) {

      return response()->json(['message' => 'phone or Password are Wrong.']);}
   if(Auth::check() && !Auth::user()->isAdmin()){
        return response()->json(['message' => 'your acount is user.']);
    }
       else {  $user = User ::query()->where('phone','=',$request['phone'])->first();
           $token=$user->createToken ('personal Access Token')->plainTextToken;

      return response()->json([
               'data'=>$token,
               'message' => 'welcome.']);}
   }





         }

