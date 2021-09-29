<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use App\Shared\Domain\Exceptions\InvalidUrlException;
use App\Shared\Domain\ValueObjectBase;

final class Url extends ValueObjectBase
{
    private string $url;

    /**
     * @throws InvalidUrlException
     */
    public function __construct(string $url)
    {
        if (empty($url)) {
            throw InvalidUrlException::forEmptyUrl();
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw InvalidUrlException::forInvalidUrl($url);
        }

        $this->url = $url;
    }

    public function value(): mixed
    {
        return $this->url;
    }
}
