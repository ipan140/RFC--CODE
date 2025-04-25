<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk Admin.
     *
     * @return \Illuminate\View\View
     */
    public function admin()
    {
        return view('dashboardadmin.index', [
            'title' => 'Admin Dashboard',
        ]);
    }

    /**
     * Menampilkan dashboard untuk Owner.
     *
     * @return \Illuminate\View\View
     */
    public function owner()
    {
        return view('dashboardowner.index', [
            'title' => 'Owner Dashboard',
        ]);
    }

    /**
     * Menampilkan dashboard untuk User.
     *
     * @return \Illuminate\View\View
     */
    public function user()
    {
        return view('dashboarduser.index', [
            'title' => 'User Dashboard',
        ]);
    }
}
