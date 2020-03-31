<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\Country\CountryRepository as Country;

class CountryController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $country;
    public function __construct(Country $country)
    {
        $this->middleware('auth', ['except' => ['getStates']]);
        $this->country = $country;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStates($id)
    {
         $result = $this->country->with(['states'])->find($id); 
         $stateList = '<option value="">Select State</option>';
         if(!empty($result->states)){
             foreach($result->states as $state){
                 $stateList .= '<option value="'.$state->id.'">'.$state->state_name.'</option>';
             }
         }
         return $stateList;
         //return response()->json($result);
         //echo "<pre>"; print_r($result->states); die;
         //return view('destination.index', ['data' => $result]);
    }
}
