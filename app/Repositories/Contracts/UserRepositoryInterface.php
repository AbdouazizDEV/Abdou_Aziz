<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function create(array $data): array;
    public function all(): array;
    public function find($id);
    public function update($id, array $data);

}
