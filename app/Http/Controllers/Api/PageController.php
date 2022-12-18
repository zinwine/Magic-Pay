<?php

namespace App\Http\Controllers\Api;

use App\Helpers;
use App\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

class PageController extends Controller
{
    public function profile(){
        $user = auth()->user();
        $data = new ProfileResource($user);
        return success('success', $data);
    }
    
    public function transaction(Request $request){
        $user = auth()->user();
        $transactions = Transaction::with('user','source')->where('user_id', $user->id)->orderBy('created_at', 'DESC');
        if($request->type){
            $transactions = $transactions->where('type', $request->type);
        }
        if($request->date){
            $transactions = $transactions->whereDate('created_at', $request->date);
        }
        $transactions = $transactions->paginate(5);
        $data = TransactionResource::collection($transactions)->additional(['result' => 1, 'message' => 'success']);
        return $data;
    }

    public function transaction_detail($trx_id){
        $user = auth()->user();
        $transaction = Transaction::with('user','source')->where('user_id', $user->id)->where('trx_id', $trx_id)->firstOrFail();
        $data = new TransactionDetailResource($transaction);
        return success('success', $data);
    }
    public function notification(){
        $user = auth()->user();
        $noti = $user->notifications()->paginate(3);
        $data = NotificationResource::collection($noti)->additional(['result' => 1, 'message' => 'success']);
        return $data;
    }
    
    public function notification_detail($noti_id){
        $user = auth()->user();
        $noti = $user->notifications()->where('id', $noti_id)->firstOrFail();
        $noti->markAsRead();
        $data = new NotificationDetailResource($noti);
        return success('success', $data);
    }
    
    
}
