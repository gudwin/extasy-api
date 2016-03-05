<?php


namespace Extasy\API\tests\Domain\Core;

use Extasy\API\tests\BaseTest;
use Extasy\API\Domain\Core\OutputError;

class OutputErrorTest extends BaseTest
{
    public function testToString() {
        $error = new OutputError('hello','world');
        $this->assertEquals( '[hello] world', (string)$error);

    }
}