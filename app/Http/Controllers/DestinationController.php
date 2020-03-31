<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\DestinationPostRequest;
use App\Repositories\Destination\DestinationRepository as Destination;
use App\Repositories\Country\CountryRepository as Country;
use App\Repositories\DestinationImage\DestinationImageRepository as DestinationImage;
use App\Repositories\DestinationVideo\DestinationVideoRepository as DestinationVideo;
use App\Repositories\DestinationFile\DestinationFileRepository as DestinationFile;
use App\Repositories\Listing\ListingRepository as Listing;

class DestinationController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $destination;
    private $country;
    private $destinationImage;
    private $destinationVideo;
    private $destinationFile;
    private $listing;

    public function __construct(Destination $department, Country $country, DestinationImage $destinationImage, DestinationVideo $destinationVideo, DestinationFile $destinationFile, Listing $listing) {
        $this->middleware('auth', ['except' => ['listing', 'destination']]);
        $this->destination = $department;
        $this->country = $country;
        $this->destinationImage = $destinationImage;
        $this->destinationVideo = $destinationVideo;
        $this->destinationFile = $destinationFile;
        $this->listing = $listing;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $result = $this->destination->with(['country', 'state'])->all();
        //echo "<pre>"; print_r($result); die;
        return view('destination.index', ['data' => $result]);
    }

    public function add(Request $request) {
        //$request->session()->forget('destination_images');
        $data['countries'] = $this->country->all();
        $data['images'] = $request->session()->get('destination_images');
        $data['video'] = $request->session()->get('destination_videos_details');
        $data['file'] = $request->session()->get('destination_file');
        //echo "<pre>"; print_r($data['video']); die;
        return view('destination.add', ['data' => $data]);
    }

    public function edit($id) {
        $data['countries'] = $this->country->all();
        $data['destination'] = $this->destination->with(['destinationImages', 'destinationVideos', 'destinationFiles'])->find($id);
        //echo "<pre>"; print_r($data['destination']); die;
        $countryId = $data['destination']->country_id;
        $data['states'] = $this->country->with(['states'])->find($countryId);
        return view('destination.edit', ['data' => $data]);
    }

    /* public function loadModal(Request $request, $action, $id){
      if($action == 'create_department'){
      return view('department.modal_create');
      }
      if($action == 'update_department'){
      $results = $this->destination->find($id);
      return view('department.modal_update', ['data' => $results]);
      }
      } */

    public function create(DestinationPostRequest $request) {
        if ($request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $imgArr = $request->session()->pull('destination_images');
            $videosArr = $request->session()->pull('destination_videos_details');
            $filesArr = $request->session()->pull('destination_file');
            $data = $request->session()->all();
            $default_image_from = isset($post_data['default_image_from']) ? $post_data['default_image_from'] : '';
            //if(isset($imgArr) && !empty($imgArr)){
                if(isset($post_data['default_image']) && !empty($post_data['default_image'])){
                    $defultImage = $post_data['default_image'];
                }else{
                    $defultImage = isset($imgArr) && !empty($imgArr)? array_keys($imgArr)[0] : 0;
                }
            //}
            //echo "<pre>11"; print_r($post_data); print_r($imgArr);echo "</pre>".$defultImage; die;
            //$defultImage = isset($post_data['default_image']) ? $post_data['default_image'] : '';
            unset($post_data['default_image']);
            unset($post_data['default_image_from']);
            $id = $post_data['id'];

            if (isset($id) && !empty($id)) {
                unset($post_data['_token']);
                $dImgs = array('is_default' => 0);
                $dImgsRes1 = $this->destinationImage->update($dImgs, $id, 'destination_id');
                $response = $this->destination->update($post_data, $id);
                if ($default_image_from == 'db') {
                    $dImgs2 = array('is_default' => 1);
                    $dImgsRes2 = $this->destinationImage->update($dImgs2, $defultImage);
                }
                //$defImageData = $this->destinationImage->find($defultImage);
                $lastInsertId = $id;
            } else {
                $response = $this->destination->create($post_data);
                $lastInsertId = $response->id;
            }
            //var_dump($response, $response->id); die;
            if ($lastInsertId) {
                if (!empty($imgArr)) {
                    foreach ($imgArr as $k => $v) {
                        $isDefault = isset($defultImage) && $k == $defultImage ? 1 : 0;
                        $dImgs = array('destination_id' => $lastInsertId, 'image_path' => $v, 'is_default' => $isDefault);
                        $dImgsRes = $this->destinationImage->create($dImgs);
                    }
                }
                if (!empty($videosArr)) {
                    foreach ($videosArr as $k => $v) {
                        $dImgs = array('destination_id' => $lastInsertId,
                            'video_format' => $v['video_format'],
                            'video_title' => $v['video_title'],
                            'video_description' => $v['video_description'],
                            'video_link' => $v['video_link']);
                        $dImgsRes = $this->destinationVideo->create($dImgs);
                    }
                }
                if (!empty($filesArr)) {
                    foreach ($filesArr as $k => $v) {
                        $dImgs = array('destination_id' => $lastInsertId,
                            'file_name' => $v['original_file_name'],
                            'file_path' => $v['filename']);
                        $dImgsRes = $this->destinationFile->create($dImgs);
                    }
                }
                $resp['status'] = 1;
                $resp['msg'] = 'Destination created successfully.';
            } else if ($response) {
                $resp['status'] = 1;
                $resp['msg'] = 'Destination updated successfully.';
            } else {
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            return redirect('/destinations')->with('status', $resp['msg']);
            //echo "<pre>"; print_r($response); die;
            //echo json_encode($resp);   
            //echo \Response::json($response);
            //die;
        }
    }

    public function deletedata(Request $request, $type, $id) {
        $moduleConfig = config('constants.destination');
        $response = array();
        switch ($type) {
            case 'delete_all_images':
                    $fullPathKey = 'destination_full_path';
                if($id){
                    $allImages = $this->destinationImage->findAllBy('destination_id',$id)->toArray(); 
                    //print_r($allImages); die;
                    if(!empty($allImages)){
                        foreach($allImages as $image){
                            $deleted = $image['image_path'];
                            $deletedImgArr = explode('/', $deleted);
                            $filenamewithextension = end($deletedImgArr);
                            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                            $filenameExt = pathinfo($filenamewithextension, PATHINFO_EXTENSION);
                            $sizes = array_keys($moduleConfig['sizes']);
                            $delFile = $moduleConfig[$fullPathKey].$filenamewithextension;
                            @unlink($delFile);
                            //remove all size images
                            foreach($sizes as $val){
                                $delFile = $moduleConfig[$fullPathKey].$filename.'_'.$val.'.'.$filenameExt;
                                @unlink($delFile);
                            }
                            $this->destinationImage->delete($image['id']);
                        }
                    }
                }
                    
                    $imgArr = $request->session()->pull('destination_images');
                    if(!empty($imgArr)){
                        foreach($imgArr as $k=>$v){
                            $deleted = $v;
                            $deletedImgArr = explode('/', $deleted);
                            $filenamewithextension = end($deletedImgArr);
                            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                            $filenameExt = pathinfo($filenamewithextension, PATHINFO_EXTENSION);
                            $sizes = array_keys($moduleConfig['sizes']);
                            $delFile = $moduleConfig[$fullPathKey].$filenamewithextension;
                            @unlink($delFile);
                            //remove all size images
                            foreach($sizes as $val){
                                $delFile = $moduleConfig[$fullPathKey].$filename.'_'.$val.'.'.$filenameExt;
                                @unlink($delFile);
                            }
                        }
                    }
                    $response['status'] = true;
                break;
            case 'delete_image':
                $fullPathKey = 'destination_full_path';
                $image = $this->destinationImage->find($id);
                $deleted = $image->image_path;
                $deletedImgArr = explode('/', $deleted);
                $filenamewithextension = end($deletedImgArr);
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $filenameExt = pathinfo($filenamewithextension, PATHINFO_EXTENSION);
                $sizes = array_keys($moduleConfig['sizes']);
                $delFile = $moduleConfig[$fullPathKey] . $filenamewithextension;
                @unlink($delFile);
                //remove all size images
                foreach ($sizes as $val) {
                    $delFile = $moduleConfig[$fullPathKey] . $filename . '_' . $val . '.' . $filenameExt;
                    @unlink($delFile);
                }
                $this->destinationImage->delete($id);
                $response['status'] = true;
                //echo "<pre>"; print_r($image->is_default); die;
                break;
            case 'delete_vedio':
                $this->destinationVideo->delete($id);
                $response['status'] = true;
                break;
            case 'delete_file':
                $fullPathKey = 'destination_file_full_path';
                $file = $this->destinationFile->find($id);
                $deleted = $file->file_path;
                $deletedImgArr = explode('/', $deleted);
                $filenamewithextension = end($deletedImgArr);
                $delFile = $moduleConfig[$fullPathKey] . $filenamewithextension;
                @unlink($delFile);
                $this->destinationFile->delete($id);
                $response['status'] = true;
                break;
        }
        echo json_encode($response);
    }

    public function listing(Request $request) {
        if ($request->isMethod('post'))
            $post = $request->all();
        else
            $post = [];

        $data = [];

        if (isset($post['sort']) && !empty($post['sort']))
            $data['sort'] = $post['sort'];
        else
            $data['sort'] = 'NAME_A_Z';

        $destinations = $this->destination->getDestination($data);

        $data['total'] = $destinations['total'];
        $data['per_page'] = $destinations['per_page'];
        $data['current_page'] = $destinations['current_page'];
        $data['from'] = $destinations['from'];
        $data['to'] = $destinations['to'];
        $data['showing'] = $destinations['to'];
        $data['remaining'] = $data['total'] - $data['showing'];

        if ($request->ajax()) {
            if (count($destinations['data']) > 0) {
                $data['grid'] = view('destination.gridview', ['destinations' => $destinations['data']])->render();

                echo json_encode($data);
            } else {
                echo json_encode('norecords');
            }
        } else {
            $data['destinations'] = $destinations['data'];

            return view('destination.destinations', $data);
        }
    }

    public function destination($destination_id, $name, Request $request) {
        $data = $destination = $properties = [];

        $destination = $this->destination->with(['destinationImages', 'destinationAgents'])->find($destination_id);

        if ($destination) {
            $destination = $destination->toArray();
            
            $properties = $this->listing->with(['country', 'state', 'destination', 'listingDefaultImage'])->findAllBy('destination_id', $destination['id'])->toArray();
        }

        $data['destination'] = $destination;
        $data['properties'] = $properties;

        return view('destination.destination', $data);
    }

}
