# CHANGELOG

All notable changes will be documented in this file.

## 0.8.2 - 2022-11-24

- Fixed type hints for the `success` and `fail` methods
           
## 0.8.1 - 2022-07-06

- `success` and `fail` static functions now have a default value
- Refactored the IDE type hint checker file

## 0.8.0 - 2022-05-16

- Reworked panicking mechanism:
  - Removed the $panicCallback static property
  - Added the $panicExceptionClass static property
             
## 0.7.0 - 2022-02-20
                                     
- Improved type hinting
- Added `getOk` and `getError` methods (work the same as `ok` and `error` properties, but have better type hinting)
- Added TypeHintingTest to test IDE's type hinting capabilities
- Reworked object constructing (internal) 

## 0.6.0 - 2021-09-22

- Internal reworking
- 'ok' and 'error' properties now return null if the requested property does not match the actual result kind

## 0.5.0 - 2021-08-01

- Initial release
