<?php

namespace App\Http\Controllers;

use App\Models\Date;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function getAllDates()
    {
        $user = auth()->user();
        if ($user) {
            $allDates = Date::where('blocked', false)->get();
            $datesForMe = Date::where('blocked', true)->where('user_id', $user->id)->get();
            return response()->json(['all unBlocked dates' => $allDates, 'all blocked Date By me' => $datesForMe], 200);
        } else {
            return response()->json(['user not found'], 404);
        }
    }
    public function reserveDate($id)
    {
        $user = auth()->user();
        if ($user) {
            $date = Date::where('id', $id)->first();
            if ($date) {
                if (!$date->blocked) {
                    $date->update([
                        'status_for_patient' => "waiting",
                        'status_for_doctor' => "not completed preview",
                        'blocked' => true,
                        'user_id' => $user->id
                    ]);
                    $date->save();
                } else {
                    return response()->json(['this date already reserve'], 400);
                }
                return response(['date reserve successfully', $date], 200);
            } else {
                return response(['date unreserve please try again'], 400);
            }
        }
        return response()->json(['user not found'], 404);
    }

}
