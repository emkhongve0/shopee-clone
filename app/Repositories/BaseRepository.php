<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    abstract public function getModel();

    public function setModel()
    {
        $this->model = app()->make($this->getModel());
    }

    public function all() { return $this->model->all(); }
    public function find($id) { return $this->model->find($id); }
    public function create(array $attributes) { return $this->model->create($attributes); }
}
