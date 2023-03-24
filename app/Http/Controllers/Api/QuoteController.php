<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quote\StoreRequest;
use App\Http\Requests\Quote\UpdateRequest;
use App\Http\Resources\Quote\QuoteResource;
use App\Models\Quote;
use App\Models\Service;
use App\Models\User;
use App\Repositories\QuoteRepositories;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

class QuoteController extends ApiController
{

    private $quoteRepositories;

    public function __construct(QuoteRepositories $quoteRepositories)
    {
        $this->quoteRepositories = $quoteRepositories;
        $this->middleware('permission:' . User::PERMISSIONS['quotes.index'])->only('index');
        $this->middleware('permission:' . User::PERMISSIONS['quotes.store'])->only('store');
        $this->middleware('permission:' . User::PERMISSIONS['quotes.update'])->only('update');
        $this->middleware('permission:' . User::PERMISSIONS['quotes.show'])->only('show');
        $this->middleware('permission:' . User::PERMISSIONS['quotes.delete'])->only('destroy');
    }
    /**
     * Listado de cotizaciones.
     *
     * @autor(a) Airaly Cañizales
     * @param Request $request
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $quotes = $this->quoteRepositories->filterQuotes(
                $request->orderBy,
                $request->date,
                $request->search
            );
            return QuoteResource::collection($quotes);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Guardar cotizaciones
     *
     * @autor(a) Airaly Cañizales
     * @param StoreRequest $request
     * @return QuoteResource|JsonResponse
     */
    public function store(StoreRequest $request): QuoteResource|JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $quote = new Quote($validatedData);
            $quote = $this->quoteRepositories->save($quote);
           
            $services = Service::select('id')->whereIn('description', Arr::pluck($validatedData['services'], 'value'))->get();
            $services = $services->pluck('id')->toArray();
            $this->quoteRepositories->sync($quote, $services);
            return new QuoteResource($quote);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Mostrar una cotizacion
     *
     * @autor(a) Airaly Cañizales
     * @param  int  $id
     * @return  QuoteResource|JsonResponse
     */
    public function show(int $id): QuoteResource|JsonResponse
    {
        try {
            $quote = $this->quoteRepositories->get($id);
            if ($quote) {
                return new QuoteResource($quote);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Actualizar cotizaciones
     *
     * @autor(a) Airaly Cañizales
     * @param UpdateRequest $request, int  $id
     * @return QuoteResource|JsonResponse
     */
    public function update(UpdateRequest $request, int $id): QuoteResource|JsonResponse
    {
        try {
            $quote = $this->quoteRepositories->get($id);
            if ($quote) {
                $validatedData = $request->validated();
                $quote->fill($validatedData);
                $quote = $this->quoteRepositories->save($quote);
            //    $this->quoteRepositories->syncWithoutDetaching($quote, $validatedData['services']);
                return new QuoteResource($quote);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Elimina una cotizacion
     *
     * @param  int  $id
     * @return QuoteResource|JsonResponse
     */
    public function destroy(int $id): QuoteResource|JsonResponse
    {
        try {
            $quote = Quote::whereId($id)->first();
            if ($quote) {
                $this->quoteRepositories->sync($quote, []);
                $quote = $this->quoteRepositories->delete($quote);
                return new QuoteResource($quote);
            }
            return $this->showNone();
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
