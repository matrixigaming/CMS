<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\User\UserRepository as User;
use App\Repositories\UserSocialMedia\UserSocialMediaRepository as UserSocialMedia;
use App\Repositories\UserNewsletter\UserNewsletterRepository as UserNewsletter;
use App\Repositories\Destination\DestinationRepository as Destination;
use DB;
use Helper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $user;
    private $usersocialmedia;
    private $usernewsletter;
    private $destination;
    public function __construct(User $user, UserSocialMedia $usersocialmedia, UserNewsletter $usernewsletter, Destination $destination)
    {
        //$this->middleware('auth');
        $this->user = $user;
        $this->usersocialmedia = $usersocialmedia;
        $this->usernewsletter = $usernewsletter;
        $this->destination = $destination;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bannerListings = DB::table('listings')
                ->select('listings.*', 'destinations.name as destination_name', 'states.state_name')
                ->leftJoin('destinations', 'listings.destination_id', '=', 'destinations.id')
                ->leftJoin('states', 'listings.state_id', '=', 'states.id')
                //->leftJoin('listing_images', 'listings.id', '=', 'listing_images.listing_id')
                //->select('listings.*', 'listing_images.image_path','listing_images.is_default')
                ->where('listings.active', 1)
                ->where('listings.banner_homepage', 1)
                ->latest()
                ->limit(10)
                ->get();
        //echo "<pre>"; print_r($bannerListings); echo "</pre>"; die;
        if(!empty($bannerListings)){
            foreach($bannerListings as $list){
                $listImages = $this->_getListingDefaultImages($list->id);
                $list->listImages = $listImages;
            }
        }
        $featuredListing = DB::table('listings')
                ->select('listings.*', 'destinations.name as destination_name', 'states.state_name')
                ->leftJoin('destinations', 'listings.destination_id', '=', 'destinations.id')
                ->leftJoin('states', 'listings.state_id', '=', 'states.id')
                ->where('listings.active', 1)
                ->where('listings.featured', 1)
                ->latest()
                ->limit(5)
                ->get();
        if(!empty($featuredListing)){
            foreach($featuredListing as $list){
                $listImages = $this->_getListingDefaultImages($list->id);
                $list->listImages = $listImages;
            }
        }
        
        $waterFrontListing = DB::table('listings')
                ->select('listings.*', 'destinations.name as destination_name', 'states.state_name')
                ->leftJoin('destinations', 'listings.destination_id', '=', 'destinations.id')
                ->leftJoin('states', 'listings.state_id', '=', 'states.id')
                ->leftJoin('listing_categories', 'listings.id', '=', 'listing_categories.listing_id')
                ->where('listings.active', 1)
                ->where('listing_categories.category_id', 1)
                ->latest()
                ->limit(5)
                ->get();
        if(!empty($waterFrontListing)){
            foreach($waterFrontListing as $list){
                $listImages = $this->_getListingDefaultImages($list->id);
                $list->listImages = $listImages;
            }
        }
        
        $HistoricListing = DB::table('listings')
                ->select('listings.*', 'destinations.name as destination_name', 'states.state_name')
                ->leftJoin('destinations', 'listings.destination_id', '=', 'destinations.id')
                ->leftJoin('states', 'listings.state_id', '=', 'states.id')
                ->leftJoin('listing_categories', 'listings.id', '=', 'listing_categories.listing_id')
                ->where('listings.active', 1)
                ->where('listing_categories.category_id', 4)
                ->latest()
                ->limit(5)
                ->get();
        
        if(!empty($HistoricListing)){
            foreach($HistoricListing as $list){
                $listImages = $this->_getListingDefaultImages($list->id);
                $list->listImages = $listImages;
            }
        }
        $params['sort'] = 'RECENT';
        $featuredAgents = $this->user->getAgentListing($params, 1);
        if(isset($featuredAgents['data']) && !empty($featuredAgents['data'])){
            foreach($featuredAgents['data'] as $index => $agent){
                $featuredAgents['data'][$index]->social = $this->usersocialmedia->findWhere(['user_id' => $agent->id]);
            }
        }
        $homePageData['featuredAgents'] = $featuredAgents;
        $homePageData['featuredDestinations'] = $this->destination->getDestination($params, 1);
        $homePageData['bannerListings'] = $bannerListings;
        $homePageData['featuredListing'] = $featuredListing;
        $homePageData['waterFrontListing'] = $waterFrontListing;
        $homePageData['historicListing'] = $HistoricListing;

        return view('welcome', ['data' => $homePageData]);
    }
    
    protected function _getListingDefaultImages($listingId){
        $listingImages = DB::table('listings')                
                ->leftJoin('listing_images', 'listings.id', '=', 'listing_images.listing_id')
                ->select('listing_images.image_path','listing_images.is_default')
                ->where('listing_images.is_default', 1)
                ->where('listings.id', $listingId)
                ->first();
        return $listingImages;
    }
    
    public function newsletter(Request $request)
    {
        $post = []; $json = TRUE;

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        if($json){
            $checkNewsletterEmailExists = $this->usernewsletter->findBy('email_address', $post['email_address']);

            if (empty($checkNewsletterEmailExists)) {
                $request = array(
                    "name" => Helper::cleanQuery(strtolower($post['name'])),
                    "email_address" => Helper::cleanQuery(strtolower($post['email_address'])),
                    "active" => 1
                );

                $this->usernewsletter->create($request);

                $mail['subject'] = "Luxury Real Estate Search - New Email Subscriptiont";
                $mail['client'] = $request;

                Helper::sendEmail($mail, 'newsletter_request');

                echo 'You have been successfully subscribed to our newsletter.';
            } else {
                $checkNewsletterEmailExists = $checkNewsletterEmailExists->toArray();
                
                if($checkNewsletterEmailExists['active'] == 1){
                    echo 'The email address provided already exists.';
                }else{
                    $this->usernewsletter->update(['active' => 1], $checkNewsletterEmailExists['id']);
                    
                    echo 'You have been successfully subscribed to our newsletter.';
                }
            }
        }
    }
}
