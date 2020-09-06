<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Token;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use App\NotificationTransformer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password, 'is_active' => 1])) {
            if (auth()->user()->hasRole('captain')) {
                return response()->json(['message' => __('messages.Please login from captain app'), 'success' => false], 401);
            }
            $token = auth()->user()->createToken('Myapp')->accessToken;
            return response()->json(['data' => ['token' => $token, 'user' => new UserResource(User::find(auth()->id()))], 'success' => true], 200);
        } else {
            return response()->json(['message' => __('messages.UnAuthorised'), 'success' => false], 401);
        }
    }

    // Register api
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users,email|max:255',
            'phone'     => ['required', 'string', 'regex:/^(01)[0-9]{9}$/', 'unique:users,phone', 'min:11', 'max:11'],
            'birth_date' => 'required|date',
            'job'       => 'required|string|max:255',
            'governorate_id'    => 'required|exists:governorates,id',
            'city_id'           => 'required|exists:cities,id',
            'password'          => 'required|string|min:6|max:255|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['sms_token'] = random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9);
        User::create($data);
        return response()->json(['data' => $data['sms_token'], 'success' => true], 200);
    }

    // Activate api
    public function activate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sms_token'  => 'required|exists:users,sms_token'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $user   = User::where('sms_token', $request->sms_token)->firstOrFail();
        $user->is_active = 1;
        $user->save();
        $token = $user->createToken('Myapp')->accessToken;
        return response()->json(['data' => ['token' => $token, 'user' => new UserResource($user)], 'success' => true], 200);
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
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
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
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
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
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'success'   => true,
            'message'   => __('messages.Password changed successfuly')
        ]);
    }
    public function info()
    {
        return response()->json(['data' => new UserResource(User::find(auth()->id())), 'success' => true], 200);
    }

    public function add_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:user_app,captain_app'],
            'lang'  => 'required|in:ar,en'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
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
        $paginator  = auth()->user()->notifications()->latest()->paginate(10);
        $notifications = $paginator->getCollection();
        $resource = new Collection($notifications, new NotificationTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function read_notification(Request $request)
    {
        auth()->user()->notifications()->where('id', $request->id)->first()->markAsRead();
        return response()->json(['success' => true], 200);
    }

    public function update_image(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
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
            'birth_date'    => ['required', 'string', 'max:255'],
            'job'           => ['required', 'string', 'max:255'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'city_id'       => ['required', 'exists:cities,id']
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $data = $request->all();
        auth()->user()->update($data);
        return response()->json(['success' => true], 200);
    }
}
