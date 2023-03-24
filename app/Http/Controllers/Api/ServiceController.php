<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Service\ServiceResource;
use App\Models\Service;
use App\Models\User;
use App\Repositories\ServiceRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceController extends ApiController
{
    private $serviceRepositories;

    public function __construct(ServiceRepositories $serviceRepositories)
    {
        $this->serviceRepositories = $serviceRepositories;
        $this->middleware('permission:' . User::PERMISSIONS['services.index'] . ',api')->only('index');
    }

    /**
     * Listado de servicios.
     *
     * @autor(a) Airaly Cañizales
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $services = $this->serviceRepositories->all();
            foreach ($services as $value) {
                $array[$value->id] = $value->description;
            }
            return $this->okResponse($array);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
