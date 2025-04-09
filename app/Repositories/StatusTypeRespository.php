<?php
namespace App\Repositories;

use App\Models\StatusType;

class StatusTypeRespository
{

    private $statusType;
    public function __construct(StatusType $statusType)
    {
        $this->statusType = $statusType;
    }

    public function find($id)
    {
        return $this->statusType->find($id);
    }

    public function findByCriteria($criteria)
    {
        return $this->statusType->where($criteria)->first();
    }

    public function getByCriteria($criteria)
    {
        return $this->statusType->where($criteria)->get();
    }

    public function get($with = [])
    {
        return $this->statusType->with($with)->get();
    }

    public function destroy($id)
    {
        $this->statusType->destroy($id);
    }

    public function updateByCriteria($criteria, $data)
    {
        return $this->statusType->where($criteria)->update($data);
    }

}
