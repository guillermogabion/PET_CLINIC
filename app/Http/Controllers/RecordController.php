<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use App\Record;
use App\Schedule;
class RecordController extends Controller
{
    //

    public function store(Request $request) {
        $record = new Record;
        $data = $record->create($request->all());

        return $data;
    }

    public function update($id, Request $request){
        $record = Record::find(id);
        $record->course = $request->course;
        $record->course_code = $request->course_code;
        $record->unit = $request->unit;
        $record->school_year = $request->school_year;
    
    }

    public function getRecord(Request $request) {
        $record = Record::where('pet_id', $request->pet_id)->get();
    }

    public function storeSched(Request $request) {
        $record = new Schedule;
        $data = $record->create($request->all());

        return $data;
    }

    public function sendSms(Request $request)
{
    $apiKey = 'DE-AMALI803973_1Z488';
    $url = 'https://api.itexmo.com/api/broadcast';

    $client = new GuzzleClient();

    $recipients = $request->input('contact'); // Assuming the recipient numbers are passed as an array in the 'recipients' field of the request
    $message = $request->input('message'); // Assuming the message is passed as a string in the 'message' field of the request

    $response = $client->request('POST', $url, [
        'form_params' => [
            'Email' => 'amaliagraceabarrientos@gmail.com',
            'Password' => 'January182020',
            'ApiCode' => $apiKey,
            'Recipients' => $recipients,
            'Message' => $message,
            'SenderId'=> ""
        ]
    ]);

    $statusCode = $response->getStatusCode();
    $responseBody = json_decode($response->getBody(), true);

    if ($statusCode == 200 && $responseBody['response_code'] == 0) {
        // Message sent successfully
        return true;
    } else {
        // Error sending message
        return false;
    }
}

}
