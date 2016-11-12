<?php
namespace Morilog\FlexibleRepository\Tests;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;
use Morilog\FlexibleRepository\BaseRepository;

class InMemoryUserRepository extends BaseRepository implements UserRepository
{

    protected $dataSource;

    /**
     * @return Collection
     */
    protected function dataSource()
    {
        if (!$this->dataSource instanceof Collection) {
            $fileContent = file_get_contents(__DIR__ . '/user_mock_data.json');
            $this->dataSource = collect(json_decode($fileContent, true));
        }

        return $this->dataSource;
    }

    protected function buildQuery()
    {
        $dataSource = $this->dataSource();

        for ($i = 0; $i < $this->skip; $i++) {
            $dataSource->shift();
        }


        $dataSource = $dataSource->take($this->take);

        $this->reset();

        return $dataSource;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->dataSource()->where('id', '=', $id)->first();
    }

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function findBy($field, $value)
    {
        return $this->dataSource()->where($field, $value)->first();
    }

    /**
     * @param $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->dataSource = $this->dataSource()->filter(function ($user) use ($id) {
            return $user['id'] !== $id;
        });

        return true;
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return $this->buildQuery();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function save($data)
    {
        // TODO: Implement save() method.
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 10)
    {
        $items = $this->buildQuery();

        return new \Illuminate\Pagination\LengthAwarePaginator($items,$items->count(), $perPage);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->buildQuery()->first();
    }
}