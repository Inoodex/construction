<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Models\Privilege;

class DashboardController extends Controller
{
    public function index()
    {
        $userModel = config('tyro-dashboard.user_model', 'App\\Models\\User');

        $stats = [
            'total_users' => class_exists($userModel) ? $userModel::count() : 0,
            'total_roles' => class_exists(Role::class) ? Role::count() : 0,
            'total_privileges' => class_exists(Privilege::class) ? Privilege::count() : 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
