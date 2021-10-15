<?php

declare(strict_types=1);

namespace App\Adapter\Repository\Database;

use App\Domain\Entity\LongUrl;
use App\Domain\Repository\LongUrlRepository;
use App\Shared\Adapter\Contracts\DatabaseOrm;
use App\Shared\Adapter\Contracts\UuidGenerator;
use App\Shared\Adapter\RepositoryBase;
use App\Shared\Exception\ValidationException;
use DateTimeImmutable;
use PDO;

final class DbLongUrlRepository extends RepositoryBase implements LongUrlRepository
{
    protected string $tableName = 'urls';

    public function __construct(
        protected DatabaseOrm $orm,
        private UuidGenerator $uuidGenerator,
        private string $baseUrl
    ) {
        parent::__construct($this->orm);
    }

    public function shortLongUrl(LongUrl $url): LongUrl
    {
        $url->set('id', $this->uuidGenerator->create());

        $this->create([
            'id' => $this->uuidGenerator->toBytes($url->id),
            'uuid' => $url->id,
            'long_url' => $url->longUrl->value(),
            'short_url_path' => $url->getShortUrlPath(),
            'type' => $url->type->value(),
            'economy_rate' => $url->calculateEconomyRate(),
            'created_at' => $url->createdAt->format('Y-m-d H:i:s')
        ]);

        return $url;
    }

    public function getUrlByPath(string $path): ?LongUrl
    {
        $errors = [];

        if (empty($path)) {
            $errors['path'][] = 'empty-path';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        $urlRecord = $this->fetchOne(['short_url_path' => $path]);

        if (is_null($urlRecord)) {
            return null;
        }

        return LongUrl::create([
            'id' => $urlRecord['uuid'],
            'longUrl' => $urlRecord['long_url'],
            'shortUrl' => $this->baseUrl . '/' . $urlRecord['short_url_path'],
            'baseUrlToShortUrl' => $this->baseUrl,
            'type' => $urlRecord['type'],
            'createdAt' => $urlRecord['created_at']
        ]);
    }

    public function registerAccess(LongUrl $url, array $metaInfo = [])
    {
        $uuid = $this->uuidGenerator->create();

        $this->orm->create('urls_logs', [
            'id' => $this->uuidGenerator->toBytes($uuid),
            'uuid' => $uuid,
            'url_id' => $this->uuidGenerator->toBytes($url->id),
            'created_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            'meta' => json_encode($metaInfo)
        ]);
    }

    public function countUrlsAndClicks(): array
    {
        $sql = "
            select count(*) as `total_urls` from `{$this->tableName}`
            union all
            select count(*) as `total_clicks` from `urls_logs`
        ";

        $rows = $this->orm->querySql($sql, [], ['fetchMode' => PDO::FETCH_COLUMN]);

        return [
            'totalUrls' => ceil($rows[0]),
            'totalClicks' => ceil($rows[1])
        ];
    }
}
