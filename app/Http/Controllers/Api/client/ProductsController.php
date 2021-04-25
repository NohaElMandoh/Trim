<?php

namespace App\Http\Controllers\Api\client;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\ProductResource;
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
use Modules\Category\Entities\Category;
use Modules\Offer\Entities\Offer;
use Modules\Product\Entities\Product;
use Modules\Service\Entities\Service;

class ProductsController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    public function allCategories(Request $request)
    {
        if ($request->has('name')) {
        $categories = Category::where('for_offers',0)->whereTranslationLike('name', '%' . $request->name . '%')->orderBy('created_at', 'desc')->get();
   
        }
        else 
          $categories = Category::where('for_offers',0)->orderBy('created_at', 'desc')->get();
        return response()->json(['success' => true, 'data' =>CategoryResource::collection($categories)], 200);
    } 
    public function products(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'category_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }

        if ($request->has('name')) {
        $products = Product::where('category_id',$request->category_id)->whereTranslationLike('name', '%' . $request->name . '%')->orderBy('created_at', 'desc')->get();
        }
        else
        $products = Product::where('category_id',$request->category_id)->orderBy('created_at', 'desc')->get();

        return response()->json(['success' => true, 'data' =>ProductResource::collection($products)], 200);
    } 

}
