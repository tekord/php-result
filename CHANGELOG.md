# CHANGELOG

All notable changes will be documented in this file.
                        
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
