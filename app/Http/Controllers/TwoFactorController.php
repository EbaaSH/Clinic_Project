<?php

namespace App\Http\Controllers;

use App\Notifications\TwoFactorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TwoFactorController extends Controller
{
    public function varify(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make(request()->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if (auth()->check()) {
            if ($user->expire_at < now()) {
                $user->delete();
                return response()->json(['timeout! sorry this code is not working please sign up again']);
            }
        }

        if ($request->code == $user->code) {
            $user->resetTwoFactorCode();
            return response()->json(['thank you you enter a correct code'], 200);
        } else {
            return response()->json(['sorry you enter uncorect code']);
        }

    }
    public function resendCode()
    {
        $user = auth()->user();
        if (auth()->check()) {
            if ($user->expire_at < now()) {
                $user->delete();
                return response()->json(['timeout! sorry this code is not working please sign up again']);
            }
        }
        $user->generateCode();
        $user->notify(new TwoFactorCode($user->code, $user->first_name));
        return response()->json(['your varification code resend to your email successfully'], 200);
    }
}
