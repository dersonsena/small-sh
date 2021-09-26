<?php

declare(strict_types=1);

namespace App\Shared\Adapter\Contracts;

interface DatabaseOrm
{
    public function create(string $tableName, array $values): int | string;
    public function read(string $tableName, array $filters, array $options = []):? array;
    public function update(string $tableName, array $values, array $conditions): bool;
    public function delete(string $tableName, array $conditions): bool;
    public function search(string $tableName, array $filters, array $options = []): array;
    public function persist(string $tableName, array $values): int | string;
}