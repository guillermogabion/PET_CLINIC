<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;

class ScheduleController extends Controller
{
    //

    public function store(Request $request) {
        $schedule = New Schedule ;
       $schedule->name = $request->name;
       $schedule->contact = $request->contact;
       $schedule->description = $request->description;
       $schedule->start = $request->start;
       $schedule->end = $request->start;

       $schedule->save;

       return 'saved';

    }

    public function edit($id, Request $request) {
        $schedule = Schedule::find($id);
        $schedule->fill($request->all());
        $schedule->save();

        return 'Schedule Updated';
    }

    public function get(){
        $data = Schedule::get();

        return $data;
    }
}
