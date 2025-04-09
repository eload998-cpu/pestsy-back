<?php
namespace App\Services\V1\User;

use App\Repositories\StatusRespository;
use App\Repositories\StatusTypeRespository;
use App\Repositories\UserRespository;
use Illuminate\Http\Request;

class ShowService
{

    private $userRespository;
    private $statusRespository;
    private $statusTypeRespository;

    public function __construct(UserRespository $userRespository, StatusRespository $statusRespository, StatusTypeRespository $statusTypeRespository)
    {
        $this->userRespository       = $userRespository;
        $this->statusRespository     = $statusRespository;
        $this->statusTypeRespository = $statusTypeRespository;

    }

    public function handle(Request $request)
    {
        try {

            $user = $this->userRespository->find($request->id);

            $status_type = $this->statusTypeRespository->findByCriteria(['name' => 'plan']);
            $status      = $this->statusRespository->findByCriteria(['status_type_id' => $status_type->id, 'name' => 'active']);

            $user->load('city.state.country');

            $user->load(['subscriptions' => function ($query) use ($status) {
                $query->wherePivot('status_id', $status->id);
            }]);
     
            $response = [
                'success'       => true,
                'titleResponse' => 'Success',
                'textResponse'  => 'User found successfully',
                'data'          => $user,
            ];
            return $response;
        } catch (\Exception $e) {
            \Log::error($e);
            return $response = [
                'success'       => false,
                'titleResponse' => 'error',
                'textResponse'  => 'There was an error',
                'data'          => [],
            ];
        }
    }

}
