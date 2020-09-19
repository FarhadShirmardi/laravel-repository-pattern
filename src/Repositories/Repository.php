<?php

namespace Derakht\RepositoryPattern\Repositories;

use Exception;
use Illuminate\Container\Container as App;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

/** @property Model $model */
abstract class Repository implements RepositoryInterface
{
    public $paginator;
    protected $modelQuery;
    protected $model;
    private $app;

    /**
     * Repository constructor.
     * @param App $app
     * @throws BindingResolutionException
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeQuery();
    }

    /**
     * @return Builder
     * @throws BindingResolutionException
     * @throws Exception
     */
    protected function makeQuery()
    {
        $this->model = $this->app->make($this->model());

        if (!$this->model instanceof Model) {
            throw new Exception(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->modelQuery = $this->model->newQuery();
    }

    /**
     * @return mixed
     */
    abstract protected function model();

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string[] $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->modelQuery->get($columns);
    }

    /**
     * @param int $perPage
     * @param string[] $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->modelQuery->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->modelQuery->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     * @throws Exception
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $keys = ['_token', '_method', 'XDEBUG_SESSION_START'];
        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                unset($data[$key]);
            }
        }
        $updated = $this->modelQuery
            ->where($attribute, '=', $id)
            ->update($data);

        if (!$updated) {
            throw new Exception(
                "Update model {$this->model()} was unsuccessful"
            );
        }

        return $updated;
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values)
    {
        return $this->modelQuery->updateOrCreate($attributes, $values);
    }

    /**
     * @param $id
     * @return int
     * @throws Exception
     */
    public function delete($id)
    {
        $status = $this->model->destroy($id);
        if (!$status and !is_array($id) and !empty($id)) {
            throw new Exception(
                "Unable to delete {$this->model()} with id: {$id}"
            );
        }
        return $status;
    }

    /**
     * @param $id
     * @param string[] $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param string[] $columns
     * @param false $paginate
     * @param string $orderBy
     * @param string $sort
     * @return mixed
     */
    public function findBy(
        $attribute,
        $value,
        $columns = ['*'],
        $paginate = false,
        $orderBy = 'id',
        $sort = 'desc'
    ) {
        $q = $this->modelQuery->where($attribute, '=', $value)
            ->orderBy($orderBy, $sort);
        if ($paginate) {
            return $q->paginate()
                ->appends('order_by', $orderBy)->appends('order_type', $sort);
        }
        return $q->get($columns);
    }

    /**
     * @param $attribute
     * @param $operand
     * @param $value
     * @param string[] $columns
     * @return mixed
     */
    public function findByOperator($attribute, $operand, $value, $columns = ['*'])
    {
        return $this->modelQuery->where($attribute, $operand, $value)
            ->get($columns);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->modelQuery->first();
    }

    /**
     * @param $attribute
     * @param null $value
     * @return mixed
     * @throws Exception
     */
    public function firstBy($attribute, $value = null)
    {
        if (is_array($attribute)) {
            return $this->modelQuery->where($attribute)->first();
        } elseif ($value == null) {
            throw new Exception(
                'Value argument should not be null',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->modelQuery->where($attribute, '=', $value)
            ->first();
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function firstOrCreate(array $attributes, array $values)
    {
        return $this->modelQuery->firstOrCreate($attributes, $values);
    }

    /**
     * @param $attribute
     * @param array $values
     * @return mixed
     */
    public function whereIn($attribute, array $values)
    {
        return $this->modelQuery->whereIn($attribute, $values)->get();
    }

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->modelQuery->count();
    }

    /**
     * @param $attribute
     * @param $value
     * @param string $operator
     * @return mixed
     */
    public function countBy($attribute, $value, $operator = '=')
    {
        return $this->modelQuery->where($attribute, $operator, $value)->count();
    }

    /**
     * @param $column
     * @return mixed
     */
    public function max($column)
    {
        return $this->modelQuery->max($column);
    }

    /**
     * @param $column
     * @return mixed
     */
    public function min($column)
    {
        return $this->modelQuery->min($column);
    }

    /**
     * @param $column
     * @param string $order
     * @param false $paginate
     * @param int $numOfItemEachPage
     * @return mixed
     */
    public function allOrderBy($column, $order = 'asc', $paginate = false, $numOfItemEachPage = 15)
    {
        $query = $this->modelQuery->orderBy($column, $order);
        if ($paginate) {
            return $query->paginate($numOfItemEachPage);
        } else {
            return $query->get();
        }
    }

    /**
     * @param $id
     * @param string[] $columns
     * @return mixed
     */
    public function findOrFail($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * @param $attribute
     * @param null $value
     * @return mixed
     * @throws Exception
     */
    public function firstByOrFail($attribute, $value = null)
    {
        if (is_array($attribute)) {
            return $this->modelQuery->where($attribute)->first();
        } elseif ($value == null) {
            throw new Exception(
                'Value argument should not be null',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->modelQuery->where($attribute, '=', $value)
            ->firstOrFail();
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param string[] $columns
     * @return mixed
     */
    public function getByLimitOffset($offset = 0, $limit = 15, $columns = ['*'])
    {
        return $this->modelQuery->skip($offset)->take($limit)->get($columns);
    }
}
