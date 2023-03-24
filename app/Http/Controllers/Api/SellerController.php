<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\StoreRequest;
use App\Http\Requests\Seller\UpdateRequest;
use App\Http\Requests\SellerRequest;
use App\Http\Resources\Seller\SellerResource;
use App\Models\User;
use App\Repositories\UserRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SellerController extends ApiController
{
    private $userRepositories;

    public function __construct(UserRepositories $userRepositories)
    {
        $this->userRepositories = $userRepositories;
        $this->middleware('permission:' . User::PERMISSIONS['sellers.index'])->only('index');
        $this->middleware('permission:' . User::PERMISSIONS['sellers.store'])->only('store');
        $this->middleware('permission:' . User::PERMISSIONS['sellers.update'])->only('update');
        $this->middleware('permission:' . User::PERMISSIONS['sellers.show'])->only('show');
        $this->middleware('permission:' . User::PERMISSIONS['sellers.delete'])->only('destroy');
    }

    /**
     * Listado de vendedores.
     *
     * @autor(a) Airaly Cañizales
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $sellers = $this->userRepositories->filterUsers(
                $request->orderBy,
                $request->search,
                User::ROLES['seller']
            );
            return SellerResource::collection($sellers);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Guardar vendedores
     *
     * @autor(a) Airaly Cañizales
     * @param StoreRequest $request
     * @return SellerResource|JsonResponse
     */
    public function store(StoreRequest $request): SellerResource|JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = bcrypt($request->password);
            $seller = new User($validatedData);
            $seller = $this->userRepositories->save($seller);
            $seller->assignRole(User::ROLES['seller']);
            return new SellerResource($seller);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Mostrar un vendedor
     *
     * @autor(a) Airaly Cañizales
     * @param  int  $id
     * @return  SellerResource|JsonResponse
     */
    public function show(int $id): SellerResource|JsonResponse
    {
        try {
            $seller = $this->userRepositories->get($id);
            if ($seller) {
                return new SellerResource($seller);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Actualizar vendedores
     *
     * @autor(a) Airaly Cañizales
     * @param UpdateRequest $request, int  $id
     * @return SellerResource|JsonResponse
     */
    public function update(UpdateRequest $request, int $id): SellerResource|JsonResponse
    {
        try {
            $seller = $this->userRepositories->get($id);
            if ($seller) {
                $validatedData = $request->validated();
                $seller->fill($validatedData);
                $seller = $this->userRepositories->save($seller);
                return new SellerResource($seller);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Elimina un vendedor
     *
     * @param  int  $id
     * @return SellerResource|JsonResponse
     */
    public function destroy(int $id): SellerResource|JsonResponse
    {
        try {
            $seller = $this->userRepositories->get($id);
            if ($seller) {
                $seller = $this->userRepositories->delete($seller);
                return new SellerResource($seller);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
