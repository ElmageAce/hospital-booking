<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Role;
use App\Http\Resources\Role as RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index(Role $role): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => RoleResource::collection($role->allRoles())
        ]);
    }
}
