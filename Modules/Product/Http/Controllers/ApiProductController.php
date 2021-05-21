<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use League\Fractal;
use Modules\Product\Entities\ProductTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Validator;
use League\Fractal\Resource\Item;

class ApiProductController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $paginator  = Product::whereHas('shop', function ($query) {
            $query->where('is_active', 1);
        })->orderBy('order')->paginate(8);
        $products = $paginator->getCollection();
        $resource = new Collection($products, new ProductTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function ids(Request $request)
    {
        $products  = Product::orderBy('order')->whereIn('id', $request->ids)->get();
        $resource = new Collection($products, new ProductTransformer);
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'en.name'   => 'required|string|max:255',
            'ar.name'   => 'required|string|max:255',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $data   = $request->all();
        // $data['image']      = upload_image($request, 'image', 128, 128);
        $data['shop_id']    = auth()->id();
        $product            = Product::create($data);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/product/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $product->update(['image' => 'uploads/product/' . $name]);
        }
        $resource = new Item($product, new ProductTransformer);
        return response_api($this->fractal->createData($resource)->toArray(), true, __('messages.Product created successfully'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'en.name'   => 'required|string|max:255',
            'ar.name'   => 'required|string|max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $data   = $request->all();
        // if ($request->hasFile('image'))
        //     $data['image']  = upload_image($request, 'image', 128, 128);
        $product            = Product::where('id', $request->id)->where('shop_id', auth()->id())->firstOrFail();
        $product->update($data);
        
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/product/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $product->update(['image' => 'uploads/product/' . $name]);
        }
        $resource = new Item($product, new ProductTransformer);
        return response_api($this->fractal->createData($resource)->toArray(), true, __('messages.Product updated successfully'));
    }

    public function delete(Request $request)
    {
        $product            = Product::where('id', $request->id)->where('shop_id', auth()->id())->firstOrFail();
        $product->delete();
        return response_api([], true, __('messages.Product deleted successfully'));
    }

    public function me(Request $request) {
        $paginator  = Product::where('shop_id', auth()->id())->where('category_id', $request->category_id)->orderBy('order')->paginate(8);
        $products = $paginator->getCollection();
        $resource = new Collection($products, function ($product) {
            return [
                'id'            => (int) $product->id,
                'name'          => $product->name,
                'category_id'   => $product->category_id ?? '',
                'order'         => $product->order,
                'price'         => $product->price,
                'image'         => route('file_show', $product->image),
                'translations'  => $product->translations
            ];
        });
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }
}
