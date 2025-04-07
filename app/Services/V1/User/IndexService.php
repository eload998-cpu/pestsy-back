<?php
namespace App\Services\V1\User;

use App\Repositories\UserRespository;
use Illuminate\Http\Request;

class IndexService
{

    private $userRespository;

    public function __construct(UserRespository $userRespository)
    {
        $this->userRespository = $userRespository;
    }

    public function handle(Request $request)
    {
        try {

            return $this->userRespository->getTableData($request);
        } catch (\Exception $e) {
            \Log::error($e);
            return $response = [
                'success'       => false,
                'titleResponse' => 'error',
                'textResponse'  => 'There was an unexpected error',
                'data'          => [],
            ];
        }
    }

}
