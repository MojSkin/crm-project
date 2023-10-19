<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserPermissionResource;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function getMainList()
    {
        new UserPermissionResource(UserRole::orderBy('title')->with(['permissions', 'users'])->first(), 'salam');
        $response = [
            'status' => true,
            'message' => 'فهرست نقش‌های کاربری با موفقیت دریافت شد',
            'result' => [
                'roles' => UserPermissionResource::collection(UserRole::orderBy('title')->with(['permissions', 'users'])->get()),
                'permissions' => Crypt::encrypt(config('mojskin-permissions.permissions', []))
            ]
        ];

        return response()->json($response, 200);
    }

    public function saveRole(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'خطا در ذخیره نقش کاربری',
            'result' => null
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:4,max:250',
            'default_route' => 'required|min:1,max:250',
        ]);

        if ($validator->fails()) {
            $response['messages'] = $this->normalizeError($validator->errors());

            return response()->json($response, 400);
        }

        try {
            $raw_permissions = Crypt::decrypt($request->permissions);
            unset($request->permissions);

            $role = UserRole::updateOrCreate(
                ['id' => $request->id],
                $request->all()
            );

            $permissions = [];

            foreach ($raw_permissions as $perm) {
                $permissions[] = [
                    'user_role_id' => $role->id,
                    'action' => $perm,
                ];
            }

            UserRolePermission::whereUserRoleId($role->id)->delete();
            UserRolePermission::insert($permissions);

            $role = new UserPermissionResource(UserRole::whereId($role->id)->with(['permissions', 'users'])->first());

            $response = [
                'status' => true,
                'message' => 'نقش کاربری با موفقیت ذخیره شد',
                'result' => $role
            ];

        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }
}
