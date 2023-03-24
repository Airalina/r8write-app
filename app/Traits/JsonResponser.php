<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

trait JsonResponser
{
    protected function okResponse($data, $code = 200)
    {
        return response()->json(
            $data,
            $code
        );
    }

    protected function errorResponse($message, $code = 500)
    {
        return response()->json(
            [
                'data' => [
                    'error' => $message,
                    'code' => $code,
                ]
            ],
            $code
        );
    }

    protected function showCollection(mixed $collection, $code = 200)
    {

        return $this->okResponse([
            'data' => $collection
        ],
            $code
        );
    }

    protected function showOne($model, $message = 'Carga con éxito', $code = 200)
    {
        return $this->okResponse([
            'data' => $model,
            'message' => $message,
        ],
            $code
        );
    }

    protected function showNone()
    {
        return $this->okResponse([
            'message' => 'No se encontraron resultados',
        ],
            404
        );
    }

}
