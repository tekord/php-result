<?php

namespace Tekord\Result\Tests;

use Tekord\Result\Result;
use Tekord\Result\Tests\Classes\BookDto;
use Tekord\Result\Tests\Classes\PersonDto;

/**
 * This is a helper class to check if an IDE infers types correctly. So it does not make sense to run this test.
 *
 * @author Cyrill Tekord
 */
final class TypeHintingTest extends TestCase {
    public function test_ok_type_hint() {
        $resultPerson = Result::success(new PersonDto());

        $ok = $resultPerson->getOk();

        // Uncomment this and check if your IDE shows fields of the SomeDto class:

//        $ok->

        // ---

        $resultBook = Result::success(new BookDto());

        $ok = $resultBook->unwrap();

//        $ok->

        // ---

        $resultAnotherPerson = Result::success(new PersonDto());

        $ok = $resultAnotherPerson->ok;

//        $ok->

        $this->assertTrue(true);
    }
}
