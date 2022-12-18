@extends('frontend.layouts.app')
@section('page-name', 'Notification')
@section('content')
<div class="noti">
    <div class="infinite-scroll">
        @foreach ($noti as $notif)
        <a href="{{url('notification/'.$notif->id)}}">
            <div class="card mb-2">
                <div class="card-body p-3">
                    {{-- <h3>{{$notif}}</h3> --}}
                    {{-- <div class="d-flex justify-content-between">
                        <p class="text-mute mb-1">
                            @if ($noti->type == 2)    
                            To               
                            {{$noti->source ? $noti->source->name : '-'}}
                            @else 
                            From
                            {{$noti->source ? $noti->source->name : '-'}}
                        @endif
                        </p>
                        <p class="mb-1 {{$noti->type == 2 ? 'text-danger' : 'text-success'}}">{{$noti->amount}} <small>MMK</small></p>
                    </div>
                     --}}
                    <h6 class="@if(is_null($notif->read_at)) text-danger @endif"><i class="fas fa-bell "></i> {{Illuminate\Support\Str::limit($notif->data['title'], 20)}}</h6>
                    <p class="mb-1">Message : {{Illuminate\Support\Str::limit($notif->data['message'],50)}}</p>
                    <small class="text-mute mb-0">{{Carbon\Carbon::parse($notif->created_at)->format('d-M-Y h:m:s A')}}</small>
                    </div>
                </div>
            </div>
        @endforeach
        </a>
        {{$noti->links()}}    
    </div>
</div>
@endsection
@section('script')
    <script>
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<img class="center-block" src="/images/loading.gif" alt="Loading..." />',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });
    </script>
@endsection