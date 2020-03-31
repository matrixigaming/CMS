<?php
//define constant which will be used in all application
return [
    'destination' => [
        'sizes' => [
            'xs' => [400,200],
            'th' => [500,500],
            'standard' => [800,475],
            'lg' => [1200,600],
            ],
        'destination_full_path' => base_path().'/public/uploads/destination/',
        'destination_path' => 'uploads/destination/',
        'destination_file_full_path' => base_path().'/public/uploads/destination/files/',
        'destination_file_path' => 'uploads/destination/files/',
    ],
    'game' => [
        'sizes' => [
            'xs' => [100,100],
            'sm' => [400,200],
            'th' => [500,500],
            'xl' => [800,475],
            ],
        'game_icon_full_path' => base_path().'/public/uploads/game/images/',
        'game_icon_path' => 'uploads/game/images/',
    ],
    'listing' => [
        'sizes' => [
            'xs' => [100,100],
            'th' => [500,500],
            'standard' => [800,475],
            'lg' => [1200,600],
            'banner' => [1600,850],
            ],
        'listing_full_path' => base_path().'/public/uploads/listing/images/',
        'listing_path' => 'uploads/listing/images/',
        'listing_audio_full_path' => base_path().'/public/uploads/listing/audios/',
        'listing_audio_path' => 'uploads/listing/audios/',
        'listing_file_full_path' => base_path().'/public/uploads/listing/files/',
        'listing_file_path' => 'uploads/listing/files/',
    ],
    'user' => [
        'sizes' => [
            'xs' => [150,75],
            'th' => [500,500],
            'standard' => [800,475],
            'lg' => [1200,600],
            ],
        'user_full_path' => base_path().'/public/uploads/user/images/',
        'user_path' => 'uploads/user/images/',
        'user_audio_full_path' => base_path().'/public/uploads/user/audios/',
        'user_audio_path' => 'uploads/user/audios/',
        'user_file_full_path' => base_path().'/public/uploads/user/files/',
        'user_file_path' => 'uploads/user/files/',
    ],
  'notification_icon_full_path'=> base_path().'/public/uploads/notification_icons/',
  'notification_icon_path'=> 'uploads/notification_icons/',
  'user_avatar_full_path'=>base_path().'/public/uploads/user_avatar/',
  'user_avatar_path'=> 'uploads/user_avatar/', 
  'distributor_rtp_variant' => [90,91,92,93,94,95], 
  'otp_valid_time' =>600, //in seconds
  'shop_video_full_path'=> base_path().'/public/uploads/shop_videos/',
  'shop_video_path'=> 'uploads/shop_videos/',
  
];
