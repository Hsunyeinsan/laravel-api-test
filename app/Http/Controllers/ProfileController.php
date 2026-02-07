<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeNameRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return new ProfileResource($request->user()); 
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'data' => [
                'message' => 'Logged out successfully',
            ],
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (! Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'data' => [
                    'message' => 'Old Password is incorrect',
                ],
            ], 401);
        }
        $user->update(['password' => Hash::make($request->new_password)]);

            $user->tokens()->delete();

            return response()->json([
                'data' => [
                    'message' => 'Password Changed Successfully',
                    
                ],
            ]);
    }
    public function changeName(ChangeNameRequest $request){
        $user=$request->user();

        $user->update(['name'=>$request->name]);
        return response()->json([
            "data"=>[
                "message"=>"Name Changed Successfully",
                'user'=>new ProfileResource($user)
            ]
        ]);
    }
}
