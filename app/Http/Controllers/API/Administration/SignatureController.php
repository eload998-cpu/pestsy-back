<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;

use App\Http\Requests\Administration\Order\Signature\CreateSignatureRequest;

use App\Models\Module\Signature;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class SignatureController extends Controller
{
    private $signature;
    private $paginate_size = 5;

    public function __construct(Signature $signature)
    {
        $this->signature = $signature;

    }

    //
    public function index(Request $request)
    {
        $signatures = $this->signature
            ->select('signatures.*')
            ->leftJoin('orders', 'signatures.order_id', 'orders.id')
            ->where('orders.id', $request->order_id);

        $signatures = $signatures->orderBy("signatures.created_at", "desc");

        $signatures = $signatures->paginate($this->paginate_size);
        $signatures = parsePaginator($signatures);

        return response()->json($signatures);
    }

    public function store(CreateSignatureRequest $request)
    {
        $data = DB::transaction(function () use ($request) {

            $string = Str::random(10);
            $base64_image = $request->signature_pad_client;
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $imageName = $string . '.' . 'png';

            $client_path = "order/{$request->order_id}/signatures/{$imageName}";
            Storage::disk('public')->put($client_path, base64_decode($file_data));

            $string = Str::random(10);
            $base64_image = $request->signature_pad_worker;
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);
            $imageName = $string . '.' . 'png';

            $worker_path = "order/{$request->order_id}/signatures/{$imageName}";
            Storage::disk('public')->put($worker_path, base64_decode($file_data));

            $signature = Signature::firstOrNew(['order_id' => $request->order_id]);
            $signature->client_signature_url = "/storage/{$client_path}";
            $signature->worker_signature_url = "/storage/{$worker_path}";
            $signature->save();

        });

        return response()->json(
            ["success" => true,
                "data" => [],
                "message" => "Exito!",
            ]
        );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = Signature::find($id);
        return response()->json(['success' => true, 'data' => $model]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $signature = Signature::destroy($id);
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
