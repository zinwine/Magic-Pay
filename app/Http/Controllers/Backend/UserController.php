<?php

namespace App\Http\Controllers\Backend;

use App\Wallet;
use Carbon\Carbon;
use App\User;              
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerator;
use App\Http\Requests\StoreUser;
use Yajra\Datatables\Datatables;
use App\Http\Requests\UpdateUser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.user.index');
    }

    public function ssd()
    {
        $data = User::query();
        return Datatables::of($data)
        ->editColumn('user_agent', function($each){
            if($each->user_agent){
            $agent = new Agent();
            $agent->setUserAgent($each->user_agent);
            $device = $agent->device();
            $platform = $agent->platform();
            $browser = $agent->browser();

            return '<table class="table table-bordered">
            <tr>
                <td>Device</td>
                <td>'.$device.'</td>
            </tr>
            <tr>
                <td>Browser</td>
                <td>'.$browser.'</td>
            </tr>
            <tr>
                <td>Platform</td>
                <td>'.$platform.'</td>
            </tr>
        </table>';
    }
    return ' -';

        })
        ->editColumn('created_at', function($each){
            return Carbon::parse($each->created_at)->format('d-M-Y');
        })
        ->editColumn('updated_at', function($each){
            return Carbon::parse($each->updated_at)->format('d-M-Y H:i');
        })
        ->editColumn('login_at', function($each){
            return Carbon::parse($each->login_at)->format('d-M-Y H:i');
        })
        ->addColumn('action', function ($each)
        {
            $edit_icon = '<a href="' . route('admin.user.edit', $each->id) .'" class="text-warning"><i class="fas fa-edit"></i></a>';
            $delete_icon = '<a href="" data-id="' . $each->id. '" class="text-danger delete_user"><i class="fas fa-trash"></i></a>';
            return '<div class="action-icon">' . $edit_icon . $delete_icon . '</div>';
        })
        ->rawColumns(['user_agent', 'action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.user/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        DB::beginTransaction();
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            Wallet::firstOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'account_number' => UUIDGenerator::accountNumber(),
                    'amount' => 0
                ]
            );
            DB::commit();

            return redirect()->route('admin.user.index')->with('create', 'Successfully Created Admin User');

        }catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors(['fail' => 'Something Wrong' . $e->getMessage()])->withInput();
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user/edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, $id)
    {
        DB::beginTransaction();
        try{

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        $user->update();

        Wallet::firstOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'account_number' => UUIDGenerator::accountNumber(),
                'amount' => 0
            ]
        );
        DB::commit();
        return redirect()->route('admin.user.index')->with('update', 'Successfully Updated User');
    }catch(\Exception $e){
        DB::rollBack();
        return back()->withErrors(['fail' => 'Something Wrong ' . $e->getMessage()])->withInput();
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return "success";
    }
}
