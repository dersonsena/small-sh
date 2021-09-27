<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exceptions\InvalidLongUrl;
use App\Shared\Domain\ValueObjectBase;

final class LongUrlType extends ValueObjectBase
{
    public const TYPE_RANDOM = 'RANDOM';
    public const TYPE_CUSTOM = 'CUSTOM';

    protected string $type;

    /**
     * @throws InvalidLongUrl
     */
    public function __construct(string $type)
    {
        if (!in_array($type, [self::TYPE_RANDOM, self::TYPE_CUSTOM])) {
            throw InvalidLongUrl::forInvalidType('url-type', $type);
        }

        $this->type = $type;
    }

    public function value(): mixed
    {
        return $this->type;
    }
}
