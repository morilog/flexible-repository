<?php
namespace Morilog\FlexibleRepository;

use Illuminate\Database\Eloquent\Model;
use Morilog\FlexibleRepository\Contracts\RepositoryInterface;

abstract class BaseEloquentRepository extends BaseRepository implements RepositoryInterface
{
    abstract protected function model();

    protected $model;

    protected function makeModel()
    {
        if ($this->model !== null) {
            return $this->model;
        }

        $model = $this->container->make($this->model());

        if (!$model instanceof Model) {
            throw new \RuntimeException('Model must be Eloquent Model');
        }

        $this->model = $model;

        return $this->model;
    }

    protected function buildQuery()
    {
        $query = $this->makeModel()->newQuery();

        if (!empty($this->orderBys)) {
            foreach ($this->orderBys as $field => $direction) {
                $query->orderBy($field, $direction);
            }
        }

        if (!empty($this->relations)) {
            $query->with($this->relations);
        }

        if ($this->criteriaCollection->isEmpty() === false) {
            foreach ($this->criteriaCollection as $criteria) {
                $criteria->apply($query, $this);
            }
        }

        if (!empty($this->onlyFields)) {
            $query->select($this->onlyFields);
        }

        $this->applyLimit($query);
        $this->applyOffset($query);

        $this->reset();

        return $query;
    }

    protected function applyLimit($query)
    {
        if (is_numeric($this->take) && $this->take > 0) {
            return $query->take($this->take);
        }

        return $query;
    }

    protected function applyOffset($query)
    {
        if (is_numeric($this->skip) && $this->skip >= 0) {
            return $query->skip($this->skip);
        }

        return $query;
    }

    public function find($id)
    {
        return $this->buildQuery()->find($id);
    }

    public function first()
    {
        return $this->buildQuery()->first();
    }

    public function findBy($field, $value)
    {
        return $this->buildQuery()->where($field, '=', $value)->first();
    }

    public function delete($id)
    {
        $model = $this->find($id);

        return $model->delete();
    }

    public function all()
    {
        return $this->buildQuery()->get();
    }

    public function paginate($perPage = 10)
    {
        return $this->buildQuery()->paginate($perPage);
    }

    public function save($data)
    {
        $model = null;

        if ($data instanceof Model) {
            $model = $data;
        } else if (is_array($data)) {
            $id = isset($data['id']) ? $data['id'] : null;

            if ($id === null) {
                $model = $this->makeModel()->newInstance($data);
            } else {
                $model = $this->find($id);
                unset($data['id']);
                $model->fill($data);
            }
        }

        if ($model === null) {
            throw new \InvalidArgumentException('Data is not storable');
        }

        $model->save();

        return $model;
    }

}