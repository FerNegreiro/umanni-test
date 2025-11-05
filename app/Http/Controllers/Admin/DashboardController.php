<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Illuminate\View\View;

    class DashboardController extends Controller
    {
        public function index(): View
        {
            $totalUsers = User::count();
            $adminCount = User::where('role', 'admin')->count();
            $nonAdminCount = User::where('role', 'no-admin')->count();

            $stats = [
                'totalUsers' => $totalUsers,
                'adminCount' => $adminCount,
                'nonAdminCount' => $nonAdminCount,
            ];

            return view('admin.dashboard', compact('stats'));
        }
    }
    
