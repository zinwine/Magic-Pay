@extends('frontend.layouts.app')
@section('page-name', 'Notification Detail')
@section('content')
<div class="notification_deatil update-password">
        <div class="card">
            <div class="card-body  text-center">
                <div class="img">
                    <img src="{{asset('img/Notifications_Outline.png')}}" alt="">
                </div>
                
                <h6 class="text-mute mb-1">{{$noti->data['title']}}</h6>
                <p class="text-mute mb-1">{{$noti->data['message']}}</p>
                <p class="text-mute mb-3"><small class="text-mute mb-0">{{Carbon\Carbon::parse($noti->created_at)->format('d-M-Y h:m:s A')}}</small></p>
                <a href="{{$noti->data['web_link']}}" class="text-white btn btn_theme btn-sm">Continue</a>
            </div>
        </div>
</div>
@endsection




