<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\File\CreateFileRequest;
use App\Models\Module\ManagementPlan;
use DB;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManagementPLanController extends Controller
{
    private $file;
    private $paginate_size = 6;

    public function __construct(ManagementPLan $file)
    {
        $this->file = $file;

    }

    //
    public function index(Request $request)
    {
        $user         = Auth::user();
        $client_id    = $request->order_id;
        $search_value = $request->search;

        if (empty($client_id)) {
            $user_role = $user->roles()->first()->name;
            switch ($user_role) {

                case 'system_user':
                    updateConnectionSchema("modules");
                    $client_id = $user->systemUsers()->first()->id;

                    break;

            }
        }

        $files = $this->file->where('client_id', $client_id);

        $files = $files->whereRaw("LOWER(management_plans.name) ILIKE '%{$search_value}%'");
        $files = $files->orderBy("created_at", "desc");

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
            $path = "/public/files/ManagementPLan/{$request->client_id}";
            if (! Storage::exists($path)) {
                Storage::makeDirectory($path, 0755);

                $folder_path = str_replace('public', 'storage', $path);

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

                    $path = Storage::disk('public')->putFileAs('files/ManagementPLan/' . $request->client_id, new File($f), $filename);
                    // Location
                    $location = storage_path() . "/app/public/files/ManagementPLan/{$request->client_id}/{$filename}";
                    // Compress Image
                    $storage_link = "/storage/files/ManagementPLan/{$request->client_id}/{$filename}";

                    $file = ManagementPLan::create([
                        'name'      => $name,
                        'file_url'  => $storage_link,
                        'client_id' => $request->client_id,

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file      = ManagementPLan::find($id);
        $file_path = str_replace("/storage/", "", $file->file_url);
        Storage::disk('public')->delete($file_path);
        ManagementPLan::destroy($id);

        return response()->json(['success' => true, 'message' => 'Exito']);

    }

    public function download($id)
    {

        expiredAccountMessage();

        updateConnectionSchema("modules");

        $file      = ManagementPLan::find($id);
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
