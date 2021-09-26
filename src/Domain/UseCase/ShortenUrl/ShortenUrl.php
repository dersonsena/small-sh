<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ShortenUrl;

use App\Domain\Entity\LongUrl;
use App\Domain\Repository\LongUrlRepository;
use DateTimeInterface;

final class ShortenUrl
{
    public function __construct(
        private LongUrlRepository $urlRepo
    ) {}

    public function execute(InputData $input): OutputData
    {
        $longUrl = LongUrl::create([
            'longUrl' => $input->longUrl,
            'baseUrlToShortUrl' => $input->baseUrl,
            'type' => $input->type
        ]);

        $longUrl = $this->urlRepo->shortLongUrl($longUrl);

        return OutputData::create([
           'longUrl' => $longUrl->longUrl,
           'shortenedUrl' => $longUrl->shortUrl,
           'economyRate' => $longUrl->calculateEconomyRate(),
           'createdAt' => $longUrl->createdAt->format(DateTimeInterface::ATOM),
        ]);
    }
}