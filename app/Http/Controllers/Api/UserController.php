<?php

namespace App\Http\Controllers\Api;

use App\Helpers\MojSkin;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserListResource;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public $avatar_path = 'uploads/user_avatars';
    public function list()
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام دریافت فهرست کاربران',
            'result' => null
        ];

        try {
            $users = UserListResource::collection(User::orderBy('username')->with('details', 'role')->get());
            $roles = UserRole::orderBy('title')->get();
            $response = [
                'status' => true,
                'message' => 'فهرست کاربران با موفقیت دریافت شد.',
                'result' => [
                    'users' => $users,
                    'roles' => $roles,
                ]
            ];
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function saveUser(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'بروز خطا هنگام ذخیره اطلاعات کاربران',
            'result' => null
        ];

        try {
            $validator = Validator::make($request->all(), [
                'fName' => 'required|min:2|max:30',
                'lName' => 'required|min:3|max:50',
                'username' => 'required|min:5|max:25',
                'email' => 'required_without:mobile'.(isset($request->email) ? '|email' : ''),
                'mobile' => 'required_without:email',
            ]);

            if ($validator->fails()) {
                $messages = $this->normalizeError($validator->errors()->toArray());
                $response['message'] = $this->normalizeError($validator->errors())[0] ?? 'بروز خطا هنگام اعتبارسنجی اطلاعات ارسالی';
                $response['status']  = false;

                return response()->json($response, 200);
            }

            if (!isset($request->id)) {
                $validator = Validator::make($request->all(), [
                    'username' => 'unique:users,username',
                ]);

                if ($validator->fails()) {
                    $response['message'] = 'نام کاربری ارسالی قبلا انتخاب شده است';
                    $response['status']  = false;

                    return response()->json($response, 400);
                }

                $validator = Validator::make($request->all(), [
                    'email' => 'unique:users,email',
                ]);

                if ($validator->fails()) {
                    $response['message'] = 'پست الکترونیک ارسالی قبلا انتخاب شده است';
                    $response['status']  = false;

                    return response()->json($response, 400);
                }

                if (isset($request->mobile)) {
                    $request->merge(['mobile' => MojSkin::check_mobile($request->mobile)]);
                    $validator = Validator::make($request->all(), [
                        'mobile' => 'unique:users,mobile',
                    ]);

                    if ($validator->fails()) {
                        $response['message'] = 'تلفن همراه ارسالی قبلا انتخاب شده است';
                        $response['status']  = false;

                        return response()->json($response, 400);
                    }
                }
            }

            if (!isset($request->user_role_id) && $request->user_role_id > 0) {
                $validator = Validator::make($request->all(), [
                    'user_role_id' => 'exists:user_roles,id',
                ]);

                if ($validator->fails()) {
                    $response['message'] = 'نقش کاربری انتخاب شده معتبر نیست';
                    $response['status']  = false;

                    return response()->json($response, 400);
                }
            }

            if ($request->user_role_id < 1) {
                $request->merge(['user_role_id' => null]);
            }

            $user_data = [
                'username' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'user_role_id' => $request->user_tole_id,
            ];

            if (isset($request->password) and strlen($request->password) > 0) {
                if (isset($request->confirm_password)) {
                    if ($request->password !== $request->confirm_password) {
                        $response['message'] = 'کلمه عبور با تایید آن مطابقت ندارد';
                        $response['status']  = false;

                        return response()->json($response, 400);
                    }
                }
                $user_data['password'] = Hash::make($request->password);
            }

            if (isset($request->id)) {
                $user = User::whereId($request->id)->with('details')->first();
                $avatar = UserProfile::whereUserId($request->id)->whereLang('fa')->whereTitle('avatar')->first();

                if ($avatar) {
                    MojSkin::unlinkFile(storage_path('storage/'.$this->avatar_path.'/'.$avatar->value));
                }
            }

            $user = User::updateOrCreate(
                ['id' => $request->id],
                $user_data
            );

            UserProfile::whereUserId($user->id)->whereIn('title', ['fName', 'lName', 'avatar'])->delete();

            $user_details = [
                ['user_id' => $user->id, 'title' => 'fName', 'value' => $request->fName, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now()],
                ['user_id' => $user->id, 'title' => 'lName', 'value' => $request->lName, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now()],
            ];

            if (isset($request->avatar)) {
                MojSkin::makeDir(public_path('storage/'.$this->avatar_path));
                $fileName = MojSkin::randomFileName(10, public_path('/storage/'.$this->avatar_path), 'u'.$user->id.'-avatar' , '', 'png', true);
                $saveFullPath = public_path('/storage/'.$this->avatar_path.'/'.$fileName);
                $avatarData   = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->avatar));
                $avatar       = Image::make($avatarData)->resize(300, 300);
                $avatar->save($saveFullPath);
                $avatar = $fileName;
                $user_details[] = ['user_id' => $user->id, 'title' => 'avatar', 'value' => $avatar, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now()];
            }

            UserProfile::insert($user_details);
            $user = new UserListResource(User::whereId($user->id)->with('details', 'role')->first());
            $response = [
                'status' => true,
                'message' => 'مشخصات کاربر با موفقیت ذخیره شد.',
                'result' => $user
            ];
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function getUserProfile(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'کاربر مورد نظر یافت نشد!',
            'result' => null
        ];

        $user = isset($request->username) ? User::whereUsername($request->username) : User::whereId(Auth::id());
        $user = $user->with(['details', 'role'])->first();
        if ($user) {
            $user = new UserListResource($user);
            $response['status'] = true;
            $response['message'] = 'مشخصات کاربر با موفقیت دریافت شد';
            $response['result'] = $user;
            return response()->json($response, 200);
        }

        return response()->json($response, 404);
    }

    public function updateUserProfile(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'خطا هنگام بروزرسانی پروفایل کاربر',
            'result' => null
        ];

        $user = User::whereUsername($request->username)->first();
        if (!isset($request->username) or !$user) {
            $response['message'] = 'کاربر مورد نظر یافت نشد!';
            return response()->json($response, 404);
        }

        if ($request->username != Auth::user()->username) {
            $response['message'] = 'شما دسترسی لازم جهت انجام این کار را ندارید!';
            return response()->json($response, 403);
        }

        try {
            if (isset($request->avatar)) {
                $avatar = UserProfile::whereUserId($user->id)->whereLang('fa')->whereTitle('avatar')->first();
                if ($avatar) {
                    MojSkin::unlinkFile(public_path('storage/'.$this->avatar_path.'/'.$avatar->value));
                    $avatar->delete();
                }

                if ($request->avatar != -1) {
                    MojSkin::makeDir(public_path('storage/'.$this->avatar_path));
                    $fileName = MojSkin::randomFileName(10, public_path('/storage/'.$this->avatar_path), 'u'.$user->id.'-avatar' , '', 'png', true);
                    $saveFullPath = public_path('/storage/'.$this->avatar_path.'/'.$fileName);
                    $avatarData   = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->avatar));
                    $avatar       = Image::make($avatarData)->resize(300, 300);
                    $avatar->save($saveFullPath);
                    $avatar = $fileName;
                    UserProfile::create([
                        'user_id' => $user->id,
                        'title' => 'avatar',
                        'value' => $avatar
                    ]);
                }
            }

            if (isset($request->fName) and isset($request->lName)) {
                $user_data = [];
                if (isset($request->uName)) {
                    if (!strlen($request->uName) < 5) {
                        $response['message'] = 'نام کاربری جدید معتبر نیست';
                        return response()->json($response, 403);
                    }
                    if (strtolower(trim($request->uName)) !== $user->username) {
                        $checkUsername = User::whereUsername(strtolower(trim($request->uName)))->count();
                        if ($checkUsername > 0) {
                            $response['message'] = 'این نام کاربری قبلا انتخاب شده است';
                            return response()->json($response, 403);
                        }
                        $user_data[] = ['username' => strtolower(trim($request->uName))];
                    }
                }

                if (isset($request->email)) {
                    if (!strlen($request->email) < 5 or !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                        $response['message'] = 'پست الکترونیک جدید معتبر نیست';
                        return response()->json($response, 403);
                    }

                    if (strtolower(trim($request->email)) !== $user->email) {
                        $checkEmail = User::whereEmail(strtolower(trim($request->email)))->count();
                        if ($checkEmail > 0) {
                            $response['message'] = 'این پست الکترونیک قبلا انتخاب شده است';
                            return response()->json($response, 403);
                        }

                        $user_data[] = ['email' => strtolower(trim($request->email))];
                        $user_data[] = ['email_verified_at' => null];
                    }
                }

                if (isset($request->mobile)) {
                    $request->merge(['mobile' => self::check_mobile($request->mobile)]);
                    if (!strlen($request->mobile) !== 10) {
                        $response['message'] = 'تلفن همراه جدید معتبر نیست';
                        return response()->json($response, 403);
                    }

                    if ($request->mobile !== $user->mobile) {
                        $checkMobile = User::whereEmail(strtolower(trim($request->mobile)))->count();
                        if ($checkMobile > 0) {
                            $response['message'] = 'این شماره تلفن همراه قبلا انتخاب شده است';
                            return response()->json($response, 403);
                        }

                        $user_data[] = ['mobile' => strtolower(trim($request->mobile))];
                        $user_data[] = ['mobile_verified_at' => null];
                    }
                }

                if (strlen($request->fName) > 0 and strlen($request->lName) > 0) {
                    UserProfile::whereUserId($user->id)->whereIn('title', ['fName', 'lName', 'slogan', 'about'])->delete();
                    $user_details = [
                        ['user_id' => $user->id, 'title' => 'fName', 'value' => $request->fName, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now()],
                        ['user_id' => $user->id, 'title' => 'lName', 'value' => $request->lName, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now()],
                    ];
                    if ($request->slogan) {
                        $user_details[] = ['user_id' => $user->id, 'title' => 'slogan', 'value' => $request->slogan, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now()];
                    }
                    if ($request->about) {
                        $user_details[] = ['user_id' => $user->id, 'title' => 'about', 'value' => $request->about, 'created_at' => Carbon::now(),  'updated_at' => Carbon::now()];
                    }
                    UserProfile::insert($user_details);
                }
            }

            if (isset($request->key)) {
                UserProfile::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'lang' => 'fa',
                        'title' => $request->key
                    ],
                    [
                        'user_id' => $user->id,
                        'lang' => 'fa',
                        'title' => $request->key,
                        'value' => json_encode($request->detail)
                    ]
                );
            }


            $response['message'] = 'اطلاعات عمومی پروفایل با موفقیت ذخیره گردید';
            $response['status'] = true;
            $response['result'] = User::whereUsername($request->username)->with([])->first();
            $response['userData'] = new UserListResource($response['result']);

            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();

            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function checkUserStatus(Request $request)
    {
        if (Auth::check() && Auth::user()->username == $request->user) {
            return response()->json(200);
        }
        return response()->json(403);
    }

    private static function check_mobile($mobile) {
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
