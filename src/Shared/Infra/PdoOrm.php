<?php

declare(strict_types=1);

namespace App\Shared\Infra;

use App\Shared\Adapter\Contracts\DatabaseOrm;
use PDO;

final class PdoOrm implements DatabaseOrm
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function create(string $tableName, array $values): int|string
    {
        $columns = array_keys($values);
        $columnsVars = array_map(fn($columnName) => ':' . $columnName, $columns);
        $columns = array_map(fn($columnName) => '`' . $columnName . '`', $columns);
        $sql = sprintf(
            'INSERT INTO `%s` (%s) VALUES (%s)',
            $tableName,
            implode(',', $columns),
            implode(',', $columnsVars)
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return $this->pdo->lastInsertId('uuid');
    }

    public function read(string $tableName, array $filters, array $options = []): ?array
    {
        return null;
    }

    public function update(string $tableName, array $values, array $conditions): bool
    {
        return true;
    }

    public function delete(string $tableName, array $conditions): bool
    {
        return true;
    }

    public function search(string $tableName, array $filters, array $options = []): array
    {
        return [];
    }

    public function persist(string $tableName, array $values): int|string
    {
        return '';
    }
}