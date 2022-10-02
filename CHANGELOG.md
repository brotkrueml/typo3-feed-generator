# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Categories can be added to a feed

### Changed
- debril/feed-io removed in favour of laminas/laminas-feed and jdecool/jsonfeed
- Namespace of multiple classes has changed

### Removed
- XSL stylesheets
- Media in items

## [0.3.0] - 2022-06-20

### Added
- Overview of feeds in Configurations module
- Last-Modified header in middleware
- Cache attribute for Feed class to control cache HTTP headers

### Changed
- Position of middleware in frontend stack

### Updated
- debril/feed-io to version 6

## [0.2.0] - 2022-06-10

### Added
- Media (like images, videos, audios) can be attached to a feed item
- Author can be attached to a feed or to a feed item

## [0.1.0] - 2022-04-01

First preview release

[Unreleased]: https://github.com/brotkrueml/typo3-feed-generator/compare/v0.3.0...HEAD
[0.3.0]: https://github.com/brotkrueml/typo3-feed-generator/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/brotkrueml/typo3-feed-generator/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/brotkrueml/typo3-feed-generator/releases/tag/v0.1.0
