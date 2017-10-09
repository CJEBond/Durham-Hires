<?php

namespace App\Http\Controllers;

use View;
use App\Admin;
use App\Settings;
use CAuth;
use Illuminate\Http\Request;
use App\Http\Requests\newUser;
use App\Classes\Common;
use App\Classes\pdf;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = $request->get('_site');
        $error = session()->get('error', '');
        $users = Admin::where('site', $site->id)
              ->get();
        $hires = Settings::where('name', 'hiresManager')
              ->where('site', $site->id)
              ->firstOrFail();
        return view('settings.users.index')->with(['users' => $users, 'hires' => (int)$hires->value, 'error' => $error, 'site' => $site]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $site = $request->get('_site');
        return View::make('settings.users.new')->with(['site' => $site]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(newUser $requestUser)
    {
        //
        $site = Request()->get('_site');
        $user = new Admin;
        $userDetails = Common::getDetailsEmail($requestUser->email);
        $user->email = $userDetails->email;
        $user->user = $userDetails->username;
        $user->privileges = 0;
        $user->name = $userDetails->name;
        $user->site = $site->id;
        $user->save();
        return redirect()->route('admin.index', $site->slug);
    }

    /**
     * Save the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $site = $request->get('_site');
        if (!isset($request->admin)) {
            return redirect()->route('admin.index', $site->slug)->with(['error' => 'At least one user needs to be an admin.']);
        }
        $hiresEmail = '';
        $users = Admin::where('site', $site->id)
            ->get();
        foreach ($users as $user) {
            $priv = 0;
            if (isset($request->treasurer[$user->id])) {
                $priv += 1;
            }
            if (isset($request->admin[$user->id])) {
                $priv += 4;
                if ($request->hires == $user->id) {
                    $hiresEmail = $user->email;
                }
            }
            $user->privileges = $priv;
            $user->save();
        }
        if (!empty($hiresEmail)) {
            $hires = Settings::where('name', 'hiresManager')
                            ->where('site', $site->id)
                            ->update(['value' => $request->hires]);
            $hires = Settings::where('name', 'hiresEmail')
                            ->where('site', $site->id)
                            ->update(['value' => $hiresEmail]);
        }
        return redirect()->route('admin.index', $site->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($site, Admin $admin)
    {
        //
        $site = Request()->get('_site');
        if (!($admin->privileges & 4)) {
            $admin->delete();
            return redirect()->route('admin.index', $site->slug);
        } else {
            return redirect()->route('admin.index', $site->slug)->with(['error' => 'Cannot delete an admin user.']);
        }
    }

    public function pdfTest()
    {
        return pdf::createInvoice(16, false);
    }
}
