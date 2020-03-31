<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryPostRequest;
use App\Repositories\Category\CategoryRepository as Category;

class CategoryController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $category;
    
    public function __construct(Category $category)
    {
        $this->middleware('auth');
        $this->category = $category;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $result = $this->category->all(); 
         return view('category.index', ['data' => $result]);
    }
    public function loadModal(Request $request, $action, $id)
    {
        if($action == 'create_category'){  
           return view('category.modal_create');        
       }
       if($action == 'update_category'){
           $results = $this->category->find($id);           
           return view('category.modal_update', ['data' => $results]);
       }
    }
    public function create(CategoryPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $id = $post_data['id'];
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->category->update($post_data, $id);
            }else{
                $response = $this->category->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Category created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Category updated successfully.';
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            //echo "<pre>"; print_r($response); die;
            echo json_encode($resp);   
            //echo \Response::json($response);
            die;
          }
    }    
}
