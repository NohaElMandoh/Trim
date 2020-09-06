<?php

namespace Modules\Governorate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Governorate\Entities\Governorate;
use Modules\Governorate\Entities\GovernorateTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ApiGovernorateController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
        if (isset($_GET['include'])) {
            $this->fractal->parseIncludes($_GET['include']);
        }
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $governorates = Governorate::latest()->get();
        $resource = new Collection($governorates, new GovernorateTransformer);
        return response_api($this->fractal->createData($resource)->toArray());
    }
    /**
     * Display a find of the resource.
     * @return Response
     */
    public function find(Request $request)
    {
        $governorate = Governorate::findOrFail($request->id);
        $resource = new Item($governorate, new GovernorateTransformer);
        return response_api($this->fractal->createData($resource)->toArray());
    }
}