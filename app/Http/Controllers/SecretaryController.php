<?php

namespace App\Http\Controllers;

use App\Models\Date;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class SecretaryController extends Controller
{
    public function addDates(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'interval' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $startDay = Carbon::parse($request->start_date);
        $endDay = Carbon::parse($request->end_date);

        $created = [];
        while ($startDay->lte($endDay)) {
            $time = Carbon::parse($startDay->format('Y-m-d') . ' ' . $request->start_time);
            $end = Carbon::parse($startDay->format('Y-m-d') . ' ' . $request->end_time);

            while ($time->lt($end)) {
                $slot = Date::firstOrCreate([
                    'date' => $time->copy()
                ]);
                $created[] = $slot;
                $time->addMinutes($request->interval);
            }

            $startDay->addDay();
        }
        return response()->json([
            'message' => 'dates generated successfully.',
            'dates_created' => count($created),
            'dates' => $created
        ], 200);
    }
    public function deleteDate(Request $request, $id)
    {
        $date = Date::find($id);
        if ($date) {
            $date->delete();
            return response()->json(['date deleted successfully'], 200);
        }
        return response()->json(['Date not found'], 404);
    }
    public function updateDate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $date = Date::find($id);
        if ($date) {
            $date->date = $request->input('date');
            $date->save();
            return response()->json(['date updated successfully'], 200);
        }
        return response()->json(['date not found'], 404);

    }
}
