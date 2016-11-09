<?php
namespace Morilog\FlexibleRepository\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface RepositoryInterface
 * @package Morilog\FlexibleRepository\Contracts
 */
interface RepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function findBy($field, $value);

    /**
     * @param $id
     * @return boolean
     */
    public function delete($id);

    /**
     * @return Collection
     */
    public function all();

    /**
     * @param $data
     * @return mixed
     */
    public function save($data);

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 10);

    /**
     * @param CriteriaInterface $criteria
     * @return static
     */
    public function applyCriteria(CriteriaInterface $criteria);

    /**
     * @param array $fields
     * @return static
     */
    public function onlyFields(array $fields = []);

    /**
     * @param $relations
     * @return static
     */
    public function with($relations);

    /**
     * @param $field
     * @param string $direction
     * @return static
     */
    public function orderBy($field, $direction = 'asc');

    /**
     * @param int $number
     * @return static
     */
    public function take($number = 10);

    /**
     * @param int $number
     * @return static
     */
    public function skip($number = 0);

}

