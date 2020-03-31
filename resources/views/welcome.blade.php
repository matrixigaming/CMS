@extends('layouts.frontapp')

@section('content')
<?php //echo "<pre>"; print_r($data['bannerListings']); echo "</pre>"; die;?>
<section>
        <div class="container-fluid">
            <div class="row content-area myCarousel index-page"> 
                <div id="myCarousel" class="carousel slide  carousel-fade" data-interval="10000" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        if(!empty($data['bannerListings'])):
                            foreach($data['bannerListings'] as $k=>$listing): 
                            if(isset($listing->listImages->image_path) && !empty($listing->listImages->image_path)){
                                $imagePath =  $listing->listImages->image_path; 
                                $path_parts = pathinfo($imagePath);
                                $filename = $path_parts['dirname'].'/'.$path_parts['filename'].'_banner'.'.'.$path_parts['extension'];
                                $filename = file_exists($filename) ? $filename : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                            }else{
                                $filename = 'frontend/images/banner/content-banner.jpg';
                            }
                            
//echo "<pre>"; print_r($listing); echo "</pre>"; die;
                           //$configData = config('constants.listing');
                           
                           
                           $bathrooms = $listing->full_bathrooms + $listing->three_fourth_bathrooms + $listing->half_bathrooms;
                           
                           $active = !$k ? 'active' : ''; 
                            ?>
                                <div class="item <?php echo $active?>">
                                    <a href="{{ url('property/' . $listing->id . '/'  . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $listing->name)))) }}">
                                        <img src="{{ url($filename) }}" alt="<?php echo $listing->name?>" title="<?php echo $listing->name?>">
                                    </a>
                                    <div class="carousel-caption">
                                        <ul>
                                            <li>
                                                <!--<button class="btn btn-defalut">sale</button>-->
                                                <div class="box">
                                                    <h5>
                                                        <?php echo $listing->destination_name?>, <?php echo $listing->state_name?></h5>
                                                    <h3><?php echo $listing->name?></h3>
                                                </div>
                                            </li>
                                            <li>
                                                <?php if($listing->price_display_option == 'display price'): ?>
                                                <h2>                                                    
                                                    <?php echo $listing->currency?> <?php echo number_format($listing->price)?><dummy class="no-uppercase"></dummy>
                                                </h2>
                                                <?php else: ?>
                                                <h2>                                                    
                                                    <?php echo 'Price on request'; ?><dummy class="no-uppercase"></dummy>
                                                </h2>
                                                <?php endif;?>
                                            </li>
                                            <li>
                                                <?php if(!empty($listing->living_space)):?>
                                                <span><i class="sq-ft-ico ico"></i><span class="text"><?php echo $listing->living_space?> <?php echo $listing->living_space_units?></span></span>
                                                <?php endif;?>
                                                <span><i class="bed-ico ico"></i><span class="text"><?php echo $listing->bedrooms?> beds</span></span>
                                                <span class="last"><i class="bathdub-ico ico"></i><span class="text"><?php echo $bathrooms; ?> baths</span></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                        
                    </div>
                    <a class="carousel-control white left" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
                    <a class="carousel-control white right" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
                </div>
            </div>
        </div>
    </section>


<section class="index">
        <div class="col-lg-12">
            <div class="row properties-tab">

                <div class="header">
                    <h2>Properties</h2>
                    <ul>
                        <li><a class="active" href="#tab-one">FEATURED</a></li>
                        <li><a href="#tab-two">WATERFRONT</a></li>
                        <li><a href="#tab-three">HISTORIC</a></li>
                        <!-- <li><a href="#tab-four">OPEN HOUSES</a></li> -->
                    </ul>
                    <span class="select hidden-lg hidden-md">
                        <select class="properties-selector">
                            <option class="tab-one">FEATURED</option>
                            <option class="tab-two">WATERFRONT</option>
                            <option class="tab-three">HISTORIC</option>
                        </select>
                    </span>
                </div>
                <div id="tab-one" class="tabs active mobile-view-on">
                    <?php
                        if(!empty($data['featuredListing'])):
                            $counter = 1;
                            foreach($data['featuredListing'] as $k=>$listing):
                                
                                if(isset($listing->listImages->image_path)){
                                    $imagePath =  $listing->listImages->image_path; 
                                    $path_parts = pathinfo($imagePath);

                                   //$configData = config('constants.listing');
                                    if($counter==1 || $counter==2){
                                        $filename = file_exists($path_parts['dirname'].'/'.$path_parts['filename'].'_standard'.'.'.$path_parts['extension']) ? $path_parts['dirname'].'/'.$path_parts['filename'].'_standard'.'.'.$path_parts['extension'] : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                        $class = 'col-lg-6';
                                    }else{
                                        $filename = file_exists($path_parts['dirname'].'/'.$path_parts['filename'].'_th'.'.'.$path_parts['extension'])?$path_parts['dirname'].'/'.$path_parts['filename'].'_th'.'.'.$path_parts['extension']:$path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                        $class = 'col-lg-4';
                                    }
                                }else{
                                    $class = $counter==1 || $counter==2 ? 'col-lg-6' : 'col-lg-4';
                                    $filename = 'dist/img/no-image.jpg';
                                }
                           
                            //echo "<pre>"; print_r($listing); echo "</pre>"; die;
                           $bathrooms = $listing->full_bathrooms + $listing->three_fourth_bathrooms + $listing->half_bathrooms;
                           $counter++;
                            ?>
                    <div class="<?php echo $class; ?>">
                        <div class="row box img-box">
                            <div class="overlay">
                                <a href="{{url('property/' . $listing->id . '/'  . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $listing->name))))}}">
                                    <button class="btn btn-defalut strock">view property</button>
                                </a>
                            </div>
                            <?php if($listing->sale_type == 'sale'): ?>
                            <button class="btn btn-defalut reverse top">sale</button>
                            <?php endif; ?>
                            <div class="details">
                                <div class="left">
                                    <span class="letter-space">
                                        <?php echo $listing->destination_name?>, <?php echo $listing->state_name?></span>
                                    <br />
                                    <h3 class="marginB-14"><?php echo $listing->name?></h3>
                                        <?php //echo $listing->street_address_1; if(!empty($listing->street_address_2)) echo ', '.$listing->street_address_2;?>
                                    <?php if($listing->price_display_option == 'display price'): ?>
                                    <h3 class="marginB-5">                                                   
                                        <?php echo $listing->currency?> <?php echo number_format($listing->price)?><dummy class="no-uppercase"></dummy>
                                    </h3>
                                    <?php else: ?>
                                    <h3 class="marginB-5">                                                    
                                        <?php echo 'Price on request'; ?><dummy class="no-uppercase"></dummy>
                                    </h3>
                                    <?php endif;?>
                                    
                                    <span class="letter-space"><?php echo $listing->living_space?> <?php echo $listing->living_space_units?>&nbsp;|&nbsp;<?php echo $listing->bedrooms?> beds&nbsp;|&nbsp;<?php echo $bathrooms?> baths</span>
                                </div>
                            </div>
                            <img class="img-responsive" src="{{ url($filename) }}" alt="<?php echo $listing->name;?>">
                        </div>
                    </div>
                    <?php
                        endforeach;
                    endif; 
                    ?>
                </div>

                <div id="tab-two" class="tabs mobile-view-on">
                    <?php
                        if(!empty($data['waterFrontListing'])):
                            $counter = 1;
                            foreach($data['waterFrontListing'] as $k=>$listing):
                                if(isset($listing->listImages->image_path)){
                                    $imagePath =  $listing->listImages->image_path; 
                                    $path_parts = pathinfo($imagePath);

                                   //$configData = config('constants.listing');
                                    if($counter==1 || $counter==2){
                                        $filename = file_exists($path_parts['dirname'].'/'.$path_parts['filename'].'_standard'.'.'.$path_parts['extension']) ? $path_parts['dirname'].'/'.$path_parts['filename'].'_standard'.'.'.$path_parts['extension'] : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                        $class = 'col-lg-6';
                                    }else{
                                        $filename = file_exists($path_parts['dirname'].'/'.$path_parts['filename'].'_th'.'.'.$path_parts['extension'])?$path_parts['dirname'].'/'.$path_parts['filename'].'_th'.'.'.$path_parts['extension']:$path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                        $class = 'col-lg-4';
                                    }
                                }else{
                                    $filename = 'dist/img/no-image.jpg';
                                }
                            
                           
                            //echo "<pre>"; print_r($listing); echo "</pre>"; die;
                           $bathrooms = $listing->full_bathrooms + $listing->three_fourth_bathrooms + $listing->half_bathrooms;
                           $counter++;
                            ?>
                    <div class="<?php echo $class; ?>">
                        <div class="row box img-box">
                            <div class="overlay">
                                <a href="{{url('property/' . $listing->id . '/'  . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $listing->name))))}}">
                                    <button class="btn btn-defalut strock">view property</button>
                                </a>
                            </div>
                            <?php if($listing->sale_type == 'sale'): ?>
                            <button class="btn btn-defalut reverse top">sale</button>
                            <?php endif; ?>
                            <div class="details">
                                <div class="left">
                                    <span class="letter-space">
                                        <?php echo $listing->destination_name?>, <?php echo $listing->state_name?></span>
                                    <br />
                                    <h3 class="marginB-14"><?php echo $listing->name?></h3>
                                        <?php //echo $listing->street_address_1; if(!empty($listing->street_address_2)) echo ', '.$listing->street_address_2;?>
                                    <?php if($listing->price_display_option == 'display price'): ?>
                                    <h3 class="marginB-5">                                                   
                                        <?php echo $listing->currency?> <?php echo number_format($listing->price)?><dummy class="no-uppercase"></dummy>
                                    </h3>
                                    <?php else: ?>
                                    <h3 class="marginB-5">                                                    
                                        <?php echo 'Price on request'; ?><dummy class="no-uppercase"></dummy>
                                    </h3>
                                    <?php endif;?>
                                    
                                    <span class="letter-space"><?php echo $listing->living_space?> <?php echo $listing->living_space_units?>&nbsp;|&nbsp;<?php echo $listing->bedrooms?> beds&nbsp;|&nbsp;<?php echo $bathrooms?> baths</span>
                                </div>
                            </div>
                            <img class="img-responsive" src="{{ url($filename) }}" alt="<?php echo $listing->name;?>">
                        </div>
                    </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>

                <div id="tab-three" class="tabs mobile-view-on">
                    <?php
                        if(!empty($data['historicListing'])):
                            $counter = 1;
                            foreach($data['historicListing'] as $k=>$listing):
                                if(isset($listing->listImages->image_path)){
                                    $imagePath =  $listing->listImages->image_path; 
                                    $path_parts = pathinfo($imagePath);

                                   //$configData = config('constants.listing');
                                    if($counter==1 || $counter==2){
                                        $filename = file_exists($path_parts['dirname'].'/'.$path_parts['filename'].'_standard'.'.'.$path_parts['extension']) ? $path_parts['dirname'].'/'.$path_parts['filename'].'_standard'.'.'.$path_parts['extension'] : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                        $class = 'col-lg-6';
                                    }else{
                                        $filename = file_exists($path_parts['dirname'].'/'.$path_parts['filename'].'_th'.'.'.$path_parts['extension'])?$path_parts['dirname'].'/'.$path_parts['filename'].'_th'.'.'.$path_parts['extension']:$path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                        $class = 'col-lg-4';
                                    }
                                }else{
                                    $filename = 'dist/img/no-image.jpg';
                                }
                           
                            //echo "<pre>"; print_r($listing); echo "</pre>"; die;
                           $bathrooms = $listing->full_bathrooms + $listing->three_fourth_bathrooms + $listing->half_bathrooms;
                           $counter++;
                            ?>
                    <div class="<?php echo $class; ?>">
                        <div class="row box img-box">
                            <div class="overlay">
                                <a href="{{url('property/' . $listing->id . '/'  . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $listing->name))))}}">
                                    <button class="btn btn-defalut strock">view property</button>
                                </a>
                            </div>
                            <?php if($listing->sale_type == 'sale'): ?>
                            <button class="btn btn-defalut reverse top">sale</button>
                            <?php endif; ?>
                            <div class="details">
                                <div class="left">
                                    <span class="letter-space">
                                        <?php echo $listing->destination_name?>, <?php echo $listing->state_name?></span>
                                    <br />
                                    <h3 class="marginB-14"><?php echo $listing->name?></h3>
                                        <?php //echo $listing->street_address_1; if(!empty($listing->street_address_2)) echo ', '.$listing->street_address_2;?>
                                    <?php if($listing->price_display_option == 'display price'): ?>
                                    <h3 class="marginB-5">                                                   
                                        <?php echo $listing->currency?> <?php echo number_format($listing->price)?><dummy class="no-uppercase"></dummy>
                                    </h3>
                                    <?php else: ?>
                                    <h3 class="marginB-5">                                                    
                                        <?php echo 'Price on request'; ?><dummy class="no-uppercase"></dummy>
                                    </h3>
                                    <?php endif;?>
                                    
                                    <span class="letter-space"><?php echo $listing->living_space?> <?php echo $listing->living_space_units?>&nbsp;|&nbsp;<?php echo $listing->bedrooms?> beds&nbsp;|&nbsp;<?php echo $bathrooms?> baths</span>
                                </div>
                            </div>
                            <img class="img-responsive" src="{{ url($filename) }}" alt="<?php echo $listing->name;?>">
                        </div>
                    </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>


        <div class="col-lg-12">
            <div class="row featured-agents content-box">
                <div class="col-lg-12 header">
                    <h2>Featured Agents</h2>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="carousel slide agents-list-slider index " data-interval="0">
                            <div class="carousel-inner gallery-list mobile-view-on">
                                <?php if(!empty($data['featuredAgents']['data'])): ?>
                                <?php foreach($data['featuredAgents']['data'] as $key => $agent): ?>
                                <div class="col-lg-4">
                                    <a href="{{ url('agent/' . $agent->id . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $agent->first_name . '-' . $agent->last_name)))) }}">
                                        @if($agent->image_path)
                                            @php
                                                $imagePath = $agent->image_path;
                                                $path_parts = pathinfo($imagePath);
                                                $filename = $path_parts['dirname'] . '/' . $path_parts['filename'] . '_th' . '.' . $path_parts['extension'];
                                                $filename = file_exists($filename) ? $filename : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                            @endphp
                                            <img class="img-responsive" src="{{ url($filename) }}" alt="{{ $agent->first_name }}" title="{{ $agent->first_name }}">
                                        @else
                                            <img class="img-responsive" src="{{ url('uploads/no-image_th.jpg') }}"  alt="{{ $agent->first_name }}" title="{{ $agent->first_name }}">
                                        @endif
                                    </a>
                                    <div class="details">
                                        <a href="{{ url('agent/' . $agent->id . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $agent->first_name . '-' . $agent->last_name)))) }}">
                                            <h3>{{ $agent->first_name }}  {{ $agent->last_name }}</h3>                                                
                                        </a>
                                        <span class="">{{ $agent->agency_name }}</span>
                                        <span class="letter-space"></span><br>
                                        @if(isset($agent->social) && count($agent->social) > 0)
                                            @foreach($agent->social as $social_media)
                                                @if($social_media->social == 'facebook')
                                                    <a href="{{ $social_media->link }}" target="_blank"><i class="fb-ico"></i></a>
                                                @elseif($social_media->social == 'twitter')
                                                    <a href="{{ $social_media->link }}" target="_blank"><i class="twit-ico"></i></a>
                                                @elseif ($social_media->social == 'instagram')
                                                    <a href="{{ $social_media->link }}" target="_blank"><i class="insta-ico"></i></a>
                                                @elseif ($social_media->social == 'linkedin')
                                                    <a href="{{ $social_media->link }}" target="_blank"><i class="linkin-ico"></i></a>
                                                @endif
                                            @endforeach    
                                        @endif
                                    </div>
                                </div>
                                    
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="row featured-agents content-box">
                <div class="col-lg-12 header">
                    <h2>Featured Lender</h2>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="carousel slide agents-list-slider index " data-interval="0">
                            <!--<div class="carousel-inner gallery-list mobile-view-on">-->
                                <div class="col-lg-4 col-md-4"></div>
                                <div class="col-lg-4 col-md-4">
                                    <a href="{{ url('lender/'. urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], 'Kathleen-Benda')))) }}" target="_blank">
                                        <img class="img-responsive" src="{{ asset('uploads/lender/user/images/Business_Photo_Evergreen-cropped.png') }}" alt="summit mortage corporation" title="" width="360" >
                                    </a>
                                    <div class="details">
                                        <a href="{{ url('lender/'. urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], 'Kathleen-Benda')))) }}" target="_blank">
                                            <h3>Kathleen Benda</h3>                                                
                                        </a>
                                        <span class="">Summit Mortgage Corporation</span>
                                        <span class="letter-space"></span><br>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4"></div>
                            <!--</div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
<?php if(isset($data['featuredDestinations']['data']) && count($data['featuredDestinations']['data'])>0) { ?>
<div class="col-lg-12">
<div class="row global-luxury content-box">
<div class="col-lg-12 header white">
<h2>Global Luxury</h2>
<p class="text-center"> Browse our featured listings in prime global markets.</p>
</div>
<div class="col-lg-12">
<div class="row">
<div class="global-luxury-slider" id="global-luxury-slider">
<div class="carousel-inner luxury-slider">
<div class="item">
<?php
$i=1;
if(isset($data['featuredDestinations']['data']) && count($data['featuredDestinations']['data'])>0) {
foreach ($data['featuredDestinations']['data'] as $key => $destination) {
//if($destination['properties_count']>0) {
?>
<div class="col-md-12 col-sm-6 col-xs-12 box img-box">
<div class="details">
<a href="{{ url('destination/' . $destination->id . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $destination->name)))) }}">
                                                <h3 class="title-case">{{ $destination->name }}</h3></a>
</div>
<div class="overlay"></div>
@if(isset($destination->image_path) && $destination->image_path)
            @php
                $imagePath = $destination->image_path;
                $path_parts = pathinfo($imagePath);
                $filename = $path_parts['dirname'].'/'.$path_parts['filename'].'_xs'.'.'.$path_parts['extension'];
                $filename = file_exists($filename) ? $filename : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
            @endphp
        
        <img class="img-responsive" src="{{ url($filename) }}" alt="Destinations - {{ $destination->name }}" title="{{ $destination->name }}">
        @else
        <img class="img-responsive" src="{{ url('uploads/no-image_xs.jpg') }}"  alt="{{ $destination->name }}" title="{{ $destination->name }}">
        @endif
</div>
<?php
if ($i % 2 == 0)
echo '</div><div class="item">';
$i++;
//}
}
} 
?>
</div>
</div>
</div>
<div class="col-lg-12 text-center"><a href="{{ url('destinationslisting') }}"><button class="btn btn-defalut strock">view ALL Destinations</button></a>	</div>
</div>
</div>
</div>
</div>
<?php } ?>    

        
        </div>
    
    </section>
@endsection
