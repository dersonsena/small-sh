<?php

use Faker\Generator;
use Faker\Factory as Faker;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;

abstract class TestCase extends PhpUnitTestCase
{
    static protected Generator $faker;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        self::$faker = Faker::create('pt_BR');
    }
}
