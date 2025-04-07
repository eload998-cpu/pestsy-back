<?php
namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Services\V1\User\DeleteService;
use App\Services\V1\User\DeleteValidation;
use App\Services\V1\User\IndexService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexService $indexService)
    {

        try {
            return response()->json($indexService->handle($request));
        } catch (\Exception $e) {
            \Log::error($e);
            return [
                'success'       => false,
                'titleResponse' => 'error',
                'textResponse'  => 'There was an unexpected error',
                'data'          => [],
            ];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request, DeleteValidation $deleteValidation, DeleteService $deleteService)
    {

        try {

            $request->merge(['id' => $id]);
            $deleteValidationResponse = $deleteValidation->handle($request->all());

            if ($deleteValidationResponse['success'] == false) {
                return response()->json($deleteValidationResponse);
            }
            $deleteServiceResponse = $deleteService->handle($request);
            return response()->json($deleteServiceResponse);
        } catch (\Throwable $e) {
            \Log::error($e);
            return [
                'success'       => false,
                'titleResponse' => 'error',
                'textResponse'  => 'There was an unexpected error',
                'data'          => [],
            ];
        }

    }
}
