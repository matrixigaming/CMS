<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DOMDocument;

class SocialController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the all social activities
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr_google = $arr_twitter = $arr_twitter = $arr_insta = $arr_facebook = array();
        
        $googleplusurl = "https://www.googleapis.com/plus/v1/people/116506268876026329839/activities/public?key=AIzaSyBnMAuT7tRLVNqEwOdweYyf3sEbZoV701w&maxResults=100";
        $googleplus = curl_init($googleplusurl);
        curl_setopt($googleplus, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($googleplus, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($googleplus, CURLOPT_SSL_VERIFYPEER, FALSE);
        $googlepluscontents = curl_exec($googleplus);
        curl_close($googleplus);        
        $googles = json_decode($googlepluscontents, TRUE);
        
        foreach ($googles['items'] as $google) {
            if(isset($google['object']['attachments'][0]['thumbnails']) && !empty($google['object']['attachments'][0]['thumbnails'])){
                $image = $google['object']['attachments'][0]['thumbnails'][0]['image']['url'];
            }elseif(isset($google['object']['attachments'][0]['image']) && !empty($google['object']['attachments'][0]['image'])){
                $image = $google['object']['attachments'][0]['image']['url'];
            }else{
                $image = '';
            }
            
            $arr_google[] = array(
                "date" => date('Y-m-d', strtotime($google['published'])),
                "social" => "googleplus",
                "title" => $google['title'],
                "link" => '',
                "image" => $image,
                "image_link" => $google['url'],
                "link_name" => '',
                "likes" => '',
                "comments" => '',
            );
        }

        $fetchData = @file_get_contents("http://www.luxuryrealestatesearch.com/tweetledee/userjson_pp.php?c=200&tweet_mode=extended"); // set limit to pull data where c = no/- of counts max 200 limits allowed
//echo "<pre>"; print_r($fetchData); echo "</pre>"; die;
        $twitterData = json_decode($fetchData, TRUE);

        $k = 0;
        if ($twitterData) {
            foreach ($twitterData as $data) {
                $datetime = explode(" ", $data['created_at']);
                $month = date('m', strtotime($datetime[1]));
                $date = $datetime[2];
                $year = $datetime[5];
                $date = $year . "-" . $month . "-" . $date;
                if (isset($data['entities']['media'])) {
                    $image_link = $data['entities']['media'][0]['url'];
                    $image = $data['entities']['media'][0]['media_url'];
                } else {
                    $image_link = "";
                    $image = "";
                }
                $noimage_link = isset($data['entities']['urls'][0]) ? $data['entities']['urls'][0]['url'] : '';
                $textdesc = explode(" http://", $data['full_text']);
                $text = $textdesc[0];
                if (!empty($textdesc[1])) {
                    $link = explode(" ", $textdesc[1]);
                    $link = $link[0];
                } else {
                    $link = "";
                }
                $arr_twitter[] = array(
                    "date" => $date,
                    "social" => "twitter",
                    "title" => $text,
                    "link" => $link,
                    "image" => $image,
                    "image_link" => $image_link,
                    "link_name" => "",
                    "noimage_link" => $noimage_link,
                    "likes" => '',
                    "comments" => '',
                );
            }
        }

        /* instagram */
        $client_id = "7a3802d4d380429c9ce3f5b653cd5bc0";
        $access_token = "4183722282.a4b8633.0f8a9ce48e024f4c909520227bb3c1be";
        $instagramurl = "https://api.instagram.com/v1/users/4183722282/media/recent/?access_token=4183722282.a4b8633.0f8a9ce48e024f4c909520227bb3c1be";

        $instach = curl_init($instagramurl);
        curl_setopt($instach, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($instach, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($instach, CURLOPT_SSL_VERIFYPEER, FALSE);
        $instacontents = curl_exec($instach);
        curl_close($instach);

        $instaData = json_decode($instacontents, TRUE);

        if ($instaData['data']) {
            foreach ($instaData['data'] as $insta) {
                $likes = $insta['likes']['count'];
                $comments = $insta['comments']['count'];
                $link = "http://instagram.com/" . $insta['caption']['from']['username'];
                $arr_insta[] = array(
                    "date" => date('Y-m-d', $insta['caption']['created_time']),
                    "social" => "instagram",
                    "title" => $insta['caption']['text'],
                    "link" => $link,
                    "image" => $insta['images']['low_resolution']['url'],
                    "image_link" => $insta['link'],
                    "link_name" => $insta['caption']['from']['full_name'],
                    "likes" => $likes,
                    "comments" => $comments
                );
            }
        }

        $FBid = '985472211562337';
        $acces_token = '488128384644785|sagI-79V2eD1U-lhx-j5M-DA4uc ';
        $FBUrl = "https://graph.facebook.com/" . $FBid . "/feed?access_token=" . trim($acces_token) . "&fields=id,message,picture,shares,created_time,link";
        $ch = curl_init($FBUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $contents = curl_exec($ch);
        curl_close($ch);
        $fbdata = json_decode($contents, TRUE);

        if ($fbdata['data']) {
            foreach ($fbdata['data'] as $fb) {
                if (!isset($fb['message'])) { continue; }
                
                    if ($fb['picture']) {
                        $image = str_replace("s.jpg", "n.jpg", $fb['picture']);
                        $image_link = $fb['link'];
                    } else {
                        $image = "";
                        $image_link = "";
                    }
                    $link = $fb['link'];
                    $link_name = '';
                    $datetime = explode("T", $fb['created_time']);
                    $date = $datetime[0];
                    $arr_facebook[] = array("date" => $date,
                        "social" => "facebook",
                        "title" => $fb['message'],
                        "link" => $link,
                        "image" => $image,
                        "image_link" => $image_link,
                        "link_name" => $link_name,
                        "likes" => '',
                        "comments" => '',
                    );
                
            }
        }

        $rss = new DOMDocument();
        //
        //http://www.pinterest.com/LuxuryRESearch/feed.rss
        $rss->load('http://www.pinterest.com/LuxuryRESearch/luxury-homes-at-luxury-real-estate-search.rss');
        $arr_pi = array();
        foreach ($rss->getElementsByTagName('item') as $node) {
            echo "<pre>"; print_r($node); die;
            $title = $node->getElementsByTagName('description')->item(0)->nodeValue;
            $title = $this->getTextBetweenTags($title, 'p');
            $description = $node->getElementsByTagName('description')->item(0)->nodeValue;
            preg_match('/<img[^>]+>/i', $description, $image_src);
            preg_match_all('/(alt|title|src)=("[^"]*")/i', $image_src[0], $image_src);
            $image_src = substr($image_src[2][0], 1, strlen($image_src[2][0]) - 2);
            $link = $node->getElementsByTagName('link')->item(0)->nodeValue;
            $date = $node->getElementsByTagName('pubDate')->item(0)->nodeValue;
            $dateInfo = explode(' ', $date);
            $dat = mktime(0, 0, 0, date("m", strtotime($dateInfo[2])), $dateInfo[1], $dateInfo[3]);
            $dat = date('Y-m-d', $dat);
            $arr_pi[] = array("date" => $dat,
                "social" => "pinterest",
                "title" => $title[0],
                "link" => $link,
                "image" => $image_src,
                "image_link" => $link,
                "link_name" => $title[0],
                "likes" => '',
                "comments" => '',
            );
        }

        if (!is_array($arr_facebook))
            $arr_facebook = array();

        if (!is_array($arr_twitter))
            $arr_twitter = array();

        /* instagram */
        if (!is_array($arr_insta))
            $arr_insta = array();

        if (!is_array($arr_pi))
            $arr_pi = array();

        if (!is_array($arr_google))
            $arr_google = array();

        $social = array_merge($arr_twitter, $arr_insta, $arr_facebook, $arr_pi, $arr_google);
        $social = $this->aasort($social, 'date', 'DESC');

        return view('social.listing', ['socialdata' => $social]);
    }
    
    function getTextBetweenTags($string, $tagname)
    {
        $pattern = "/<$tagname ?.*>(.*)<\/$tagname>/";
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
    }
    
    
    public function aasort (&$array, $key,$order = 'ASC')
    {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
                $sorter[$ii]=$va[$key];
        }
        if($order == 'ASC')
                asort($sorter);
        elseif($order == 'DESC')
                arsort($sorter);

        foreach ($sorter as $ii => $va) {
                $ret[$ii]=$array[$ii];
        }
        $array=$ret;
        return $array;
    }
}
