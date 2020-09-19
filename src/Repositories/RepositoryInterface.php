<?php

namespace Derakht\RepositoryPattern\Repositories;

interface RepositoryInterface
{
    public function all($columns = ['*']);

    public function allOrderBy($column, $order = 'asc', $paginate = false, $perPage = 15);

    public function getByLimitOffset($offset = 0, $limit = 15, $columns = ['*']);

    public function create(array $data);

    public function update(array $data, $id, $attribute = "id");

    public function updateOrCreate(array $attributes, array $values);

    public function paginate($perPage = 15, $columns = ['*']);

    public function delete($id);

    public function find($id, $columns = ['*']);

    public function findOrFail($id, $columns = ['*']);

    public function findBy($attribute, $value, $columns = ['*'], $paginate = false, $orderBy = 'id', $sort = 'desc');

    public function findByOperator($attribute, $operand, $value, $columns = ['*']);

    public function first();

    public function firstBy($attribute, $value = null);

    public function firstByOrFail($attribute, $value = null);

    public function firstOrCreate(array $attributes, array $values);

    public function whereIn($attribute, array $values);

    public function count();

    public function countBy($attribute, $value, $operator = '=');

    public function max($column);

    public function min($column);

    public function getModel();
}
