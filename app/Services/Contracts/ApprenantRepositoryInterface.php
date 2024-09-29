<?php
namespace App\Repositories\Contracts;

interface ApprenantRepositoryInterface
{
    public function create(array $data);
    public function import($file);
    public function all(array $filters);
    public function find($id);
    public function getInactive();
}
