<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\LongUrlType;
use App\Shared\Domain\EntityBase;
use App\Shared\Domain\ValueObjects\Url;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * @property-read Url $baseUrlToShortUrl
 * @property-read Url $longUrl
 * @property-read Url $shortUrl
 * @property-read LongUrlType $type
 * @property-read DateTimeInterface $createdAt
 */
final class LongUrl extends EntityBase
{
    public const SHORT_URL_PATH_LENGTH = 5;

    protected Url $baseUrlToShortUrl;
    protected Url $longUrl;
    protected LongUrlType $type;
    protected ?Url $shortUrl;
    protected ?DateTimeInterface $createdAt;

    public static function create(array $values): self
    {
        if (!isset($values['createdAt'])) {
            $values['createdAt'] = (new DateTimeImmutable())->format(DateTimeInterface::ATOM);
        }

        if (!isset($values['shortUrl'])) {
            $values['shortUrl'] = $values['baseUrlToShortUrl'] . '/' . self::generatePathToShortUrl();
        }

        return parent::create($values);
    }

    protected function setBaseUrlToShortUrl(string $baseUrl)
    {
        $this->baseUrlToShortUrl = new Url($baseUrl);
    }

    protected function setLongUrl(string $longUrl)
    {
        $this->longUrl = new Url($longUrl);
    }

    protected function setShortUrl(string $shortUrl)
    {
        $this->shortUrl = new Url($shortUrl);
    }

    protected function setType(string $type)
    {
        $this->type = new LongUrlType($type);
    }

    protected function setCreatedAt(string $createdAt)
    {
        $this->createdAt = new DateTimeImmutable($createdAt);
    }

    public function calculateEconomyRate(): float
    {
        return ceil(100 - ((strlen($this->shortUrl->value()) * 100) / strlen($this->longUrl->value())));
    }

    public function getShortUrlPath(): string
    {
        $urlParts = explode('/', $this->shortUrl->value());
        return end($urlParts);
    }

    public function renewShortUrlPath(): void
    {
        $this->shortUrl = new Url($this->baseUrlToShortUrl . '/' . self::generatePathToShortUrl());
    }

    public static function generatePathToShortUrl(): string
    {
        return substr(sha1(uniqid((string)rand(), true)), 0, self::SHORT_URL_PATH_LENGTH);
    }
}
