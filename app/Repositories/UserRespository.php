<?php
namespace App\Repositories;

use App\Models\Status;
use App\Models\StatusType;
use App\Models\User;
use Illuminate\Http\Request;

class UserRespository
{

    private $user;
    private $paginate_size = 6;
    private $status;
    private $statusType;
    public function __construct(User $user, Status $status, StatusType $statusType)
    {
        $this->user       = $user;
        $this->status     = $status;
        $this->statusType = $statusType;
    }

    public function find($id)
    {
        return $this->user->find($id);
    }

    public function getByCriteria($criteria)
    {
        return $this->user->where($criteria)->get();
    }

    public function get($with = [])
    {
        return $this->user->with($with)->get();
    }

    public function destroy($id)
    {
        $this->user->destroy($id);
    }

    public function updateByCriteria($criteria, $data)
    {
        return $this->user->where($criteria)->update($data);
    }

    public function getTableData(Request $request)
    {
        try {
            $users = $this->user;
            $users = $users->where('email', '!=', 'felcast999@gmail.com');

            if ($request->search) {
                $search_value = $request->search;
                $users        = $users->whereRaw("LOWER(users.first_name) || LOWER(users.last_name) || LOWER(users.cellphone) || LOWER(users.email)  ILIKE '%{$search_value}%'");

            }

            if ($request->sort) {
                switch ($request->sortBy) {

                    case 'name':
                        $users = $users->orderByRaw("users.first_name || users.last_name {$request->sort}");

                        break;

                    case 'email':
                        $users = $users->orderBy('email', $request->sort);
                        break;

                }

            } else {
                $users = $users->orderBy("users.created_at", "desc");

            }

            $status_type = $this->statusType->where('name', 'plan')->first();
            $status      = $this->status->where('status_type_id', $status_type->id)->where('name', 'active')->first();

            $users = $users
                ->with(['subscriptions' => function ($query) use ($status) {
                    $query->wherePivot('status_id', $status->id);
                }])
                ->paginate($this->paginate_size);

            $users = parsePaginator($users);

            return $users;

        } catch (\Exception $e) {
            \Log::error($e);
            throw $e;
        }
    }
}
