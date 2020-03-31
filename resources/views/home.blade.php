@extends('layouts.loggedinheader')
@section('content')
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?php $user = Auth::user(); ?>
        <!-- Main content -->
        @if($user->hasRole('Admin'))
            @include('includes.dashboardadmin')
        @else
            @include('includes.dashboarduser')        
        @endif
        <!-- /.content -->
      </div><!-- /.content-wrapper -->

@endsection