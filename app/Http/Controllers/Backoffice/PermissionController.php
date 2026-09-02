<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Rbac\Permission;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(): View
    {
        $permissions = Permission::query()
            ->orderBy('module')
            ->orderBy('code')
            ->get()
            ->groupBy('module');

        return view('backoffice.permissions.index', compact('permissions'));
    }
}
