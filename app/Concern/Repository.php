<?php

namespace App\Concern;

abstract class Repository
{
    /**
     * The model associated with the repository.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Builder
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * Store new record
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $this->model->fill($data)->save();

        return $this->model;
    }

    /**
     * Find specified record by the given id
     *
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
     */
    public function findById($id)
    {
        return $this->getQuery()->findOrFail($id);
    }

    /**
     * Create fresh query
     *
     * @param int $id
     * @return $this
     */
    public function newQuery($id = null)
    {
        if (empty($id)) {
            $this->builder = $this->model->newQuery();
            return $this;
        }
        $this->builder = null;
        $this->model = $this->findById($id);

        return $this;
    }

    /**
     * Get query builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        if (empty($this->builder))
            return $this->model;

        return $this->builder;
    }

    /**
     * Begin querying a model with eager loading.
     *
     * @param  array|string  $relations
     * @return $this
     */
    public function with($relations)
    {
        $this->builder = $this->model->with($relations);

        return $this;
    }

    /**
     * Paginate the given query.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 10, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $perPage = $perPage ?: $this->model->getPerPage();

        return $this->getQuery()->paginate($perPage, $columns, $pageName, $page)->withQueryString();
    }

    /**
     * Update specified record by the given id
     *
     * @param array $data
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateById(array $data, $id)
    {
        $model = $this->findById($id);
        $model->fill($data)->save();

        return $model;
    }

    /**
     * Remove specified record by the given id
     *
     * @param mixed $id
     * @return bool
     */
    public function deleteById($id)
    {
        return $this->findById($id)->delete();
    }

    /**
    * Set the "limit" value of the query.
    *
    * @param  int  $value
    * @return $this
    */
    public function limit(int $value)
    {
        $this->builder = $this->getQuery()->limit($value);

        return $this;
    }

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function get($columns = ['*'])
    {
        return $this->getQuery()->get($columns);
    }

    /**
     * Paginate the given query into a cursor paginator.
     *
     * @param  int|null  $perPage
     * @param  array  $columns
     * @param  string  $cursorName
     * @param  \Illuminate\Pagination\Cursor|string|null  $cursor
     * @return \Illuminate\Contracts\Pagination\CursorPaginator
     */
    public function cursorPaginate($perPage = null, $columns = ['*'], $cursorName = 'cursor', $cursor = null)
    {
        $perPage = $perPage ?: $this->model->getPerPage();

        return $this->getQuery()->cursorPaginate($perPage, $columns, $cursorName, $cursor)->withQueryString();
    }

    /**
     * Add subselect queries to count the relations.
     *
     * @param  mixed  $relations
     * @return $this
     */
    public function withCount($relations)
    {
        $this->builder = $this->getQuery()->withCount($relations);

        return $this;
    }
}
