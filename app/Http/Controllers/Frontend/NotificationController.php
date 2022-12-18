<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index (){
        $user = auth()->guard('web')->user();
        $noti = $user->notifications()->paginate(3);
        return view('frontend.notification', compact('noti'));
    }
    public function show($id){
        $user = auth()->guard('web')->user();
        $noti = $user->notifications()->where('id', $id)->firstOrFail();
        $noti->markAsRead();
        return view('frontend.noti_detail', compact('noti'));
    }
}
