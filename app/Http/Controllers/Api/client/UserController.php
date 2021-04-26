<?php

namespace App\Http\Controllers\Api\client;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Token;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use App\NotificationTransformer;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    use SendsPasswordResetEmails;

    // Login api
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 402);
        }
        $req_phone = '+2' . $request->text;
        if ((Auth::attempt(['phone' => $req_phone, 'password' => $request->password])) ||
            (Auth::attempt(['email' => $request->text, 'password' => $request->password]))
        ) {
            if (auth()->user()->is_active == 0) {
                return response()->json(['success' => false, 'message' => __('messages.user account not activated')], 401);
            } else {
                if (auth()->user()->hasRole('captain')) {
                    return response()->json(['success' => false, 'message' => __('messages.Please login from captain app')], 401);
                }
                $token = auth()->user()->createToken('Myapp')->accessToken;

                return response()->json(['success' => true, 'data' => ['token' => $token, 'user' => new UserResource(User::find(auth()->id()))]], 200);
            }
        } else {
            return response()->json(['success' => false, 'message' => __('messages.UnAuthorised')], 401);
        }
    }
    // getVerificationCode api
    public function getVerificationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 400);
        }
        $user = User::where('email', $request->text)->first();
        return $user;
        if ((Auth::attempt(['phone' => $request->text, 'password' => $request->password])) ||
            (Auth::attempt(['email' => $request->text, 'password' => $request->password]))
        ) {

            $userCode = User::find(auth()->id())->sms_token;

            return response()->json(['success' => true, 'data' => ['code' => $userCode, 'user' => new UserResource(User::find(auth()->id()))]], 200);
        } else {
            return response()->json(['success' => false, 'message' => __('messages.UnAuthorised')], 401);
        }
    }
    // reset password api
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 402);
        }

        $req_phone = '+2' . $request->text;
        $user = User::where('email', $request->text)->orWhere('phone', $req_phone)->first();

        if ($user) {

            $sms_token = random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9);

            $result = $user->update([
                'sms_token' => $sms_token,
                'is_active' => 0
            ]);
            if ($result > 0) {
                // try {
                // Notification::send($user, new \App\Notifications\activateuser($user));
                $smsstatus = $this->send($user->phone, $user->sms_token);
                // } catch (Throwable $e) {
                //     info('nexmo message not sent');
                // }
                $token = $user->createToken('Myapp')->accessToken;
                return response()->json(['success' => true, 'data' => ['token' => $token, 'user' => new UserResource($user), 'smsStatus' => $smsstatus]], 200);
            } else return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
        } else {
            return response()->json(['success' => false, 'message' => __('messages.user does not exist')], 400);
        }
    }
    // Register api
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users,email|max:255',
            'phone'     => ['required', 'string',  'unique:users,phone', 'min:11', 'max:11'],
            'gender' => ['required', 'string', 'max:255', 'in:male,female'],
            'password'          => 'required|string|min:6|max:255|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 402);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => '+2' . $request->phone,
            'gender' => $request->gender,
            'password' => bcrypt($request->password),
            'sms_token' => random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9),
        ]);
        $token = $user->createToken('Myapp');
        $smsstatus = $this->send($user->phone, $user->sms_token);

        $smsstatus = "";
        return response()->json(['success' => true, 'data' => ['token' => $token, 'user' => new UserResource($user), 'sms status' => $smsstatus]], 200);
    }
    // social register
    public function socialRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            // 'email'     => 'required|string|email|unique:users,email|max:255',
            'provider' => 'required', //facebook,gmail,...etc
            'provider_id' => 'required', //SocialUserId
            'provider_token' => 'required', // 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 402);
        }
        $user = User::where('provider_id', $request->provider_id)->first();
        if ($user) {

            $token = $user->createToken('Myapp')->accessToken;

            return response()->json(['success' => true, 'data' => ['token' => $token, 'user' => new UserResource($user)]], 200);
        } else {
            $data = $request->all();
            $data['sms_token'] = random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9);

            $user = User::create($data);
            $token = $user->createToken('Myapp');
            $smsstatus = $this->send($user->phone, $user->sms_token);
            return response()->json(['success' => true, 'data' => ['token' => $token->accessToken, 'user' => new UserResource($user), 'smsstatus' => $smsstatus]], 200);
        }
    }
    // Activate api
    public function activate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sms_token'  => 'required|exists:users,sms_token'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 402);
        }
        $user = $request->user();
        if ($user->sms_token == $request->sms_token) {
            $user->is_active = 1;
            $user->save();
            $request->user()->token()->revoke();
            $token = $user->createToken('Myapp')->accessToken;
            return response()->json(['data' => ['success' => true, 'token' => $token, 'user' => new UserResource($user)]], 200);
        } else
            return response()->json(['success' => false, 'message' => __('messages.this code not valid')], 400);
    }

    // Gender api
    public function gender(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gender'  => ['required', 'in:male,female']
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 402);
        }
        $user   = $request->user();
        $user->gender = $request->gender;
        $user->save();
        return response()->json(['success' => true, 'data' => ['user' => new UserResource($user)]], 200);
    }
    // Logout
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status'    => 'success',
            'message'   => __('messages.Successfully logged out')
        ]);
    }

    // Reset email
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required', 'exists:users,phone'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 400);
        }
        $token = random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9);
        DB::table('password_resets')->insert(
            ['phone' => $request->phone, 'token' => $token, 'created_at' => date('Y-m-d H:i')]
        );
        return response()->json([
            'success'   => true,
            'data'      => $token,
            'message'   => __('messages.Enter the code you have recived')
        ]);
    }

    public function newPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'  => 'required|string|min:6|max:255|confirmed',
            'token'     => 'required|exists:password_resets,token'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 400);
        }
        if ($token_row = DB::table('password_resets')->where('token', $request->token)->first()) {
            $user = User::where('phone', $token_row->phone)->latest()->first();
            $user->password = bcrypt($request->password);
            $user->save();
            DB::table('password_resets')->where('token', $request->token)->delete();
            return response()->json([
                'success'   => true,
                'message'   => __('messages.Password changed successfuly')
            ]);
        }
        return response()->json([
            'success'   => false,
            'message'   => __('messages.Invalid token')
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'  => 'required|string|min:6|max:255|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 402);
        }
        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();
        // return response()->json([
        //     'success'   => true,
        //     'message'   => __('messages.Password changed successfuly')
        // ]);
        return response()->json(['success' => true, 'data' => ['user' => new UserResource($user)]], 200);
    }

    public function info()
    {
        return response()->json(['success' => true, 'data' => new UserResource(User::find(auth()->id()))], 200);
    }

    public function add_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:user_app,captain_app'],
            'lang'  => 'required|in:ar,en'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 400);
        }
        $data = [
            'token'     => $request->token,
            'type'      => $request->type,
            'lang'      => $request->lang,
            'user_id'   =>   auth('api')->id() ?? null
        ];
        $token = Token::where('token', $request->token)->where('type', $request->type)->first();
        if (!$token) {
            $token = Token::create($data);
        } else {
            $token->update($data);
        }
        return response()->json(['success' => true], 200);
    }

    public function get_notifications()
    {
        // $paginator  = auth()->user()->notifications()->latest()->paginate(10);
        // $notifications = $paginator->getCollection();
        // $resource = new Collection($notifications, new NotificationTransformer);
        // $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        // return response_api($this->fractal->createData($resource)->toArray());
        $notifications=auth()->user()->notifications()->latest()->get();
        return $notifications;
    }

    public function read_notification(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 400);
        }
        auth()->user()->notifications()->where('id', $request->id)->first()->markAsRead();
        return response()->json(['success' => true], 200);
    }

    public function update_image(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 400);
        }
        $data = $request->all();
        $data['image'] = upload_image($request, 'image', 200, 200);
        auth()->user()->update($data);
        return response()->json(['success' => true], 200);
    }

    public function update_info(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', Rule::unique('users', 'email')->ignore(auth()->id()), 'max:255'],
            'phone'         => ['required', 'string', Rule::unique('users', 'phone')->ignore(auth()->id()), 'max:255'],

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 400);
        }

        $data = $request->all();
        $data['image']      = $request->hasFile('image') ? upload_image($request, 'image', 200, 200) : 'user.png';
        $data['cover']      = $request->hasFile('cover') ? upload_image($request, 'cover', 200, 200) : 'user.png';

        if ($request->has('password')) {

            $data['password']   = bcrypt($request->password);
        }

        auth()->user()->update($data);

        return response()->json(['success' => true, 'data' => new UserResource(User::find(auth()->id()))], 200);
    }

    public function profile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['string', Rule::unique('users')->ignore($request->user()->id)],
            // 'email'     => 'string|email|unique:users,email|max:255'.$request->user()->id,
            'phone' => ['string', 'min:11', 'max:11', Rule::unique('users')->ignore($request->user()->id)],
            // 'phone'     => [ 'unique:users,phone', 'min:11', 'max:11'],
            'password'          => 'string|min:6|max:255|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 402);
        }

        if ($request->has('name')) {
            $request->user()->update($request->only('name'));
        }
        if ($request->has('email')) {
            $request->user()->update($request->only('email'));
        }
        if ($request->has('phone')) {
            $phone = '+2' . $request->phone;
            $request->user()->update(['phone' => $phone]);
        }

        if ($request->has('image')) {
            $img     = $request->hasFile('image') ? upload_image($request, 'image', 200, 200) : 'user.png';
            $request->user()->update(['image' => $img]);
        }

        if ($request->has('cover')) {
            $cover      = $request->hasFile('cover') ? upload_image($request, 'cover', 200, 200) : 'user.png';
            $request->user()->update(['cover' => $cover]);
        }

        if ($request->has('password')) {
            $pass  = bcrypt($request->password);
            $request->user()->update(['password' => $pass]);
        }


        return response()->json(['success' => true, 'data' => new UserResource(User::find(auth()->id()))], 200);
    }

    public function sms(Request $request)
    {

        return $this->send('201224201414', '123');
    }
    public function send($mobile, $code)
    {

        $result = $this->curl_request(
            "https://smsmisr.com/api/v2/",
            [
                'username' => '9m41IUZP',
                'password' => '6kPlUPMNgf',
                'language' => 2,
                'sender' => 'Beauty',
                'mobile' =>  $mobile,
                'message' => 'Your verification code is : ' . $code . ''
            ],
            [
                'Content-Type: application/json',
                'Accept: application/json',
                'Accept-Language: en-US'
            ]
        );

        $result = json_decode($result, true);
        if (isset($result['code']) && in_array($result['code'], [1901, 6000])) return true;
        return false;
    }

    private function curl_request($url, $fields, $headers = [])
    {
        $payload = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
