<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $model;
    private $relations, $syncRelation;

    public function __construct(Model $model, array $relations = [], string $syncRelation = '')
    {
        $this->model = $model;
        $this->relations = $relations;
        $this->syncRelation = $syncRelation;
    }

    public function all()
    {
        $query = $this->model;
        if (!empty($this->relations)) {
            $query = $query->with($this->relations);
        }

        return $query->get();
    }

    public function get(int $id)
    {
        $query = $this->model->find($id);
        if (!empty($this->relations) && $query) {
            $query = $query->load($this->relations);
        }
        return $query;
    }

    public function save(Model $model)
    {
        $model->save();
        return $model;
    }

    public function delete(Model $model)
    {
        $model->delete();
        if (!empty($this->relations)) {
            $model = $model->load($this->relations);
        }
        return $model;
    }

    public function sync(Model $model, array $data)
    {
        $relation = $this->syncRelation;
        $model->$relation()->sync($data);
        return $model;
    }

    public function syncWithoutDetaching(Model $model, array $data)
    {
        $relation = $this->syncRelation;
        $model->$relation()->syncWithoutDetaching($data);
        return $model;
    }

    public function createRelation(Model $model, string $relation, array $data)
    {
        if ($model->$relation()) {
            $model->$relation()->create($data);
            $model = $model->load($relation);
        }
        return $model;
    }

}
