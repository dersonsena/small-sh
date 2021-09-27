<?php

declare(strict_types=1);

namespace App\Shared\Infra;

use App\Shared\Adapter\Contracts\DatabaseOrm;
use PDO;

final class PdoOrm implements DatabaseOrm
{
    public function __construct(
        private PDO $pdo
    ) {
    }

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
        $columns = '*';
        $clauses = '';

        if (isset($options['columns'])) {
            $columns = array_map(fn ($columnName) => '`' . $columnName . '`', $options['columns']);
            $columns = implode(',', $columns);
        }

        $filterColumns = array_keys($filters);
        $i = 0;

        foreach ($filterColumns as $columnName) {
            if ($i === 0) {
                $clauses .= "WHERE `{$columnName}` = :{$columnName}";
            }

            if ($i > 0) {
                $clauses .= "AND `{$columnName}` = :{$columnName}";
            }

            $i++;
        }

        $sql = sprintf("SELECT %s FROM `%s` %s", $columns, $tableName, $clauses);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($filters);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $row;
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

    public function querySql(string $sql, array $values = [], array $options = []): array
    {
        $stmt = $this->pdo->prepare(trim($sql));
        $stmt->execute($values);
        $fetchMode = $options['fetchMode'] ?? PDO::FETCH_ASSOC;
        return $stmt->fetchAll($fetchMode);
    }
}
