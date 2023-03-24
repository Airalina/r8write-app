<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepositories extends  BaseRepository
{
    public function __construct(Service $service)
    {
        parent::__construct($service);
    }

/*    public function getWithSameFirstAndLastName(string $name)
    {
        $first = $this->model->where('first_name', $name);

        return $this->model->where('last_name', $name)
            ->union($first)
            ->get();
    }*/

}