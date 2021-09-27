<?php

declare(strict_types=1);

namespace App\Adapter\Repository\Database;

use App\Domain\Entity\LongUrl;
use App\Domain\Repository\LongUrlRepository;
use App\Shared\Adapter\Contracts\DatabaseOrm;
use App\Shared\Adapter\Contracts\UuidGenerator;
use DateTimeImmutable;
use PDO;

final class DbLongUrlRepository implements LongUrlRepository
{
    public function __construct(
        private DatabaseOrm $orm,
        private UuidGenerator $uuidGenerator,
        private array $config
    ) {
    }

    public function shortLongUrl(LongUrl $url): LongUrl
    {
        while (true) {
            $urlRecord = $this->orm->read('urls', ['short_url_path' => $url->getShortUrlPath()], [
                'columns' => ['id']
            ]);

            if (!is_null($urlRecord)) {
                $url->renewShortUrlPath();
                continue;
            }

            break;
        }

        $url->set('id', $this->uuidGenerator->create());

        $this->orm->create('urls', [
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
        $urlRecord = $this->orm->read('urls', ['short_url_path' => $path]);

        if (is_null($urlRecord)) {
            return null;
        }

        return LongUrl::create([
            'id' => $urlRecord['uuid'],
            'longUrl' => $urlRecord['long_url'],
            'shortUrl' => $this->config['baseUrl'] . '/' . $urlRecord['short_url_path'],
            'baseUrlToShortUrl' => $this->config['baseUrl'],
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
            select count(*) as `total_urls` from `urls` union all select count(*) as `total_clicks` from `urls_logs`
        ";

        $rows = $this->orm->querySql($sql, [], ['fetchMode' => PDO::FETCH_COLUMN]);

        return [
            'totalUrls' => ceil($rows[0]),
            'totalClicks' => ceil($rows[1])
        ];
    }
}
