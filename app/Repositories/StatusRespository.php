<?php
namespace App\Repositories;

use App\Models\Status;

class StatusRespository
{

    private $status;
    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function find($id)
    {
        return $this->status->find($id);
    }

    public function findByCriteria($criteria)
    {
        return $this->status->where($criteria)->first();
    }

    public function getByCriteria($criteria)
    {
        return $this->status->where($criteria)->get();
    }

    public function get($with = [])
    {
        return $this->status->with($with)->get();
    }

    public function destroy($id)
    {
        $this->status->destroy($id);
    }

    public function updateByCriteria($criteria, $data)
    {
        return $this->status->where($criteria)->update($data);
    }

}
