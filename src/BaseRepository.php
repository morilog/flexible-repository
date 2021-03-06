<?php
namespace Morilog\FlexibleRepository;

use Illuminate\Contracts\Container\Container;
use Morilog\FlexibleRepository\Contracts\CriteriaInterface;
use Morilog\FlexibleRepository\Contracts\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $relations;

    /**
     * @var array
     */
    protected $orderBys;

    /**
     * @var array
     */
    protected $onlyFields;

    /**
     * @var null|integer
     */
    protected $take;

    /**
     * @var null|integer
     */
    protected $skip;

    /**
     * @var CriteriaCollection
     */
    protected $criteriaCollection;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->reset();
    }

    public function orderBy($field, $direction = 'asc')
    {
        $this->orderBys[$field] = $direction;

        return $this;
    }

    public function onlyFields(array $fields = [])
    {
        $this->onlyFields = $fields;

        return $this;
    }

    public function applyCriteria(CriteriaInterface $criteria)
    {
        $this->criteriaCollection->push($criteria);

        return $this;
    }

    public function with($relations)
    {
        if (is_string($relations)) {
            $this->relations[] = $relations;

            return $this;
        }

        if (is_array($relations)) {
            $this->relations = array_merge($this->relations, $relations);

            return $this;
        }

        throw new \InvalidArgumentException('relation must be string or array');
    }

    public function skip($number = 0)
    {
        $this->skip = $number;

        return $this;
    }

    public function take($number = 10)
    {
        $this->take = $number;

        return $this;
    }


    protected function reset()
    {
        $this->orderBys = [];
        $this->relations = [];
        $this->onlyFields = [];
        $this->skip = null;
        $this->take = null;
        $this->criteriaCollection = new CriteriaCollection([]);
    }
}