<?php

namespace Tekord\Result\Tests;

use Exception;
use Tekord\Result\Result;

/**
 * @author Cyrill Tekord
 */
final class ResultTest extends TestCase {
    public function testOk() {
        $o = Result::success("Everything is OK");

        $this->assertTrue($o->isOk());
        $this->assertFalse($o->isFailed());

        $this->assertEquals("Everything is OK", $o->ok);
        $this->assertNull($o->error);
    }

    public function testFail() {
        $o = Result::fail("Something went wrong");

        $this->assertFalse($o->isOk());
        $this->assertTrue($o->isFailed());

        $this->assertEquals("Something went wrong", $o->error);
        $this->assertNull($o->ok);
    }

    public function testUnwrap() {
        $o = Result::success("OK");

        $result = $o->unwrap();

        $this->assertEquals("OK", $result);

        // -

        $o = Result::fail("Failed");

        $result = $o->unwrapOrDefault(149);

        $this->assertEquals(149, $result);

        // -

        $o = Result::fail("Failed");

        $result = $o->unwrapOrElse(function () {
            return 2000;
        });

        $this->assertEquals(2000, $result);
    }

    public function testUnwrapFail() {
        $o = Result::fail("Failed");

        $this->expectException(Exception::class);

        $o->unwrap();
    }

    public function testMap() {
        $mapper = function ($value) {
            return "($value)";
        };

        // -

        $o = Result::success("OK");

        $result = $o->mapOrDefault($mapper, "NOT SET");

        $this->assertEquals("(OK)", $result);

        // -

        $o = Result::fail("Failed");

        $result = $o->mapOrDefault($mapper, "NOT SET");

        $this->assertEquals("NOT SET", $result);

        // -

        $o = Result::success("OK");

        $result = $o->mapOrElse($mapper, function () {
            return '-';
        });

        $this->assertEquals('(OK)', $result);

        // -

        $o = Result::fail("Failed");

        $result = $o->mapOrElse($mapper, function () {
            return '-';
        });

        $this->assertEquals('-', $result);
    }

    public function testPanic() {
        Result::$panicCallback = function () {
            return throw new Exception("Overridden Panic Exception");
        };

        $o = Result::fail("Failed");

        $this->expectExceptionMessage("Overridden Panic Exception");

        $o->unwrap();
    }
}
