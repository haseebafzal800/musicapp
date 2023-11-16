<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    public function admin()
    {
        $data['pageTitle'] = 'Dashboard';
        $data['dashboard'] = 'active';
        $data['dashboardOpend'] = 'menu-open';
        $data['dashboardOpening'] = 'menu-is-opening';
        // if(Auth::user()->hasRole('Admin')){
        //     $data['producers'] = Role::where('name', 'Producer')->first()->users->count();
        //     $data['clients'] = Role::where('name', 'Client')->first()->users->count();
        //     $data['meetings'] = MeetingModel::count();
        //     $data['todayMeetings'] = MeetingModel::whereDate('start', Carbon::today())->count();
        // }else{
        //     $data['producers'] = User::whereHas('roles', function ($query) {
        //             $query->where('client_id', Auth::user()->client_id)->where('name', 'Producer');
        //         })->count();
        //     $data['clients'] = User::whereHas('roles', function ($query) {
        //         $query->where('client_id', Auth::user()->client_id)->where('name', 'Client');
        //     })->count();
        //     $data['meetings'] = MeetingModel::where('client_id', Auth::user()->client_id)->count();
        //     $data['todayMeetings'] = MeetingModel::whereDate('start', Carbon::today())->where('client_id', Auth::user()->client_id)->count();
        // }
        // echo"<pre>";
        // print_r($data['producers']); die;
        return view('admin.dashboard', $data);
    }
}
