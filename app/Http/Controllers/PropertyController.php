<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\Listing\ListingRepository as Listing;
use App\Repositories\ListingVideo\ListingVideoRepository as ListingVideo;
use App\Repositories\ListingRequest\ListingRequestRepository as ListingRequest;
use App\Repositories\Category\CategoryRepository as Category;
use App\Repositories\User\User1Repository as User;
use Jenssegers\Agent\Agent;
use Helper;

class PropertyController extends Controller
{
    private $listing;
    private $listingvideo;
    private $listingrequest;
    private $category;
    private $user;
    public function __construct(Listing $listing, ListingVideo $listingvideo, ListingRequest $listingrequest, Category $category, User $user)
    {
        $this->listing = $listing;
        $this->listingvideo = $listingvideo;
        $this->listingrequest = $listingrequest;
        $this->category = $category;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Show the all properties
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->isMethod('post'))
            $post = $request->all();
        else
            $post = [];

        $data = [];
        
        if(isset($post['search_keyword']) && !empty($post['search_keyword']))
            $data['search_keyword'] = $post['search_keyword'];
        else
            $data['search_keyword'] = '';
        
        if(isset($post['min_price']) && !empty($post['min_price']))
            $data['min_price'] = $post['min_price'];
        else
            $data['min_price'] = '';
        
        if(isset($post['max_price']) && !empty($post['max_price']))
            $data['max_price'] = $post['max_price'];
        else
            $data['max_price'] = '';
        
        if(isset($post['bedrooms']) && !empty($post['bedrooms']))
            $data['bedrooms'] = $post['bedrooms'];
        else
            $data['bedrooms'] = '';
        
        if(isset($post['sort']) && !empty($post['sort']))
            $data['sort'] = $post['sort'];
        else
            $data['sort'] = 'NAME_A_Z';
        
        $properties = $this->listing->getProperties($data);
        
        $data['total'] = $properties['total'];
        $data['per_page'] = $properties['per_page'];
        $data['current_page'] = $properties['current_page'];
        $data['from'] = $properties['from'];
        $data['to'] = $properties['to'];
        $data['showing'] = $properties['to'];
        $data['remaining'] = $data['total'] - $data['showing'];

        if ($request->ajax()) {
            if(count($properties['data']) > 0){
                $data['grid'] = view('properties.gridview', ['properties' => $properties['data']])->render();
                $data['list'] = view('properties.listview', ['properties' => $properties['data']])->render();
                
                echo json_encode ($data);
            }else{
                echo json_encode ('norecords');
            }
        } else {
            $data['properties'] = $properties['data'];

            return view('properties.properties', $data);
        }
    }
    
    public function property($property_id, $name, Request $request)
    {
        $data = $property = $categories = $related_properties = [];
        $listing = clone $this->listing;
        $property = $this->listing->getProperty($property_id);
        $this->listing = $listing;
//echo "<pre>"; print_r($property); die;
        if($property){
            $categories = [];
            if(!empty($property['listing_categories'])){
                foreach($property['listing_categories'] as $category){
                    $category_name = $this->getCategoryDetail($category['category_id']);
                    if($category_name){
                        $categories[] = $category_name;
                    }
                }
            }
            
            
            $related_properties = $this->listing
                    ->with(['country', 'state', 'listingDefaultImage', 'destination', 'listingImages', 'uploaderInfo'])
                    ->findWhere([['user_id', '=', $property['user_id']], ['id', '!=', $property['id']], ['active', '=', 1]]);
        }
        
        $data['property'] = $property;
        $data['related_properties'] = ($related_properties) ? $related_properties->toArray() : $related_properties;
        $data['property']['categories'] = implode(', ', $categories);
        $data['previous'] = $this->listing->getPreviousProperty($property_id);
        $data['next'] = $this->listing->getNextProperty($property_id);
        $data['agent'] = new Agent();
       // var_dump($data['agent']->isDesktop()); die;
        return view('properties.property', $data);
    }
    
    function getCategoryDetail($category_id)
    {
        $category = $this->category->find($category_id)->toArray();
        
        return ($category) ? $category['name'] : FALSE;
    }
    
    public function showVideo($videoid)
    {
        $data = [];
        $video = $this->listingvideo->find($videoid);
        $data['video'] = $video;

        if($video->video_format == "YouTube"){
            $youtubevideoid = Helper::youtubeVideoId($video->video_link);
            $data['videotitle'] = $video->video_title;
            $data['videohtml'] = Helper::convertYoutubeVideo($youtubevideoid);
        }elseif($video->video_format == "Vimeo"){
            $link = $video->video_link;
            $data['videotitle'] = $video->video_title;
            $data['videohtml'] = Helper::vimeoVideoDetails($video->video_link, 'html');
        }

        return view('common.video', $data);
    }
    
    public function requestInformation(Request $request)
    {
        $data = $post = []; $json = TRUE;

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;
        
        if($json){
            $date = explode('/', $post['date']);
            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
            $datetime = $date. ' ' . $post['time'] . ':00';
            $request_information = array(
                'listing_id' => Helper::cleanQuery($post['propertyid']),
                'first_name' => Helper::cleanQuery($post['first_name']),
                'last_name' => Helper::cleanQuery($post['last_name']),
                'email_address' => Helper::cleanQuery($post['email_address']),
                'phone' => Helper::cleanQuery($post['phone']),
                'day_time' => Helper::cleanQuery($datetime),
                'message' => Helper::cleanQuery($post['message'])
            );
            
            $this->listingrequest->create($request_information);
            
            $property = $this->listing->getProperty(Helper::cleanQuery($post['propertyid']));

            $request_information['day'] = $date;
            $request_information['time'] = date("h:ia", strtotime($post['time'])) . ' PST';
            if (isset($property['uploader_info']) && !empty($property['uploader_info'])) {
                $mail['email'] = Helper::cleanQuery($property['uploader_info']['email']);
                $mail['property_title'] = Helper::cleanQuery($property['name']);
                $mail['first_name'] = Helper::cleanQuery($property['uploader_info']['first_name']);
                $mail['last_name'] = Helper::cleanQuery($property['uploader_info']['last_name']);
                $mail['client'] = $request_information;

                $mail['to'] = $mail['email'];
                $mail['subject'] = "Property Information Request - " . Helper::cleanQuery($property['name']);

                Helper::sendEmail($mail, 'property_request');
            }
        }
    }
}
