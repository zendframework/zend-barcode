# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.8.0 - 2019-12-26

### Added

- [#48](https://github.com/zendframework/zend-barcode/pull/48) adds the methods `setProvidedChecksum(bool $value)` (and the option `providedChecksum`) and `getProvidedChecksum()`. These allow indicating that the barcode text includes a checksum value for purposes of validation.

### Changed

- Nothing.

### Deprecated

- [#49](https://github.com/zendframework/zend-barcode/pull/49) deprecates `Zend\Barcode\Renderer\Pdf`. The renderer uses the now-abandoned zendframework/zendpdf package, and, as such, is deprecated as well, and scheduled for removal with version 3.0.0. We will release a separate PDF renderer package at a later date that consumes a 3rd party PDF library.

### Removed

- Nothing.

### Fixed

- Nothing.

## 2.7.1 - 2019-09-21

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#43](https://github.com/zendframework/zend-barcode/pull/43) fixes typo in exception message of `Zend\Barcode\Exception\UnexpectedValueException`.

- [#44](https://github.com/zendframework/zend-barcode/pull/44) changes
  curly braces in array and string offset access to square brackets
  in order to prevent issues under the upcoming PHP 7.4 release.

- [#45](https://github.com/zendframework/zend-barcode/pull/45) fixes
  rotation calculations.

- [#46](https://github.com/zendframework/zend-barcode/pull/46) fixes
  generating checksum for EAN5 and Identcode/Leitcode. These barcodes
  have fixed length and checksum generator must use also leading zeros. 

- [#47](https://github.com/zendframework/zend-barcode/pull/47) fixes
  text length for EAN2 and EAN5 by adding leading zeros.

## 2.7.0 - 2017-12-11

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- [#25](https://github.com/zendframework/zend-barcode/pull/25) removes support
  for PHP 5.5.

- [#38](https://github.com/zendframework/zend-barcode/pull/38) removes support
  for HHVM.

### Fixed

- Nothing.

## 2.6.1 - 2017-12-11

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#24](https://github.com/zendframework/zend-barcode/pull/24) updates the SVG
  renderer to remove extraneous whitespace in `rgb()` declarations, as the
  specification dis-allows whitespace, and many PDF readers/manipulators will
  not correctly consume SVG definitions that include them.

- [#36](https://github.com/zendframework/zend-barcode/pull/36) provides several
  minor changes to namespace imports for the `Zend\Barcode\Object` namespace to
  ensure the package works on PHP 7.2.

## 2.6.0 - 2016-02-17

### Added

- [#23](https://github.com/zendframework/zend-barcode/pull/23) prepares and
  publishes the documentation to https://zendframework.github.io/zend-barcode/

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#12](https://github.com/zendframework/zend-barcode/pull/12) and
  [#16](https://github.com/zendframework/zend-barcode/pull/16) update the code
  base to be forwards-compatible with zend-servicemanager and zend-stdlib v3.
