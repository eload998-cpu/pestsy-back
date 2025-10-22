<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\File\CreateFileRequest;
use App\Models\Module\Permission;
use App\Models\Permission as AdministrationPermission;
use App\Models\User;
use DB;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PermissionController extends Controller
{
    private $file;
    private $paginate_size = 6;

    public function __construct(Permission $file)
    {
        $this->file = $file;

    }

    //
    public function index(Request $request)
    {
        $files        = $this->file;
        $search_value = $request->search;
        $user         = Auth::user();

        $files = $files->orderBy("created_at", "desc");
        $files = $files->whereRaw("LOWER(permissions.name) ILIKE '%{$search_value}%'");
        $files = $files->where("company_id", $user->company_id);

        $files = $files->paginate($this->paginate_size);
        $files = parsePaginator($files);

        return response()->json($files);
    }

    public function store(CreateFileRequest $request)
    {

        expiredAccountMessage();
        $user = Auth::user();
        updateConnectionSchema("modules");

        $data = DB::transaction(function () use ($request) {
            $path = "/public/files/Permissions";
            if (! Storage::exists($path)) {
                Storage::makeDirectory($path, 0755);

            }

            foreach ($request->files as $key => $value) {

                foreach ($value as $f) {

                    // Getting file name
                    $name = $f->getClientOriginalName();
                    $base = pathinfo($name, PATHINFO_FILENAME);

                    $base = preg_replace('/\(\d+\)/', '', $base);

                    $base     = trim($base);
                    $name     = substr($base, 0, 19) . '.pdf';
                    $filename = rand() . '_' . $name;

                    $path = Storage::disk('public')->putFileAs('files/Permissions', new File($f), $filename);
                    // Location
                    $location = storage_path() . "/app/public/files/Permissions/{$filename}";
                    // Compress Image
                    $storage_link = "/storage/files/Permissions/{$filename}";

                    $file = Permission::create([
                        'name'       => $name,
                        'file_url'   => $storage_link,
                        'company_id' => $request->company_id,

                    ]);
                }

            }
            exec('chmod -R 755 ' . storage_path() . '/app/public/files');

        });

        return response()->json(
            ["success" => true,
                "data"     => [],
                "message"  => "Exito!",
            ]
        );

    }

    //FUNCTIONALITY STOPPED FOR NOW
    public function addPermissions(Request $request)
    {
        updateConnectionSchema("administration");
        $permissions = [];

        $user = User::where('client_id', $request->id)->first();

        foreach ($request->value as $value) {
            $permission = AdministrationPermission::where('name', 'like', '%' . $value . "_" . $request->type . '%')->first();
            array_push($permissions, $permission);
        }

        return response()->json($permissions);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file      = Permission::find($id);
        $file_path = str_replace("/storage/", "", $file->file_url);
        Storage::disk('public')->delete($file_path);

        $file = Permission::destroy($id);

        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    public function download($id)
    {

        expiredAccountMessage();

        updateConnectionSchema("modules");

        $file      = Permission::find($id);
        $file_path = str_replace("/storage/", "", $file->file_url);
        $file_name = basename($file_path);
        $headers   = [
            'Content-Description'           => 'File Transfer',
            'Content-Disposition'           => 'attachment; filename=' . basename($file_path) . '',
            'Content-Type'                  => 'application/pdf',
            'Access-Control-Expose-Headers' => 'Content-Disposition',
        ];

        return Storage::disk('public')->download($file_path, $file_name, $headers);

    }

}
