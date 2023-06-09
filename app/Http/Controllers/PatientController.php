<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patient;
use App\Pet;

class PatientController extends Controller
{
    //

    public function storeClient(Request $request){


        $dataPatient = new Patient;
        $dataPet = new Pet;
        $dataPatient = $request->all();
        $patient = Patient::create($dataPatient);
       
       return 'Data saved' && $patient;
    }

    public function editClient($id, Request $request) {
        $dataPatient = Patient::find($id);
        $dataPatient->fill($request->all());
        $dataPatient->save();
        
        return 'Data updated';
    }

    public function getAll(){
        $data = Patient::get();

        return $data;
    }
}
