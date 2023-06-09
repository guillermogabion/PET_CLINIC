<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\File;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;
use PDF;
use Illuminate\Http\Request;
use App\Pet;
class PetController extends Controller
{
    //

    public function store(Request $request)
    {
    //    dd($request->all());

    $record = new Pet;
    $record = $request->all();
    $data = Pet::insert($record);

    return $data;
    }


    public function getAllPet(){
        $data = Pet::with('owner', 'record')->get();

        return $data;
    }
//     public function printCert($id)
// {
//     $pets = Pet::where('id', $id)->with(['owner', 'record' => function ($query) {
//         $query->where('service', 'vaccination');
//     }])->get();

//     $pdf = new Dompdf();
//     $pdf->loadHTML(View::make('pdf.template', compact('pets'))->render());

//     // Set paper size and orientation (optional)
//     $pdf->setPaper('A4', 'portrait');

//     // Render the PDF
//     $pdf->render();

//     // Generate a unique filename for the PDF
//     $filename = 'certificate_' . uniqid() . '.pdf';

//     // Save the generated PDF to a temporary file
//     $tempFilePath = storage_path('app/public/' . $filename);
//     file_put_contents($tempFilePath, $pdf->output());

//     // Return the PDF file as a download response
//     $fileContent = File::get($tempFilePath);
//     $headers = [
//         'Content-Type' => 'application/pdf',
//         'Content-Disposition' => 'attachment; filename="' . $filename . '"',
//     ];

//     return Response::make($fileContent, 200, $headers)->deleteFileAfterSend(true);
// }

public function printCert($id)
{
    $pets = Pet::where('id', $id)->with(['owner', 'record' => function ($query) {
        $query->where('service', 'vaccination');
    }])->get();

    $pdf = PDF::loadView('template', compact('pets'));
        
    // return $pdf->download('test.pdf');
    return $pdf->stream('test.pdf', ['Attachment' => false]);
}

    public function deletePet($id){
        Pet::find($id)->delete();
        return 'Pet Removed';
    }


    public function editPet($id, Request $request)
    {
        $dataPet = Pet::find($id);
        $dataPet->fill($request->all());
        $dataPet->save();
        return 'Data updated';
    }
}
