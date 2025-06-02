<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Device\CreateDeviceRequest;
use App\Http\Requests\Administration\Device\UpdateDeviceRequest;
use App\Models\Module\Device;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{

    private $device;
    private $paginate_size = 6;

    public function __construct(Device $device)
    {
        $this->device = $device;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $devices = $this->device;
        $user    = Auth::user();

        if ($request->search) {
            $search_value = $request->search;
            $devices      = $devices->whereRaw("LOWER(devices.name) ILIKE '%{$search_value}%'");

        }
        $devices = $devices->whereNull('company_id')
            ->orWhere('company_id', $user->company_id);

        if ($request->sort) {
            switch ($request->sortBy) {

                case 'name':
                    $devices = $devices->orderBy("name", $request->sort);
                    break;
            }

        } else {
            $devices = $devices->orderBy("devices.created_at", "desc");

        }

        $devices = $devices->paginate($this->paginate_size);
        $devices = parsePaginator($devices);

        return response()->json($devices);
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
    public function store(CreateDeviceRequest $request)
    {
        DB::transaction(function () use ($request) {

            $device = Device::create($request->all());

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
        $device = Device::find($id);

        return response()->json(['success' => true, 'data' => $device]);

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
    public function update(UpdateDeviceRequest $request, $id)
    {

        DB::transaction(function () use ($request, $id) {

            $data = $request->all();
            unset($data["_method"]);

            $device = Device::where('id', $id)->update($data);

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

        $device = DesinfecDevicetionMethod::where('id', $id)->where('is_general', false);
        if ($user_role == "super_administrator") {
            $device = $device->orWhere('is_general', true);
        }
        $device = $device->delete();
        return response()->json(['success' => true, 'message' => 'Exito']);

    }
}
