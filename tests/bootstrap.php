<?php

// ensure we get report on all possible php errors
error_reporting(-1);

require_once __DIR__ . '/../vendor/autoload.php';

DG\BypassFinals::enable();

require_once __DIR__ . '/TestCase.php';
