<?php
namespace App\Services\V1\User;

use App\Classes\SchemaBuilder;
use App\Repositories\UserRespository;
use Illuminate\Http\Request;

class DeleteService
{

    private $userRespository;
    private SchemaBuilder $schema;

    public function __construct(UserRespository $userRespository, SchemaBuilder $schema)
    {
        $this->userRespository = $userRespository;
        $this->schema          = $schema;

    }

    public function handle(Request $request)
    {
        try {

            $user = $this->userRespository->find($request->id);

            $this->schema->destroySchema("modules");
            $user = $this->userRespository->destroy($request->id);

            $response = [
                'success'       => true,
                'titleResponse' => 'Success',
                'textResponse'  => 'User deleted successfully',
                'data'          => [],
            ];
            return $response;
        } catch (\Exception $e) {
            \Log::error($e);
            return $response = [
                'success'       => false,
                'titleResponse' => 'error',
                'textResponse'  => 'There was an error deleting the user',
                'data'          => [],
            ];
        }
    }

}
