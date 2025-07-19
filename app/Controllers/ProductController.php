<?php

namespace App\Controllers;

use App\Services\ProductService;

class ProductController
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        //header('Content-Type: application/json');
       // echo json_encode($this->service->getAll());
        $products = $this->service->getAll();

        require __DIR__ . '/../Views/products/index.php';
    }

    public function show($id)
    {
        header('Content-Type: application/json');
        echo json_encode($this->service->getById($id));
    }

    public function store()
    {
      //  $data = json_decode(file_get_contents('php://input'), true);
      //  $this->service->create($data);
     //   echo json_encode(['message' => 'Produto criado com sucesso']);

        $this->service->create($_POST);
        header("Location: /products");

    }

    public function edit($id)
    {
        $product = $this->service->getById($id);
        // if (!$product) {
        //     http_response_code(404);
        //     echo json_encode(['message' => 'Produto não encontrado']);
        //     return;
        // }       
        require __DIR__ . '/../Views/products/edit.php';
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->service->update($id, $data);
        echo json_encode(['message' => 'Produto atualizado com sucesso']);
    }

    public function delete($id)
    {
        var_dump($id);
        $this->service->delete($id);
        header("Location: /products");
        echo json_encode(['message' => 'Produto removido com sucesso']);
    }
}