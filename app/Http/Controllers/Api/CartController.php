<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\SalonResource;
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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Modules\Offer\Entities\Offer;
use Modules\Product\Entities\Product;
use Modules\Service\Entities\Service;

class CartController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }
    public function addItemToCart(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'item_id' => 'required',
            'quantity' => 'required',
            'type' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 401);
        }

        if ($request->has('type')) {
            if ($request->has('item_id')) {
                if ($request['type'] == 'service') {
                    $item = Service::find($request->item_id);
                }
                if ($request['type'] == 'offer') {
                    $item = Offer::find($request->item_id);
                }
                if ($request['type'] == 'product') {
                    $item = Product::find($request->item_id);
                }
            } else return response()->json(['errors' => 'لم يتم اختيار خدمة', 'success' => false], 401);
        } else return response()->json(['errors' => 'لم يتم تحديد نوع الخدمة', 'success' => false], 401);

        if ($item) {
            // $firstitem = Cart::where('user_id', $request->user()->id)->with('item.merchant')->first();
            // if ($firstitem) {
            //     if ($item->merchant_id != $firstitem->item->merchant_id)
            //     return response()->json(['errors' => 'تم تغير مقدم الخدمة', 'success' => false], 401);
            //     else {
            //         $price = $item->price;

            //         $readyItem = [
            //             $item->id => [
            //                 'quantity' => $request->quantity,
            //                 'price' => $price,
            //                 'note' => $request->note,
            //                 'item_type' =>  get_class($item)
            //             ]
            //         ];
            //         $request->user()->cart()->attach($readyItem);
            //     }
            // } else {
            $price = $item->price;

            // $readyItem = [
            //     'product_id' => $item->id,
            //     'quantity' => $request->quantity,
            //     'price' => $price,
            //     'note' => $request->note,
            //     'product_type' =>  get_class($item)

            // ];
            // $request->user()->cart()->create($readyItem);
            $readyItem = [
                $item->id => [
                    'quantity' => $request->quantity,
                    'price' => $price,
                    'product_type' =>  get_class($item)
                ]
            ];
            $request->user()->cart()->attach($readyItem);
            // }
        } else {
            return response()->json(['success' => false, 'errors' => 'المنتج غير موجود'], 200);
        }
        return response()->json(['success' => true, 'data' => $item], 200);
    }
    public function cartItems(Request $request)
    {
        $items = Cart::where('user_id', $request->user()->id)->get();
        return response()->json(['success' => true, 'data' => CartResource::collection($items)], 200);
    }

    public function updateCartItem(Request $request)
    {

        $validation = validator()->make($request->all(), [
            'row_id' => 'required',
            'quantity' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 401);
        }

        if ($request->quantity == 0) {
            DB::table('carts')
                ->where('user_id', $request->user()->id)
                ->where('id', $request->row_id)
                ->delete();
            return response()->json(['success' => true, 'data' => 'تم الحذف'], 200);
        }
        $item = Cart::where('user_id', $request->user()->id)->where('id', $request->row_id)->first();

        if (!empty($item)) {
         
            $item->update([
                'quantity' => $request->quantity,
            ]);
            return response()->json(['success' => true, 'data' => 'تم التحيث'], 200);
        } else  response()->json(['success' => false, 'data' => 'المنتج غير موجود'], 401);
    }

    public function deleteCartItem(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'row_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 401);
        }
        $item = Cart::where('user_id', $request->user()->id)
            ->where('id', $request->row_id)->first();

        if ($item) {
            DB::table('carts')
                ->where('user_id', $request->user()->id)
                ->where('id', $request->row_id)
                ->delete();
            return response()->json(['success' => true, 'data' => 'تم ألحذف'], 200);
        } else  response()->json(['success' => false, 'data' => 'المنتج غير موجود'], 401);
    }

    public function deleteAllCartItems(Request $request)
    {
        $request->user()->cart()->detach();
        return response()->json(['success' => true, 'data' => 'تم ألحذف'], 200);
    }

}
