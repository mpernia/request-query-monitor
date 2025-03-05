# Changelog

## [1.0.1] - 2025-03-04

### Added
- Initial release of Request Query Monitor for Laravel
- Database query logging functionality
  - Configurable slow query threshold
  - Logging of all executed database queries
- API request logging middleware
  - Configurable slow request threshold
  - Detailed request information logging
- Configuration options via `.env` file
  - `QUERY_LOGGER_ENABLED`
  - `QUERY_LOGGER_SLOW_THRESHOLD`
  - `REQUEST_LOGGER_SLOW_THRESHOLD`
- Vendor publish command for configuration files
  - `query_logger.php`
  - `request_logger.php`

### Features
- Monitor and log database queries
- Track API request performance
- Easy configuration and setup
- Minimal performance overhead

### Installation
- Composer package installation support
- Laravel middleware integration

### Documentation
- README.md with installation and usage instructions
- Initial configuration guidelines

### Known Limitations
- Initial release, may have some edge cases
- Requires further testing in various Laravel environments
