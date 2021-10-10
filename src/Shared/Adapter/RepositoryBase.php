<?php

declare(strict_types=1);

namespace App\Shared\Adapter;

use App\Shared\Adapter\Contracts\DatabaseOrm;
use App\Shared\Domain\Contracts\Repository;

abstract class RepositoryBase implements Repository
{
    protected string $tableName;
    protected string $pkColumn = 'uuid';

    public function __construct(
        protected DatabaseOrm $orm
    ) {
    }

    public function fetchByPk(string | int $id): array|null
    {
        return $this->orm->read($this->tableName, [$this->pkColumn => $id]);
    }

    public function fetchOne(array $conditions, array $options = []): array|null
    {
        return $this->orm->read($this->tableName, $conditions, $options);
    }

    public function fetchAll(array $conditions, array $options = []): array
    {
        return $this->orm->search($this->tableName, $conditions, $options);
    }

    public function create(array $values): int|string
    {
        return $this->orm->create($this->tableName, $values);
    }

    public function update(array $values, array $conditions): bool
    {
        return $this->orm->update($this->tableName, $values, $conditions);
    }

    public function delete(array $conditions): bool
    {
        return $this->orm->delete($this->tableName, $conditions);
    }
}
