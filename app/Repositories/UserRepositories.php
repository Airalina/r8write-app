<?php

namespace App\Repositories;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserRepositories extends BaseRepository
{
    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    public function filterUsers($orderBy = 'id', $querySearch = '', $role = '', $pages = 10)
    {
        $queryFilter = ($role == 'seller') ? $this->model->role($role) : $this->model->whereHas('leads');

        if (!empty($querySearch)) {
            if ($role == 'lead') {
                $queryFilter = $queryFilter->where('first_name', 'LIKE', "%$querySearch%")
                    ->orWhere('last_name', 'LIKE', "%$querySearch%")
                    ->orWhere('email', 'LIKE', "%$querySearch%")
                    ->orWhere('dni', 'LIKE', "%$querySearch%")
                    ->orWhereHas('leads', function ($query)  use ($querySearch) {
                        $query->where('phone', 'LIKE', "%$querySearch%")
                            ->orWhere('site_url', 'LIKE', "%$querySearch%");
                    });
            } else {
                $queryFilter = $queryFilter->where(function ($query) use ($querySearch) {
                    $query->where('first_name', 'LIKE', "%$querySearch%")
                        ->orWhere('last_name', 'LIKE', "%$querySearch%")
                        ->orWhere('email', 'LIKE', "%$querySearch%")
                        ->orWhere('dni', 'LIKE', "%$querySearch%");
                });
            }
        }

        if (!empty($orderBy)) {
            $queryFilter = $queryFilter->orderBy($orderBy);
        }


        return $queryFilter->paginate($pages);
    }
}
