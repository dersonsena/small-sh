<?php

declare(strict_types=1);

namespace App\Domain\UseCase\ShortenUrl;

use App\Domain\Entity\LongUrl;
use App\Domain\Repository\LongUrlRepository;
use App\Shared\Exception\ValidationException;
use DateTimeInterface;

final class ShortenUrl
{
    public function __construct(
        private LongUrlRepository $longUrlRepo
    ) {
    }

    public function execute(InputData $input): OutputData
    {
        $errors = [];

        if (empty($input->longUrl)) {
            $errors['longUrl'][] = 'empty-value';
        }

        if (!filter_var($input->longUrl, FILTER_VALIDATE_URL)) {
            $errors['longUrl'][] = 'invalid-url';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

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
