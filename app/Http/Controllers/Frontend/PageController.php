<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use App\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePassword;
use App\Notifications\GeneralNotification;
use App\Http\Requests\TransferFormValidate;
use Illuminate\Support\Facades\Notification;

class PageController extends Controller
{
    public function home (){
        $user = Auth::guard('web')->user();

        // $title = 'Hello World';
        // $message = 'Lorem LoremLorem LoremLorem LoremLorem LoremLorem LoremLorem LoremLorem Lorem';
        // $source_id = 3;
        // $source_type = 'transfer';
        // $web_link = url('profile');

        // Notification::send([$user], new GeneralNotification($title, $message, $source_id, $source_type, $web_link));

        return view('frontend.home', compact('user'));
    }

    public function wallet (){
        // $user = Auth::user();
        $user = Auth::guard('web')->user();
        return view('frontend.wallet', compact('user'));
    }

    public function receiverQR (){
        $user = Auth::guard('web')->user();
        return view('frontend.receive_qr', compact('user'));
    }

    public function scanAndPay (){
        $user = Auth::guard('web')->user();
        return view('frontend.scan_and_pay', compact('user'));
    }

    public function scanAndPayForm (Request $request){
        $from_account = Auth::guard('web')->user();
        $to_account = User::where('phone', $request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['failed' => 'QR Code is invalid.'])->withInput(); 
        }
        if($from_account->phone == $to_account->phone){
            return back()->with('failed', 'QR Code is invalid'); 
        }
        return view('frontend.scan_and_pay_form', compact('to_account', 'from_account'));
    }

    public function scanAndPayConfirm (TransferFormValidate $request){

        $str = $request->to_phone.$request->amount.$request->description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay12345#$@&^%');
        if($request->hash_value !== $hash_value2){
            return back()->withErrors(['amount' => 'The given data is invalid'])->withInput();
        }
        
        $from_account = Auth::guard('web')->user();
        $to_phone = $request->to_phone;
        $to_amount = $request->amount;
        $to_description = $request->description;
        $hash_value = $request->hash_value;
        
        $str = $to_phone.$to_amount.$to_description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay12345#$@&^%');
        if($hash_value !== $hash_value2){
            return back()->withErrors(['failed' => 'The given data is invalid, May be your account is hacked'])->withInput();
        }
        
        if($from_account->phone == $to_phone){
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }

        $to_account = User::where('phone', $to_phone)->first();

        if(!$to_account){
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }
        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['failed' => 'The given data is invalid.'])->withInput();
        }
        if($to_amount < 1000){
            return back()->withErrors(['amount' => 'The amount must be a least 1000 MMK'])->withInput();
        }
        if($from_account->wallet->amount < $to_amount){
            return back()->withErrors(['amount' => 'Sorry! Your balance is not enough.'])->withInput();
        }
        return view('frontend.transfer_confirm', compact('hash_value','from_account', 'to_account', 'to_amount', 'to_phone', 'to_description'));
    }

    public function scanAndPayComplete (TransferFormValidate $request){
        $str = $request->to_phone.$request->amount.$request->description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay12345#$@&^%');
        if($request->hash_value !== $hash_value2){
            return back()->withErrors(['amount' => 'The given data is invalid'])->withInput();
        }
        
        $from_account = Auth::guard('web')->user();
        $to_phone = $request->to_phone;
        $to_amount = $request->amount;
        $to_description = $request->to_description;
        $hash_value = $request->hash_value;
        
        $str = $to_phone.$to_amount.$to_description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay12345#$@&^%');
        if($hash_value !== $hash_value2){
            return back()->withErrors(['failed' => 'The given data is invalid'])->withInput();
        }
        
        if($from_account->phone == $to_phone){
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }

        $to_account = User::where('phone', $to_phone)->first();

        if(!$to_account){
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }
        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['failed' => 'The given data is invalid.'])->withInput();
        }
        if($to_amount < 1000){
            return back()->withErrors(['amount' => 'The amount must be a least 1000 MMK'])->withInput();
        }
        if($from_account->wallet->amount < $to_amount){
            return back()->withErrors(['amount' => 'Sorry! Your balance is not enough.'])->withInput();
        }

        DB::beginTransaction();
        try{

            $from_wallet = $from_account->wallet;
            $from_wallet->decrement('amount', $to_amount);
            $from_wallet->update();

            $to_wallet = $to_account->wallet;
            $to_wallet->increment('amount', $to_amount);
            $to_wallet->update();

            $ref_no = UUIDGenerator::refNumber();

            $from_transaction = new Transaction();
            $from_transaction->ref_no = $ref_no;
            $from_transaction->trx_id = UUIDGenerator::trxId();
            $from_transaction->user_id = $from_account->id;
            $from_transaction->type = 2;
            $from_transaction->amount = $to_amount;
            $from_transaction->source_id = $to_account->id;
            $from_transaction->description = $to_description;
            $from_transaction->save();

            $to_transaction = new Transaction();
            $to_transaction->ref_no = $ref_no;
            $to_transaction->trx_id = UUIDGenerator::trxId();
            $to_transaction->user_id = $to_account->id;
            $to_transaction->type = 1;
            $to_transaction->amount = $to_amount;
            $to_transaction->source_id = $from_account->id;
            $to_transaction->description = $to_description;
            $to_transaction->save();

            //from noti
            $title = 'Money Transfered!';
            $message = 'Your e-money transfered ' . number_format($to_amount) . ' MMK to ' .$to_account->name . ' ( '.$to_account->phone.' )' ;
            $source_id = $from_transaction->id;
            $source_type = Transaction::class;
            $web_link = url('/transation/'.$from_transaction->trx_id);
            $api_link = [
                'target' => 'transaction_detail',
                'param' => [
                    'trx_id' => $from_transaction->trx_id
                ]
            ];

            Notification::send([$from_account], new GeneralNotification($title, $message, $source_id, $source_type, $web_link, $api_link));

            //to noti
            $title = 'Money Received!';
            $message = 'Your walled received ' . number_format($to_amount) . ' MMK from ' .$from_account->name . ' ( '.$from_account->phone.' )';
            $source_id = $to_transaction->id;
            $source_type = Transaction::class;
            $web_link = url('/transation/'.$to_transaction->trx_id);
            $api_link = [
                'target' => 'transaction_detail',
                'param' => [
                    'trxid' => $to_transaction->trx_id
                ]
            ];

            Notification::send([$to_account], new GeneralNotification($title, $message, $source_id, $source_type, $web_link, $api_link));

            DB::commit();

            return redirect('/transation/'.$from_transaction->trx_id)->with('transfer_success', 'Successfully Transfer');   
        
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors(['failed' => 'Something Wrong' . $e->getMessage()])->withInput();
        }
    }

    public function transation (Request $request){
        $auth_user = Auth::guard('web')->user();
        $transactions = Transaction::with('user','source')->where('user_id', $auth_user->id)->orderBy('created_at', 'DESC');
        if($request->type){
            $transactions = $transactions->where('type', $request->type);
        }
        if($request->date){
            $transactions = $transactions->whereDate('created_at', $request->date);
        }
        $transactions = $transactions->paginate(3);
        return view('frontend.transation', compact('transactions'));
    }
    public function transationDetail ($trx_id){
        $auth_user = Auth::guard('web')->user();
        $transaction = Transaction::with('user','source')->where('user_id', $auth_user->id)->where('trx_id', $trx_id)->first();
        return view('frontend.transaction_detail', compact('transaction'));
    }

    public function transfer (){
        $user = Auth::guard('web')->user();
        return view('frontend.transfer', compact('user'));
    }

    public function toAccountVerify (Request $request){
        $auth_user = Auth::guard('web')->user();
        // $user = User::where('phone', $request->phone)->where('phone', '!=', $auth_user->phone)->first();
        if($auth_user->phone != $request->phone){
            $user = User::where('phone', $request->phone)->first();
            if($user){
                return response()->json([
                    'status' => 'success',
                    'message' => 'success',
                    'data' => $user
                ]);
            }
        }
        
        return response()->json([
            'status' => 'failed',
            'message' => 'Invlid data',
            'data' => []
        ]);
    }

    public function transferConfirm (TransferFormValidate $request){

        $from_account = Auth::guard('web')->user();
        $to_phone = $request->to_phone;
        $to_amount = $request->amount;
        $to_description = $request->description;
        $hash_value = $request->hash_value;
        
        $str = $to_phone.$to_amount.$to_description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay12345#$@&^%');
        if($hash_value !== $hash_value2){
            return back()->withErrors(['failed' => 'The given data is invalid, May be your account is hacked'])->withInput();
        }
        
        if($from_account->phone == $to_phone){
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }

        $to_account = User::where('phone', $to_phone)->first();

        if(!$to_account){
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }
        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['failed' => 'The given data is invalid.'])->withInput();
        }
        if($to_amount < 1000){
            return back()->withErrors(['amount' => 'The amount must be a least 1000 MMK'])->withInput();
        }
        if($from_account->wallet->amount < $to_amount){
            return back()->withErrors(['amount' => 'Sorry! Your balance is not enough.'])->withInput();
        }
        return view('frontend.transfer_confirm', compact('from_account', 'to_account', 'to_amount', 'hash_value', 'to_description'));
    }
    public function transferComplete (TransferFormValidate $request){
        $from_account = Auth::guard('web')->user();
        $to_phone = $request->to_phone;
        $to_amount = $request->amount;
        $to_description = $request->to_description;
        $hash_value = $request->hash_value;
        
        $str = $to_phone.$to_amount.$to_description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay12345#$@&^%');
        if($hash_value !== $hash_value2){
            return back()->withErrors(['failed' => 'The given data is invalid'])->withInput();
        }
        
        if($from_account->phone == $to_phone){
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }

        $to_account = User::where('phone', $to_phone)->first();

        if(!$to_account){
            return back()->withErrors(['to_phone' => 'To account is invalid.'])->withInput();
        }
        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['failed' => 'The given data is invalid.'])->withInput();
        }
        if($to_amount < 1000){
            return back()->withErrors(['amount' => 'The amount must be a least 1000 MMK'])->withInput();
        }
        if($from_account->wallet->amount < $to_amount){
            return back()->withErrors(['amount' => 'Sorry! Your balance is not enough.'])->withInput();
        }

        DB::beginTransaction();
        try{

            $from_wallet = $from_account->wallet;
            $from_wallet->decrement('amount', $to_amount);
            $from_wallet->update();

            $to_wallet = $to_account->wallet;
            $to_wallet->increment('amount', $to_amount);
            $to_wallet->update();

            $ref_no = UUIDGenerator::refNumber();

            $from_transaction = new Transaction();
            $from_transaction->ref_no = $ref_no;
            $from_transaction->trx_id = UUIDGenerator::trxId();
            $from_transaction->user_id = $from_account->id;
            $from_transaction->type = 2;
            $from_transaction->amount = $to_amount;
            $from_transaction->source_id = $to_account->id;
            $from_transaction->description = $to_description;
            $from_transaction->save();

            $to_transaction = new Transaction();
            $to_transaction->ref_no = $ref_no;
            $to_transaction->trx_id = UUIDGenerator::trxId();
            $to_transaction->user_id = $to_account->id;
            $to_transaction->type = 1;
            $to_transaction->amount = $to_amount;
            $to_transaction->source_id = $from_account->id;
            $to_transaction->description = $to_description;
            $to_transaction->save();

            //from noti
            $title = 'Money Transfered!';
            $message = 'Your e-money transfered ' . number_format($to_amount) . ' MMK to ' .$to_account->name . ' ( '.$to_account->phone.' )' ;
            $source_id = $from_transaction->id;
            $source_type = Transaction::class;
            $web_link = url('/transation/'.$from_transaction->trx_id);
            $api_link = [
                'target' => 'transaction_detail',
                'param' => [
                    'trxid' => $from_transaction->trx_id
                ]
            ];

            Notification::send([$from_account], new GeneralNotification($title, $message, $source_id, $source_type, $web_link, $api_link));

            //to noti
            $title = 'Money Received!';
            $message = 'Your walled received ' . number_format($to_amount) . ' MMK from ' .$from_account->name . ' ( '.$from_account->phone.' )';
            $source_id = $to_transaction->id;
            $source_type = Transaction::class;
            $web_link = url('/transation/'.$to_transaction->trx_id);
            $api_link = [
                'target' => 'transaction_detail',
                'param' => [
                    'trxid' => $to_transaction->trx_id
                ]
            ];

            Notification::send([$to_account], new GeneralNotification($title, $message, $source_id, $source_type, $web_link, $api_link));

            DB::commit();

            return redirect('/transation/'.$from_transaction->trx_id)->with('transfer_success', 'Successfully Transfer');   
        
        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors(['failed' => 'Something Wrong' . $e->getMessage()])->withInput();
        }
    }
    
    public function passwordCheck (Request $request){
        $auth_user = Auth::guard('web')->user();
        $password = $request->password;
        if(!$password){
            return response()->json([
                'status' => 'fail',
                'message' => 'Please! Fill Your password.'
            ]);   
        }
        if(Hash::check($password, $auth_user->password)){
            return response()->json([
                'status' => 'success',
                'message' => 'Your password is correct.'
            ]);
        }  
        return response()->json([
            'status' => 'fail',
            'message' => 'Sorry! Your password is incorrect.'
        ]);   
    }

    public function profile(){
        $user = Auth::user();
        // $user = Auth::guard('web')->user(); -----> Same result
        return view('frontend.profile', compact('user'));
    }
    
    public function updatePassword (){
        return view('frontend.update_password');
    }
    
    public function updatePasswordStore(UpdatePassword $request){
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        $user = Auth::guard('web')->user();
        if(Hash::check($old_password, $user->password)){
            $user->password = Hash::make($new_password);
            $user->update();

            $title = 'Changed Password!';
            $message = 'Your account password is successfully changed.';
            $source_id = $user->id;
            $source_type = User::class;
            $web_link = url('profile');
            $api_link = [
                'target' => 'profile',
                'param' => null
            ];


            Notification::send([$user], new GeneralNotification($title, $message, $source_id, $source_type, $web_link, $api_link));


            return redirect()->route('profile')->with('create', 'Succefully Updated Password');
        }
        return back()->withErrors(['old_password' => 'Sorry! The old password if incorrect'])->withInput();
    }
    
    public function transferHash(Request $request){
        $str = $request->phone.$request->amount.$request->description;
        $hash_value = hash_hmac('sha256', $str, 'magicpay12345#$@&^%');

        return response()->json([
            'status' => 'success',
            'data' => $hash_value
        ]);   
        
    }

}
