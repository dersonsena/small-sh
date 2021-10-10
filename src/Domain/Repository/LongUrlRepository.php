<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\LongUrl;
use App\Shared\Domain\Contracts\Repository;

interface LongUrlRepository extends Repository
{
    public function shortLongUrl(LongUrl $url): LongUrl;
    public function getUrlByPath(string $path): ?LongUrl;
    public function registerAccess(LongUrl $url, array $metaInfo = []);
    public function countUrlsAndClicks(): array;
}
