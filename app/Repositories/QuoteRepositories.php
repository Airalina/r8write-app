<?php

namespace App\Repositories;

use App\Models\Quote;

class QuoteRepositories extends BaseRepository
{
    public $relationMany;

    public function __construct(Quote $quote)
    {
        $this->relationMany = 'services';
        parent::__construct($quote, ['services', 'owner'], $this->relationMany);
    }

    public function filterQuotes($orderBy = 'id', $date, $querySearch = '', $pages = 10)
    {
        $queryFilter = $this->model->query();

        if (!empty($querySearch)) {
            $queryFilter = $this->model->where('description', 'LIKE', "%$querySearch%")
                ->orWhere('date', 'LIKE', "%$querySearch%")
                ->orWhere('user_id', 'LIKE', "%$querySearch%")->orWhereHas('owner', function ($query)  use ($querySearch) {
                    $query->where('first_name', 'LIKE', "%$querySearch%")
                    ->orWhere('last_name', 'LIKE', "%$querySearch%")
                    ->orWhere('email', 'LIKE', "%$querySearch%");
                });
        }

        if (!empty($date)) {
            $queryFilter = $queryFilter->whereDate('date', $date);
        }

        if (!empty($orderBy)) {
            $queryFilter = $queryFilter->orderBy($orderBy);
        }

        return $queryFilter->with($this->relationMany)->paginate($pages);
    }
}
