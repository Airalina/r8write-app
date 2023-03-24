<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Models\User;

class LeadRepositories extends BaseRepository
{
    public function __construct(Lead $lead)
    {
        parent::__construct($lead, ['user']);
    }

    
}