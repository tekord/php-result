<?php

// This is a helper file to check if an IDE infers types correctly.
// Put the cursor at the end of a value getting call and see if your IDE shows a hint

use Tekord\Result\Result;
use Tekord\Result\Tests\Classes\BookDto;
use Tekord\Result\Tests\Classes\PersonDto;

$resultPerson = Result::success(new PersonDto());

//                          |
//                          V - put your cursor here
$ok = $resultPerson->getOk();

$resultBook = Result::success(new BookDto());

//                         |
//                         V - put your cursor here
$ok = $resultBook->unwrap();

// ---

$resultAnotherPerson = Result::success(new PersonDto());

//                            |
//                            V - put your cursor here
$ok = $resultAnotherPerson->ok;
