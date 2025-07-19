<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        $product = new Product($data);
        return $this->repository->create($product);
    }

    public function update($id, array $data)
    {
        $product = new Product(array_merge(['id' => $id], $data));
        return $this->repository->update($product);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}