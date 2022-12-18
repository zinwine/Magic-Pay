<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\AdminUser;              
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminUser;
use App\Http\Requests\UpdateAdminUser;


class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.admin_user.index');
    }

    public function ssd()
    {
        $data = AdminUser::query();
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
        ->addColumn('action', function ($each)
        {
            $edit_icon = '<a href="' . route('admin.admin-user.edit', $each->id) .'" class="text-warning"><i class="fas fa-edit"></i></a>';
            $delete_icon = '<a href="" data-id="' . $each->id. '" class="text-danger delete_admin_user"><i class="fas fa-trash"></i></a>';
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
        return view('backend.admin_user/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminUser $request)
    {
        $admin_user = new AdminUser();
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = Hash::make($request->password);
        $admin_user->save();
        return redirect()->route('admin.admin-user.index')->with('create', 'Successfully Created Admin User');
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
        $admin_user = AdminUser::findOrFail($id);
        return view('backend.admin_user/edit', compact('admin_user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminUser $request, $id)
    {
        $admin_user = AdminUser::findOrFail($id);
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = $request->password ? Hash::make($request->password) : $admin_user->password;
        $admin_user->update();
        return redirect()->route('admin.admin-user.index')->with('update', 'Successfully Updated Admin User');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin_user = AdminUser::findOrFail($id);
        $admin_user->delete();
        return "success";
    }
}
