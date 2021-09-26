<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\LongUrl;

interface LongUrlRepository
{
    public function shortLongUrl(LongUrl $url): LongUrl;
}