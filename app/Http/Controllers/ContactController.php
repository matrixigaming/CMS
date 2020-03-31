<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\Contact\ContactRepository as Contact;

class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $contact;
    public function __construct(Contact $contact)
    {
        $this->middleware('auth');
        $this->contact = $contact;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $result = $this->contact->all(); 
         //echo "<pre>"; print_r($result); echo "</pre>";
         return view('contact.index', ['data' => $result]);        
    }
    public function loadModal(Request $request, $action, $id)
    {

       if($action == 'view_contact'){
           $results = $this->contact->find($id);          
           return view('contact.modal_view', ['data' => $results]);
       }
    }
    
}
