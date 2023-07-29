<?php

namespace App\Http\Controllers;

use App\Models\users;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mail;

class UserController extends Controller
{
    public function signup(Request $request) {
        try{
        $validate = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:20',
           	'name' => 'required|string|min:3|max:30',
			'date_of_birth' => 'required|string|max:10',
            'street_name' => 'required|string|min:5|max:40',
            'address_line_1' => 'required|string|min:5|max:40',
            'address_line_2' => 'required|string|min:5|max:40',
            'city' => 'required|string|min:5|max:20',
            'pincode' => 'required|string|min:6|max:8',
            'state' => 'required|string|min:2|max:2',
            'country' => 'required|string|min:2|max:3',
            'phone_number' => 'required|string|min:10|max:10',
			'email_id' => 'required|email|max:50',
            'password' => 'required|string|min:8|max:30',

            // 'otp' => 'required|string|min:4|max:4',
        ],
        // [
        //     'name.required' => 'Name is must.',
        //     'name.min' => 'Name must have 5 char.',
        // ]
        );
        if($validate->fails()){
        // return back()->withErrors($validate->errors())->withInput();
        // return response()->json($validate->errors()->toArray());
        return response()->json([
            'message' => $validate->errors()->toArray()[array_keys($valid->errors()->toArray())[0]][0],
            'status' => false
            ], 200);
        }
        else if(!empty(users::where(['username'=>$request->username])->exists())){
            return response()->json(['error'=>'Username already exists']);
        }
        else if(!empty(users::where(['email'=>$request->email_id])->exists())){
            return response()->json(['error'=>'Email already exists']);
        }
        else if(!empty(users::where(['phone_number'=>$request->phone_number])->exists())){
            return response()->json(['error'=>'Phone Number already exists']);
        }
        else{
                $user = new users;
                $address = [];
                $user->username = $request->username;
                $user->name = $request->name;
                $user->date_of_birth = $request->date_of_birth;
                $address['street_name'] = $request->street_name;
                $address['address_line_1'] = $request->address_line_1;
                $address['address_line_2'] = $request->address_line_2;
                $address['city'] = $request->city;
                $address['pincode'] = $request->pincode;
                $address['state'] = $request->state;
                $address['country'] = $request->country;
                $user->address =json_encode($address);
                $user->phone_number = $request->phone_number;
                $user->email = $request->email_id;
                $user->password = hash("sha512",$request->password);
                // $user->otp = mt_rand(100000,999999);
                $user->otp = "123456";
                $user->verify_email = "0";
                $user->user_type = "1";
                $user->save();

                // mail

                // $title = 'Crakers Mart';
				// $content = "<p>Hi</p><p>Your Crakers Mart Verification Code is $user->otp</p><p>Thank you!</p>";
				// $data2 = ['subject'=>$title,'content'=>$content];
				// $email = $request->email_id;
				// Mail::send(['name'=>$user->name], $data2, function($sendmail) use ($email,$title) {
				// 		$sendmail->to($email, $title)->subject($title);
				// });

                // expiry time

				// $endTime = Carbon::now()->addMinutes(env('OTP_EXPIRY_MINUTE'))->format('Y-m-d H:i:s');
				// OtpVerification::updateOrCreate(
				// 	['email'=>$request->email],
				// 	['otp' => $user->otp,'expiry'=>$endTime]
				// );

                // return redirect('/')->with('status',"Insert successfully");
                return response()->json(['success'=>'user created successfully']);
            }   
        }
        catch(\Exception $exception){
            // return Redirect::back()->withErrors([$exception->getMessage()])->withInput($request->input());
            return response()->json($exception->getMessage());
        } 
        catch (\Illuminate\Database\QueryException $exception ){
        // return redirect('/')->with("failed",$exception->getMessage());
        return response()->json($exception->getMessage());
        }             	         	
    } 

    function verify_otp(Request $request){
        $validate = Validator::make($request->all(), [
            'email_id' => 'required|email|max:50',
            'otp' => 'required|string|min:6|max:6',
        ]);
        try{
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }else if(!empty(users::where(['email'=>$request->email_id])->exists())){
                $user = users::where(['otp'=>$request->otp],['email'=>$request->email_id])->first();
                if(!empty($user)){
                    $user->verify_email = "1";
                    $user->save();
                    return response()->json(['success'=>'Email verified Successfully']);
                }else{
                    return response()->json(['error'=>'Invalid OTP']);
                }
            }else{
                return response()->json(['error'=>'Invalid Email']);
            }
        }
        catch(\Exception $exception){
            return response()->json($exception->getMessage());
        } 
        catch (\Illuminate\Database\QueryException $exception ){
            return response()->json($exception->getMessage());
        } 
    }

    function login(Request $request) {
        $validate = Validator::make($request->all(), [
            'email_id' => 'required|email|max:50',
            'password' => 'required|string|min:8|max:30',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors()->toArray());
        }
        else if(!empty(users::where(['email'=>$request->email_id])->exists())){
            if(!empty(users::where(['password'=>hash("sha512",$request->password)])->exists())){
                return response()->json(['success'=>'Login successfully']);
            }
            else {
                return response()->json(['error'=>'Incorrect Password']);
            }
        }
        else{
            return response()->json(['error'=>'Invalid Email']);
        }
    }

    function update_user(Request $request){
        $validate = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:20',
            'name' => 'required|string|min:3|max:30',
            'date_of_birth' => 'required|string|max:10',
            'address' => 'required|string|min:5|max:40',
            'phone_number' => 'required|string|min:10|max:10',
            'email_id' => 'required|email|max:50',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors()->toArray());
        }
        else if(!empty(users::where(['username'=>$request->username])->exists())){
            if(!empty(users::where(['email'=>$request->email_id])->exists())){
                return response()->json(['error'=>'Email already exists']);
            }
            else if(!empty(users::where(['phone_number'=>$request->phone_number])->exists())){
                return response()->json(['error'=>'Phone Number already exists']);
            }
            else{
                try{
                    $user = users::where(['username'=>$request->username])->first();
                    $user->name = $request->name;
                    $user->date_of_birth = $request->date_of_birth;
                    $user->address = $request->address;
                    $user->phone_number = $request->phone_number;
                    $user->email = $request->email_id;
                    // $user->otp = mt_rand(100000,999999);
                    $user->otp = "123456";
                    $user->verify_email = "0";
                    $user->user_type = "1";
                    $user->save();
                    return response()->json(['success'=>'User updated successfully']);
                }
                catch(\Exception $exception){
                    return response()->json($exception->getMessage());
                } 
                catch (\Illuminate\Database\QueryException $exception ){
                    return response()->json($exception->getMessage());
                }  
            }
        }
        else{
            return response()->json(['error'=>'Invalid Username']);
        }
    }

    function reset_password(Request $request){
        $validate = Validator::make($request->all(), [
            'email_id' => 'required|email|max:50',
            'password' => 'required|string|min:8|max:30',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors()->toArray());
        }
        else if(!empty(users::where(['email'=>$request->email_id])->exists())){
            try{
                $user = users::where(['email'=>$request->email_id])->first();
                $user->password = hash("sha512",$request->password);
                // $user->otp = mt_rand(100000,999999);
                $user->otp = "123456";
                $user->save();
                return response()->json(['success'=>'Password reset successfully']);
            }
            catch(\Exception $exception){
                return response()->json($exception->getMessage());
            } 
            catch (\Illuminate\Database\QueryException $exception ){
                return response()->json($exception->getMessage());
            }  
        }
        else{
            return response()->json(['error'=>'Invalid Email']);
        }
    }
}
?>