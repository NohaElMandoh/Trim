<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;
use League\Fractal;
use Modules\Category\Entities\CategoryTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class ApiCategoryController extends Controller
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
        $categories = [];
        if ($request->has('is_shop')) {
            $categories = Category::where('is_shop', $request->is_shop)->latest()->get();
        } else {
            $categories = Category::latest()->get();
        }
        $resource = new Collection($categories, new CategoryTransformer);
        return response_api($this->fractal->createData($resource)->toArray());
    }
}
