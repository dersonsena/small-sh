<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ShortenUrl;

use App\Domain\Entity\LongUrl;
use App\Domain\Repository\LongUrlRepository;
use DateTimeInterface;

final class ShortenUrl
{
    public function __construct(
        private LongUrlRepository $longUrlRepo
    ) {
    }

    public function execute(InputData $input): OutputData
    {
        while (true) {
            $longUrl = LongUrl::create([
                'longUrl' => $input->longUrl,
                'baseUrlToShortUrl' => $input->baseUrl,
                'type' => $input->type
            ]);

            if ($this->longUrlRepo->getUrlByPath($longUrl->getShortUrlPath())) {
                $longUrl->renewShortUrlPath();
                continue;
            }

            break;
        }

        $longUrl = $this->longUrlRepo->shortLongUrl($longUrl);

        return OutputData::create([
           'longUrl' => $longUrl->longUrl->value(),
           'shortenedUrl' => $longUrl->shortUrl->value(),
           'economyRate' => $longUrl->calculateEconomyRate(),
           'createdAt' => $longUrl->createdAt->format(DateTimeInterface::ATOM),
        ]);
    }
}
