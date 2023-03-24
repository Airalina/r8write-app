<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lead\StoreRequest;
use App\Http\Requests\Lead\UpdateRequest;
use App\Http\Resources\Lead\LeadResource;
use App\Models\Lead;
use App\Models\User;
use App\Repositories\LeadRepositories;
use App\Repositories\UserRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeadController extends ApiController
{
    private $userRepositories, $leadRepositories;

    public function __construct(UserRepositories $userRepositories, LeadRepositories $leadRepositories)
    {
        $this->userRepositories = $userRepositories;
        $this->leadRepositories = $leadRepositories;
        $this->middleware('permission:' . User::PERMISSIONS['leads.index']. '|' . User::PERMISSIONS['quotes.index'])->only('index');
        $this->middleware('permission:' . User::PERMISSIONS['leads.store'])->only('store');
        $this->middleware('permission:' . User::PERMISSIONS['leads.update'])->only('update');
        $this->middleware('permission:' . User::PERMISSIONS['leads.show'])->only('show');
        $this->middleware('permission:' . User::PERMISSIONS['leads.delete'])->only('destroy');
    
}

    /**
     * Listado de clientes potenciales.
     *
     * @autor(a) Airaly Cañizales
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $leads = $this->userRepositories->filterUsers(
                $request->orderBy,
                $request->search,
                User::ROLES['lead']
            );
            return LeadResource::collection($leads);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Guardar clientes potenciales
     *
     * @autor(a) Airaly Cañizales
     * @param StoreRequest $request
     * @return LeadResource|JsonResponse
     */
    public function store(StoreRequest $request): LeadResource|JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = bcrypt($request->password);
            $lead = new User($validatedData);
            $lead = $this->userRepositories->save($lead);
            $lead->assignRole(User::ROLES['lead']);
            $this->userRepositories->createRelation($lead, 'leads', $validatedData);
            return new LeadResource($lead);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Mostrar un cliente potencial
     *
     * @autor(a) Airaly Cañizales
     * @param  int  $id
     * @return  LeadResource|JsonResponse
     */
    public function show($id): LeadResource|JsonResponse
    {
        try {
            $lead = $this->leadRepositories->get($id);
            if ($lead) {
                return new LeadResource($lead->user);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Actualizar clientes potenciales
     *
     * @autor(a) Airaly Cañizales
     * @param UpdateRequest $request, int  $id
     * @return LeadResource|JsonResponse
     */
    public function update(UpdateRequest $request, $id): LeadResource|JsonResponse
    {
        try {
            $lead = $this->leadRepositories->get($id);
            if ($lead) {
                $validatedData = $request->validated(); 
                $lead->fill($validatedData);
                $lead->user->fill($validatedData);
                $lead = $this->leadRepositories->save($lead);
                $this->userRepositories->save($lead->user);
                return new LeadResource($lead->user);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Elimina un cliente potencial
     *
     * @param  int  $id
     * @return LeadResource|JsonResponse
     */
    public function destroy($id): LeadResource|JsonResponse
    {
        try {
            $lead = $this->leadRepositories->get($id);
            if ($lead) {
                $lead = $this->leadRepositories->delete($lead);
                $this->userRepositories->delete($lead->user);
                return new LeadResource($lead->user);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
