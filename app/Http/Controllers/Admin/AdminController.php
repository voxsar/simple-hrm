<?php

namespace App\Http\Controllers\Admin;

use Carbon;
use App\Classes\Reply;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Exports\AdminExport;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\Admin\CreateRequest;
use App\Http\Requests\Admin\Admin\UpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminBaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Crypt;


class AdminController extends AdminBaseController
{
    /**
     * Constructor for the Employees
     */

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Admin';
        $this->adminActive = 'active';
    }

    public function index()
    {
        return View::make('admin.admin.index', $this->data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajaxAdmin()
    {
        $user = admin();
        $result = Admin::select('id', 'name', 'email');
        return datatables()->eloquent($result)
            ->filter(function ($query) {
                if (request()->search['value']) {
                    $query->where('name', 'LIKE', '%' . request()->search['value'] . '%')
                        ->orWhere('email', 'LIKE', '%' . request()->search['value'] . '%');
                }
            })
            ->addColumn('edit', function ($row) use ($user) {
                if ($row->id != $user->id) {
                    return '<a  class="btn btn-sm purple"  href="' . route('admin.admin.edit', $row->id) . '" ><i class="fa fa-edit"></i> View/Edit</a>
                         &nbsp;<a href="javascript:;" onclick="del(' . $row->id . ',\'' . $row->name . '\');return false;" class="btn btn-sm red">
                         <i class="fa fa-trash"></i> Delete</a>';
                }
                return '<a  class="btn btn-sm purple"  href="' . route('admin.admin.edit', $row->id) . '" ><i class="fa fa-edit"></i> View/Edit</a>
                &nbsp';

            })
            ->escapeColumns(['edit', 'application_status'])
            ->make(false);
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        return View::make('admin.admin.create', $this->data);
    }

    /**
     * @param CreateRequest $request
     * @return array
     * @throws \Exception
     */
    public function store(CreateRequest $request)
    {
        try {
            $employee = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'last_login' => Carbon\Carbon::now('Asia/Kolkata'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        return Reply::redirect(route('admin.admin.index'), '</strong> successfully added to the Database');
    }

    /**
     * Show the form for editing the specified admin
     */
    public function edit($id)
    {
        $this->employeesActive = 'active';
        $this->admin_user = Admin::findOrFail($id);
        return View::make('admin.admin.edit', $this->data);
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return Reply::redirect(route('admin.admin.index'), '<strong>Success</strong> Updated Successfully');
    }

    /**
     * @param $id
     * @return array
     * Delete Admin  Completely
     */
    public function destroy($id)
    {
        $user = admin();
        if ($user->id == $id) {
            return Reply::error('unable to delete admin , admin is logged in');
        }

        Admin::destroy($id);

        return Reply::success('messages.successDelete');
    }

    //export Admin List
    public function export()
    {
        $fileName = 'Admin-' . time() . '.xlsx';
        if (request()->filled('s')) {
            return (new AdminExport(request()->input('s')))->download($fileName);
        }
        return (new AdminExport)->download($fileName);
    }
}
