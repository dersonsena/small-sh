<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contracts;

interface Repository
{
    public function fetchByPk(string | int $id): array|null;
    public function fetchOne(array $conditions, array $options = []): array|null;
    public function fetchAll(array $conditions, array $options = []): array;
    public function create(array $values): int|string;
    public function update(array $values, array $conditions): bool;
    public function delete(array $conditions): bool;
}
