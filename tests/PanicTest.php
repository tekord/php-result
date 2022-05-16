<?php

namespace Tekord\Result\Tests;

use DomainException;
use Exception;
use Tekord\Result\PanicException;
use Tekord\Result\Result;
use Tekord\Result\Tests\Classes\CustomResult;

/**
 * @runTestsInSeparateProcesses
 *
 * @author Cyrill Tekord
 */
final class PanicTest extends TestCase {
    public function testPanicking() {
        $o = Result::fail("Some Error");

        try {
            $o->unwrap();
        }
        catch (Exception $e) {
            $this->assertInstanceOf(PanicException::class, $e);
            $this->assertEquals($e->getMessage(), 'Some Error');
        }

        // -

        Result::$panicExceptionClass = DomainException::class;

        try {
            $o->unwrap();
        }
        catch (Exception $e) {
            $this->assertInstanceOf(DomainException::class, $e);
            $this->assertEquals($e->getMessage(), 'Some Error');
        }
    }

    public function testCustomResultPanicking() {
        // Results use different exception classes. We need to make sure they don't interfere each other

        $r1 = CustomResult::fail("Custom Error");

        try {
            $r1->unwrap();
        }
        catch (Exception $e) {
            $this->assertInstanceOf(DomainException::class, $e);
            $this->assertEquals($e->getMessage(), 'Custom Error');
        }

        $r2 = Result::fail("Default Error");

        try {
            $r2->unwrap();
        }
        catch (Exception $e) {
            $this->assertInstanceOf(PanicException::class, $e);
            $this->assertEquals($e->getMessage(), 'Default Error');
        }
    }
}
