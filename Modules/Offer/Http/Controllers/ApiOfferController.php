<?php

namespace Modules\Offer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Offer\Entities\Offer;
use League\Fractal;
use Modules\Offer\Entities\OfferTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ApiOfferController extends Controller
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
        $paginator = null;
        if ($request->has('lat') && $request->has('lng')) {
            $paginator  = Offer::whereHas('shop', function ($query) use ($request) {
                $query->where('is_active', 1)->whereHas('branches', function ($query) use ($request) {
                    $query->select(DB::raw('*, ( 6367 * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
                        ->having('distance', '<', 20)
                        ->orderBy('distance');
                });
            })->where('category_id', $request->category_id)->orderBy('order')->paginate(10);
        } else {
            $paginator  = Offer::whereHas('shop', function ($query) use ($request) {
                $query->where('is_active', 1);
            })->where('category_id', $request->category_id)->orderBy('order')->paginate(10);
        }
        $offers = $paginator->getCollection();
        $resource = new Collection($offers, new OfferTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function ids(Request $request)
    {
        $offers  = Offer::orderBy('order')->whereIn('id', $request->ids)->get();
        $resource = new Collection($offers, new OfferTransformer);
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'en.name'   => 'required|string|max:255',
            'ar.name'   => 'required|string|max:255',
            'en.description'   => 'required|string|max:255',
            'ar.description'   => 'required|string|max:255',
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
        $product            = Offer::create($data);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/offers/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $product->update(['image' => 'uploads/offers/' . $name]);
        }
        $resource = new Item($product, new OfferTransformer);
        return response_api($this->fractal->createData($resource)->toArray(), true, __('messages.Offer created successfully'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'en.name'   => 'required|string|max:255',
            'ar.name'   => 'required|string|max:255',
            'en.description'   => 'nullable|string|max:255',
            'ar.description'   => 'nullable|string|max:255',
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
        $product            = Offer::where('id', $request->id)->where('shop_id', auth()->id())->firstOrFail();
        $product->update($data);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/offers/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $product->update(['image' => 'uploads/offers/' . $name]);
        }
        $resource = new Item($product, new OfferTransformer);
        return response_api($this->fractal->createData($resource)->toArray(), true, __('messages.Offer updated successfully'));
    }

    public function delete(Request $request)
    {
        $product            = Offer::where('id', $request->id)->where('shop_id', auth()->id())->firstOrFail();
        $product->delete();
        return response_api([], true, __('messages.Offer deleted successfully'));
    }

    public function me()
    {
        $paginator  = Offer::where('shop_id', auth()->id())->orderBy('order')->paginate(8);
        $products = $paginator->getCollection();
        $resource = new Collection($products, function ($offer) {
            return [
                'id'            => (int) $offer->id,
                'name'          => $offer->name,
                'category_id'   => $offer->category_id ?? '',
                'description'   => $offer->description,
                'order'         => $offer->order,
                'price'         => $offer->price,
                'image'         => route('file_show', $offer->image),
                'translations'  => $offer->translations
            ];
        });
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }
}
