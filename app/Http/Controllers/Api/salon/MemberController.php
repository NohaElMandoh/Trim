<?php

namespace App\Http\Controllers\Api\salon;

use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResource;
use App\Http\Resources\ServiceResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Member;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Token;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use App\NotificationTransformer;
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Modules\Service\Entities\Service;
use Throwable;

class MemberController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    public function addMember(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'spaciality' => 'required',
            'description' => 'required',
            'address' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $data   = $request->all();
        // $data['for_children'] = (boolean) $request->for_children;
        $member = $request->user()->members()->create($data);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/members/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $member->update(['image' => 'uploads/members/' . $name]);
        }
        return response()->json(['success' => true, 'data' => new MemberResource($member)], 200);
    }
    
    public function members(Request $request)
    {

        $members = $request->user()->members()->paginate(10);

        return response()->json(['success' => true,'count'=>$members->count(), 'data' =>  MemberResource::collection($members)->response()->getData(true)], 200);
    }
    public function deleteMember(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'member_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }

        $member = Member::find($request->member_id);
        if ($member) {
            $member->delete();
            return response()->json(['success' => true, 'data' =>   __('messages.member deleted successfully')], 200);
        }
        else
        return response()->json(['success' => false, 'message' => __('messages.Member Not Exist')], 400);
    }
}
