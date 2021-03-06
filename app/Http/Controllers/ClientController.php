<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

use Illuminate\Http\Request;
use Validator;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        //sort ir direction
        $clients = Client::sortable()->get();
        $companies = Company::all();

        return view("client.index", ['clients'=>$clients, 'companies'=>$companies]);
    }

    public function indexAjax() {

        //1milijonas
        $clients = Client::with('clientCompany')->sortable()->get();

        //foreach $clients
        //clientTitle
        //$client->clientCompany->title;

        $cliens_array = array(
            'clients' => $clients
        );

        $json_response =response()->json($cliens_array); 

        return $json_response;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("client.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = new Client;
        $client->name = $request->client_name;
        $client->surname = $request->client_surname;
        $client->description = $request->client_description;
    
        $client->save();

        return redirect()->route('client.create');
    }

    public function storeAjax(Request $request) {

        
        // $request->validate([
        //     'client_name'=> 'required',
        //     'client_surname'=> 'required',
        //     'client_description'=> 'required',
        //     'client_company_id'=> 'required',
        // ]);
        //funbkcija  mes nenutrauktume
        //grazinti klaidos json_response

        //sitoje vietoje ir nutrauks funkcija
        $input = [
            'client_name'=> $request->client_name,
            'client_surname'=> $request->client_surname,
            'client_description'=> $request->client_description,
            'client_company_id'=> $request->client_description,
        ];

        $rules = [
            'client_name'=> 'required',
            'client_surname'=> 'required',
            'client_description'=> 'required',
            'client_company_id'=> 'required',
        ];

        $customMessages = [
            'required' => "This field is required"
        ];

        
        $validator = Validator::make($input, $rules); // 3 funckijos argumentas neprivalomas

        //tikrina ar validatorius nepraejo
        if($validator->fails()) {

            //zinuciu masyva, kuriose surasyta viskas, kas negerai
            //atvaizduoti zinuciu masyva prie kiekvieno input laukelio
            $errors = $validator->messages()->get('*'); //pasiima visu ivykusiu klaidu sarasa
            $client_array = array(
                'errorMessage' => "validator fails",
                'errors' => $errors
            );
        } else {

            $client = new Client;
            $client->name = $request->client_name;
            $client->surname = $request->client_surname;
            $client->description = $request->client_description;
            $client->company_id = $request->client_company_id;
        
            $client->save();//po isaugojimo momento

            $sort = $request->sort ;
            $direction = $request->direction ;

            $clients = Client::with("clientCompany")->sortable([$sort => $direction ])->get();

            $client_array = array(
                'successMessage' => "Client stored succesfuly",
                'clientId' => $client->id,
                'clientName' => $client->name,
                'clientSurname' => $client->surname,
                'clientDescription' => $client->description,
                'clientCompanyId' => $client->company_id,
                'clientCompanyTitle' => $client->clientCompany->title,
                "clients" => $clients
            );
    }

        $json_response =response()->json($client_array); 
        return $json_response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return view("client.show", ['client' => $client]);
    }

    public function showAjax(Client $client) {
        $client_array = array(
            'successMessage' => "Client retrieved succesfuly",
            'clientId' => $client->id,
            'clientName' => $client->name,
            'clientSurname' => $client->surname,
            'clientDescription' => $client->description,
            'clientCompanyId' => $client->company_id,
            'clientCompanyTitle' => $client->clientCompany->title

        );

        $json_response =response()->json($client_array); 

        return $json_response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClientRequest  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    public function updateAjax(Request $request, Client $client)
    {
        $client->name = $request->client_name;
        $client->surname = $request->client_surname;
        $client->description = $request->client_description;
        $client->company_id = $request->client_company_id;

        $client->save();

        $client_array = array(
            'successMessage' => "Client updated succesfuly",
            'clientId' => $client->id,
            'clientName' => $client->name,
            'clientSurname' => $client->surname,
            'clientDescription' => $client->description,
            'clientCompanyId' => $client->company_id,
            'clientCompanyTitle' => $client->clientCompany->title
        );

        // 
        $json_response =response()->json($client_array); //javascript masyva

        return $json_response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route("client.index");
    }

    public function destroyAjax(Client $client)
    {
        $client->delete();

        $success_array = array(
            'successMessage' => "Client deleted successfuly". $client->id,
        );

        // 
        $json_response =response()->json($success_array);

        return $json_response;
    }

    public function searchAjax(Request $request) {

        $searchValue = $request->searchValue;

        $clients = Client::query()
        ->where('name', 'like', "%{$searchValue}%")
        ->orWhere('surname', 'like', "%{$searchValue}%")
        ->orWhere('description', 'like', "%{$searchValue}%")
        ->get();

        if(count($clients) > 0) {
            $cliens_array = array(
                'clients' => $clients
            );
        } else {
            $cliens_array = array(
                'errorMessage' => 'No clients found'
            );
        }

        

        $json_response =response()->json($cliens_array);

        return $json_response;

    }
}