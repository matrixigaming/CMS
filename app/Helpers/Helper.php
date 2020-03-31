<?php

namespace App\Helpers;
use App\Destination as Destination;
use App\Page as Page;
use Mail;

class Helper 
{
    public function getTimeDuration($date){
        $current = date('Y-m-d H:i:s');
        $created = $date;
        $datetime1 = date_create($created);
        $datetime2 = date_create($current);
        $interval = date_diff($datetime1, $datetime2);
        $interval_result = $this->format_interval($interval);
        //var_dump($interval_result);
        if(isset($interval_result['year'])){
            return $interval_result['year'].' ago';
        }
        if(isset($interval_result['month'])){
            return $interval_result['month'].' ago';
        }
        if(isset($interval_result['day'])){
            return $interval_result['day'].' ago';
        }
        if(isset($interval_result['hour'])){
            return $interval_result['hour'].' ago';
        }
        if(isset($interval_result['minute'])){
            return $interval_result['minute'].' ago';
        }
        if(isset($interval_result['second'])){
            return 'Just now'; //$interval_result['yesr'].' ago';
        }
    }
    
    function format_interval($interval) {
        $result = array();
        if ($interval->y) { $result['year']= $interval->format("%y years "); }
        if ($interval->m) { $result['month']= $interval->format("%m months "); }
        if ($interval->d) { $result['day']= $interval->format("%d days "); }
        if ($interval->h) { $result['hour']= $interval->format("%h hours "); }
        if ($interval->i) { $result['minute']= $interval->format("%i minutes "); }
        if ($interval->s) { $result['second']= $interval->format("%s seconds "); }

        return $result;
    }

    public static function renderTextEditor($selector)
    {
        $script = '<script type="text/javascript" src="' . url('plugins/wysiwyg/tinymce/tinymce.min.js') . '"></script>
                   <script type="text/javascript">
                        tinymce.init({
                            selector: "#' . $selector . '",
                            height: 200,
                            menubar: false,
                            plugins: [
                              \'autolink lists link image charmap print preview anchor\',
                              \'searchreplace visualblocks code fullscreen\',
                              \'insertdatetime media table contextmenu paste code responsivefilemanager\'
                            ],
                            toolbar: \' bold italic underline  |  bullist | list | link image | code \',
                            content_css : \'https://fonts.googleapis.com/css?family=Montserrat:400,700\',
                            filemanager_title:\'Filemanager\' ,
                            external_filemanager_path: "' . url('plugins/wysiwyg/responsivefilemanager/filemanager') . '/",
                            external_plugins: { 
                              \'filemanager\' : "' . url('plugins/wysiwyg/responsivefilemanager/filemanager/plugin.min.js') . '"
                            }
                        });
                    </script>';
        
        return $script;
    }
    
    public static function getSocialClass($socialagent)
    {
        $socialagent = (array) $socialagent;

        switch($socialagent['social']){
            case 'facebook': $class = "fb-ico"; break;
            case 'twitter': $class = "twit-ico"; break;
            case 'instagram': $class = "insta-ico"; break;
            case 'pintrest': $class = "pinterest-ico"; break;
            case 'google plus': $class = "gplus-ico"; break;
            case 'youtube': $class = "utube-small-ico"; break;
            case 'linkedin': $class = "linkin-ico"; break;
            case 'tumblr': $class = "tumblr-ico"; break;
            default: $class = '';
        }
        
        return $class;
    }
    
    public static function getAllSocial()
    {
        return ['facebook', 'twitter', 'instagram', 'pintrest', 'google plus', 'youtube', 'linkedin', 'tumblr'];
    }
    
    public static function trimLength($s, $maxlength)
    {
        $words = explode(' ', $s);
        $split = '';

        foreach ($words as $word) {
            if (strlen($split) + strlen($word) < $maxlength)
                $split .= $word . ' ';
            else
                break;
        }

        if (strlen($s) > $maxlength) {
            $split = trim($split) . " ...";
        }

        return $split;
    }
    
    public static function footerDestinations()
    {
        $query = \App\Destination::with(['listing' => function($query){
                        $query->where('active', 1);                        
                    }])->whereHas('listing', function ($query){})->where('featured', 1)->get();
        

        return $query;
    }
    
    public static function getPageContent($id)
    {
        $query = \App\Page::find($id);

        return $query;
    }
    
    public static function youtubeVideoId($link)
    {
        preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $youtubeVideoId);

        return isset($youtubeVideoId[0]) && !empty($youtubeVideoId[0]) ? $youtubeVideoId[0] : '';
    }
    
    public static function convertYoutubeVideo($youtubeVideoId)
    {
        if ($youtubeVideoId)
            return '<embed src="http://www.youtube.com/v/' . $youtubeVideoId . '&rel=1" type="application/x-shockwave-flash" wmode="transparent" allowfullscreen="true" width="460" height="295"></embed><br />';
        else
            return "";
    }
    
    public static function vimeoVideoDetails($link, $returnparameter = '')
    {
        $id = substr(trim($link), strlen('http://vimeo.com/'));
        $videodata = @file_get_contents('https://vimeo.com/api/oembed.json?url=' . $link);
        $videodata = json_decode($videodata, true);

        return ($returnparameter && isset($videodata[$returnparameter])) ? $videodata[$returnparameter] : $videodata;
    }
    
    public static function cleanQuery($string)
    {
        $string = trim(htmlentities(strip_tags($string)));

        if (get_magic_quotes_gpc())
            $string = stripslashes($string);

        return $string;
    }
    
    public static function sendEmail($data, $purpose)
    {
        switch($purpose){
            case 'property_request':
                Mail::send('emails.property_request', $data, function ($mail) use ($data) {
                    $mail->from(config('mail.username'), 'Luxury Real Estate Search');

                    $mail->to($data['to'], $data['first_name'])->subject($data['subject']);
                });
                break;
            case 'contact_request':
                $data['is_admin'] = 1;
                Mail::send('emails.contact_request', $data, function ($mail) use ($data) {
                    $mail->from(config('mail.username'), 'Luxury Real Estate Search');

                    $mail->to('sanford@lres.co', 'Sanford')->subject($data['subject']);
                });
                if(isset($data['user']) && !empty($data['user'])){
                    $data['is_admin'] = 0;
                    Mail::send('emails.contact_request', $data, function ($mail) use ($data) {
                        $mail->from(config('mail.username'), 'Luxury Real Estate Search');

                        $mail->to($data['user']['email'], $data['user']['first_name'])->subject($data['subject']);
                    });
                }
                break;
            case 'contact_request_lender':
                $data['is_admin'] = 1;
                Mail::send('emails.contact_request', $data, function ($mail) use ($data) {
                    $mail->from(config('mail.username'), 'Luxury Real Estate Search');
                    $mail->to('kbenda@summit-mortgage.com', 'Kathleen')->subject($data['subject']);
                    //$mail->to('bjain@tecziq.com', 'Sanford')->subject($data['subject']);
                  //  $mail->to('sanford@lres.co', 'Sanford')->cc(['kbenda@summit-mortgage.com'])->subject($data['subject']);
                    
                });
                if(isset($data['user']) && !empty($data['user'])){
                    $data['is_admin'] = 0;
                    Mail::send('emails.contact_request', $data, function ($mail) use ($data) {
                        $mail->from(config('mail.username'), 'Luxury Real Estate Search');

                        $mail->to($data['user']['email'], $data['user']['first_name'])->subject($data['subject']);
                    });
                }
                break;
            case 'newsletter_request':
                Mail::send('emails.newsletter_request', $data, function ($mail) use ($data) {
                    $mail->from(config('mail.username'), 'Luxury Real Estate Search');

                    $mail->to('sanford@lres.co', 'Sanford')->subject($data['subject']);
                });
                break;
        }
    }
    
    public function array_sort($array, $on, $order=SORT_ASC){
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    
    public function getImageArraySort($imageArr, $from = 'Destination'){
        $isDefaultKey = array_search('1', array_column($imageArr, 'is_default'));
        $newSortArray[] = $imageArr[$isDefaultKey];
        unset($imageArr[$isDefaultKey]);
        $sortteList = $this->array_sort($imageArr, 'sort_order', SORT_ASC);
        $newSortArray = array_merge($newSortArray, $sortteList);
        if($from == 'Property'){
            $onePos = isset($newSortArray[1]) ? $newSortArray[1] : false;
            if($onePos){
                $newSortArray[1] = $newSortArray[0];
                $newSortArray[0] = $onePos;
            }
        }
        return $newSortArray;
    }
    public static function updateBathroomsValue($full, $threeHalf, $half)
    {
        $baths = round($full + ( $threeHalf * 3/4 ) + ( $half * 0.5 ), 2);

        return $baths;
    }
}
