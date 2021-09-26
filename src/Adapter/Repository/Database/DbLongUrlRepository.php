<?php

declare(strict_types=1);

namespace App\Adapter\Repository\Database;

use App\Domain\Entity\LongUrl;
use App\Domain\Repository\LongUrlRepository;
use App\Shared\Adapter\Contracts\DatabaseOrm;
use App\Shared\Adapter\Contracts\UuidGenerator;

final class DbLongUrlRepository implements LongUrlRepository
{
    public function __construct(
        private DatabaseOrm $orm,
        private UuidGenerator $uuidGenerator
    ) {}

    public function shortLongUrl(LongUrl $url): LongUrl
    {
        while (true) {
            $urlRecord = $this->orm->read('urls', ['short_url_path' => $url->getShortUrlPath()]);

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
}