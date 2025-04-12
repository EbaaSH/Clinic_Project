<?php

namespace App\Http\Controllers;

use App\Models\Date;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function previewDate($id)
    {
        $user = auth()->user();
        if ($user) {
            $date = Date::where('id', $id)->where('status_for_patient', "accepted")->where('status_for_doctor', "not completed preview")->first();

            if ($date) {
                $date->update([
                    'status_for_doctor' => 'completed preview'
                ]);
                return response()->json(['date preview by doctor', $date], 200);
            } else {
                return response()->json(['date not found', $date], 404);
            }

        }
        return response()->json(['user not found'], 404);
    }
    public function getDatesForDoctor()
    {
        $user = auth()->user();
        if ($user) {
            $previewDates = Date::where('status_for_doctor', "completed preview")->get();
            $notPreviewDates = Date::where('status_for_doctor', "not completed preview")->where('status_for_patient', "accepted")->get();
            if ($previewDates && $notPreviewDates) {
                return response()->json(['preview Dates' => $previewDates, 'not preview Dates' => $notPreviewDates], 200);
            }
            return response()->json(['dates not found'], 404);
        }
        return response()->json(['user not found'], 404);
    }
}
