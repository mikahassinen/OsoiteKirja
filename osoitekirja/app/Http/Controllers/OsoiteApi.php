<?php

namespace App\Http\Controllers;

use App\Person;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;


class OsoiteApi extends Controller
{
    public function hae(Request $request)
    {
        $results = DB::table('location')
            ->select('location.*')
            ->get();
        //return EventDate(array('Name'));

        return array($results);

    }

    public function createPerson(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|alpha',
            'lastname' => 'required|alpha',
            'postalCode' => 'required|digits:5',
            'city' => 'required|alpha',
            'country' => 'required|alpha',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $failedMessage = $validator->messages();
            return array($failedMessage);
        }

        //request to json
        $data = $request->json()->all();

        //Person
        $first_name = $data['firstname'];
        $last_name = $data['lastname'];

        //Location
        $street_address = $data['street'];
        $street_number = $data['streetNumber'];
        $city = $data['city'];
        $zip = $data['postalCode'];
        $country = $data['country'];
        $latitude = $data['lat'];
        $longitude = $data['lon'];

        $locationResult = DB::table('location')
            ->where('street_address', '=', $street_address)
            ->where('street_number', '=', $street_number)
            ->where('zip', '=', $zip)
            ->exists();
        //$locationResult = DB::table('location')->select('*')
        //->get();
        //return response()->json($data);
        if (!$locationResult) {
            $location_id = DB::table('location')
                ->insertGetId(array('street_address' => $street_address
                , 'street_number' => $street_number
                , 'city' => $city
                , 'zip' => $zip
                , 'country' => $country
                , 'latitude' => $latitude
                , 'longitude' => $longitude));
            $person_id = DB::table('person')
                ->insertGetId(array('first_name' => $first_name
                , 'last_name' => $last_name
                , 'location_Location_id' => $location_id));
            //return response()->json($data);
        } else {
            $locationR = DB::table('location')
                ->where('street_address', '=', $street_address)
                ->where('street_number', '=', $street_number)
                ->where('zip', '=', $zip)
                ->get();
            $person_id = DB::table('person')
                ->insertGetId(array('first_name' => $first_name
                , 'last_name' => $last_name
                , 'location_Location_id' => $locationR));
        }
        return response()->json($data);


        /* DB::table('Location')
             ->insert(['Location_id' => $location_id,
                 'Location_name' => $location_name,
                 'Street_address' => $street_address,
                 'City' => $city,
                 'Zip' => $zip,
                 'Country' => $country,
             ]);

         DB::table('Event')
             ->insert(['Event_id' => $event_id,
                 'Name' => $name,
                 'Type' => $type,
                 'Location_Location_id' => $location_id]);
         //return array($results);
         //return Response::json($results);
         return response()->json($data); //send json respond*/
    }

    public function getPersonsByZip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'zip' => 'required|digits:5',
        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $failedMessage = $validator->messages();
            return array($failedMessage);
            //return redirect('api/persons/zip')
            //    ->withErrors($failed)
            //    ->withInput();
        }

        $zip = $request->input('zip');
        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('location.zip', '=', $zip)
            ->select('person.*', 'location.*')
            ->get();

        return $results->toJson();
    }

    public function getPersonsByfirstName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|alpha',
        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $failedMessage = $validator->messages();
            return array($failedMessage);
            //return redirect('api/persons/zip')
            //    ->withErrors($failed)
            //    ->withInput();
        }

        $fn = $request->input('firstname');
        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('person.first_name', '=', $fn)
            ->select('person.*', 'location.*')
            ->get();

        return $results->toJson();
    }

    public function getPersonsByLastName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lastname' => 'required|alpha',
        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $failedMessage = $validator->messages();
            return array($failedMessage);
            //return redirect('api/persons/zip')
            //    ->withErrors($failed)
            //    ->withInput();
        }

        $ln = $request->input('lastname');
        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('person.last_name', '=', $ln)
            ->select('person.*', 'location.*')
            ->get();

        return $results->toJson();
    }

    public function getPersonsByfirstNameAndLastName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|alpha',
            'lastname' => 'required|alpha',
        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $failedMessage = $validator->messages();
            return array($failedMessage);
            //return redirect('api/persons/zip')
            //    ->withErrors($failed)
            //    ->withInput();
        }

        $fan = $request->input('firstname');
        $lan = $request->input('lastname');
        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('person.first_name', '=', $fan)
            ->where('person.last_name', '=', $lan)
            ->select('person.*', 'location.*')
            ->get();

        return $results->toJson();
    }
    public function getPersonsByfirstNameAndLastNameAndZip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|alpha',
            'lastname' => 'required|alpha',
            'zip' => 'required|digits:5',
        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $failedMessage = $validator->messages();
            return array($failedMessage);
            //return redirect('api/persons/zip')
            //    ->withErrors($failed)
            //    ->withInput();
        }

        $fan = $request->input('firstname');
        $lan = $request->input('lastname');
        $zip = $request->input('zip');
        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('person.first_name', '=', $fan)
            ->where('person.last_name', '=', $lan)
            ->where('location.zip', '=', $zip)
            ->select('person.*', 'location.*')
            ->get();

        return $results->toJson();
    }

    public function getPersonsByAnyName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anyname' => 'required|alpha',

        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $failedMessage = $validator->messages();
            return array($failedMessage);
            //return redirect('api/persons/zip')
            //    ->withErrors($failed)
            //    ->withInput();
        }

        $any = $request->input('anyname');
        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('person.first_name', '=', $any)->exists();
        if ($results) {
            $resultsFirst = DB::table('person')
                ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
                ->where('person.first_name', '=', $any)
                ->select('person.*', 'location.*')
                ->get();
            return $resultsFirst->toJson();
        }
        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('person.last_name', '=', $any)->exists();
        if ($results) {
            $resultsLast = DB::table('person')
                ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
                ->where('person.last_name', '=', $any)
                ->select('person.*', 'location.*')
                ->get();
            return $resultsLast->toJson();
        }


        return array($results);
    }
    public function getPersonsByAnyNameAndZip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anyname' => 'required|alpha',
            'zip' => 'required|digits:5',
        ]);

        if ($validator->fails()) {
            $failed = $validator->failed();
            $failedMessage = $validator->messages();
            return array($failedMessage);
            //return redirect('api/persons/zip')
            //    ->withErrors($failed)
            //    ->withInput();
        }

        $any = $request->input('anyname');
        $zip=$request->input('zip');


        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('person.first_name', '=', $any)
            ->where('location.zip', '=', $zip)->exists();
        if ($results) {
            $resultsFirst = DB::table('person')
                ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
                ->where('person.first_name', '=', $any)
                ->where('location.zip', '=', $zip)
                ->select('person.*', 'location.*')
                ->get();
            return $resultsFirst->toJson();
        }
        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            //->join('event_date', 'event_date.Event_id', '=', 'event.Event_id')
            ->where('person.last_name', '=', $any)
            ->where('location.zip', '=', $zip)->exists();
        if ($results) {
            $resultsLast = DB::table('person')
                ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
                ->where('person.last_name', '=', $any)
                ->where('location.zip', '=', $zip)
                ->select('person.*', 'location.*')
                ->get();
            return $resultsLast->toJson();
        }


        //return array($results);
    }

    public function getAllPersons(Request $request)
    {

        $results = DB::table('person')
            ->join('location', 'location.Location_id', '=', 'person.Location_Location_id')
            ->select('person.*', 'location.*')
            ->orderBy('person.last_name')
            ->get();
        return $results->toJson();
    }
}


