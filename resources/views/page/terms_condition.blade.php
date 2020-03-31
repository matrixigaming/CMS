@extends('layouts.frontapp')

@section('content')
<section>
    <div class="container-fluid">
        <div class="row content-area myCarousel index-page inner-page">
            <div class="container-fluid bg-gray">
                <ul class="breadcrumb letter-space hidden-xs hidden-sm">
                    <li><a href="{{ url('/') }}">home</a></li>
                    <li class="active"><a href="{{ url('/terms-and-conditions') }}">terms & conditions</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="col-lg-12">
        <div class="row content-banner">
            <h1>Terms & Conditions</h1>
            <img class="img-responsive" src="{{ url('frontend/images/banner/content-banner.jpg') }}" alt="Luxury Real Estate Search - Terms & Conditions" />
        </div>
    </div>
</section>

<section>
    <div class="col-lg-12">
        <div class="inner content-section">
            @php $pageContent = Helper::getPageContent(2); @endphp
            @if($pageContent)
                {!! $pageContent->content !!}
            @endif
        </div>
    </div>
</section>
@endsection
