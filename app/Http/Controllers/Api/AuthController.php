<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserListResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->merge(['mobile' => $this->check_mobile($request->mobile ?? null)]);

        $validator = Validator::make($request->all(), [
            'username'  => 'sometimes|required',
            'email'     => 'sometimes|required|email',
            'mobile'    => 'sometimes|required|string',
            'password'  => 'required|string|confirmed',
            'user_role' => 'exists:user_roles,id'
        ]);

        $response = [
            'status' => false,
            'message' => trans('validation.unknown_error'),
            'result' => null
        ];

        if ($validator->fails()) {
            $response['messages'] = $this->normalizeError($validator->errors());
            $response['message']  = 'بروز خطا هنگام اعتبارسنجی اطلاعات ارسالی';

            return response()->json($response, 400);
        }

        if (isset($request->username)) {
            if ($request->username === null or strlen($request->username) <= 3) {
                $response['message'] = trans('validation.username_required');
                return response()->json($response, 400);
            }

            if (!preg_match("^(?=.{4,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$", $request->username)) {
                $response['message'] = trans('validation.username_not_valid');
                return response()->json($response, 400);
            }

            if (User::whereUsername($request->username)->count() > 0) {
                $response['message'] = trans('validation.username_is_taken');
                return response()->json($response, 400);
            }
        }
        if (isset($request->email)) {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $response['message'] = trans('validation.email_not_valid');
                return response()->json($response, 400);
            }

            if (User::whereEmail($request->email)->count() > 0) {
                $response['message'] = trans('validation.email_is_taken');
                return response()->json($response, 400);
            }
        }
        if (isset($request->mobile)) {
            if (!isset($request->mobile)) {
                $response['message'] = trans('validation.mobile_not_sent');
                return response()->json($response, 400);
            }

            if ($request->mobile === false) {
                $response['message'] = trans('validation.mobile_not_valid');
                return response()->json($response, 400);
            }

            if (User::whereMobile($request->mobile)->count() > 0) {
                $response['message'] = trans('validation.mobile_is_taken');
                return response()->json($response, 400);
            }
        }

        if (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$/', $request->password)) {
            $response['message'] = trans('validation.password_not_valid');
            return response()->json($response, 400);
        }

        try {
            $request->merge(['password' => Hash::make($request->password)]);
            $user = User::create($request->all());

            $response = [
                'status' => true,
                'message' => trans('auth.user_created'),
                'result' => $user
            ];
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return $response()->json($response, 500);
        }

        return response()->json($response, 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'  => 'required',
            'password'  => 'required',
        ]);

        $response = [
            'status' => false,
            'message' => trans('validation.unknown_error'),
            'result' => null
        ];

        if ($validator->fails()) {
            $response['messages'] = $this->normalizeError($validator->errors());
            $response['message']  = 'بروز خطا هنگام اعتبارسنجی اطلاعات ارسالی';

            return response()->json($response, 400);
        }
        $username_field = $this->username($request->username);
        if ($username_field==='mobile') {
            $request->merge(['username' => $this->check_mobile($request->username)]);
        }
        $request->merge([$username_field => $request->username]);

        if (Auth::attempt($request->only([$username_field, 'password']))) {
            $user = new UserListResource(Auth::user());

            $response = [
                'status' => true,
                'message' => trans('auth.login_success'),
                'token' => $user->createToken(config('app.name'))->plainTextToken,
                'result' => $user
            ];

            return response()->json($response, 200);
        }

        $response['message'] = trans('auth.failed');
        return response()->json($response, 400);
    }

    public function username($username)
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        if (preg_match("/(\+98|0|98|0098|)[9](0|1|2|3|4|5|9)\d{8}/", $username)) {
            return 'mobile';

        }

        return 'username';
    }

    private function check_mobile($mobile) {
        if ($mobile === null) {
            return null;
        }

        $mobile_pattern = '/(\+98|0|98|0098|)[9](0|1|2|3|4|5|9)\d{8}/';
        if (preg_match($mobile_pattern, $mobile)) {
            return substr($mobile, strlen($mobile) - 10, 10);
        }

        return false;
    }
}
