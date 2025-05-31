<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Product\CreateProductRequest;
use App\Http\Requests\Administration\Product\UpdateProductRequest;
use App\Models\Module\Product;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    private $product;
    private $paginate_size = 6;

    public function __construct(Product $product)
    {
        $this->product = $product;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->product;
        $user     = Auth::user();

        if ($request->search) {
            $search_value = $request->search;
            $products     = $products->whereRaw("LOWER(products.name) ILIKE '%{$search_value}%'");

        }
        $products = $products->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $products = $products->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $products = $products->orderBy("products.created_at", "desc");

        }

        $products = $products->paginate($this->paginate_size);

        $products = parsePaginator($products);

        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        DB::transaction(function () use ($request) {

            $product = product::create($request->all());

        });

        return response()->json(['success' => true, 'message' => 'Exito']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = product::find($id);

        return response()->json(['success' => true, 'data' => $product]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $product = product::where('id', $id)->update($data);

        });

        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        $user      = Auth::user();
        $user_role = $user->roles()->first()->name;

        $product = product::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $product = $product->orWhere('is_general', true);
        }
        $product = $product->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
