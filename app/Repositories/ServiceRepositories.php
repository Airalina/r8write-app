<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Support\Arr;

class ServiceRepositories extends  BaseRepository
{
    public function __construct(Service $service)
    {
        parent::__construct($service);
    }

    public function getServices(array $servicesData)
    {
        $services = $this->model->select('id')
            ->whereIn('description', Arr::pluck($servicesData, 'value'))
            ->get();
        $services = $services->pluck('id')
            ->toArray();
        return $services;
    }
}
