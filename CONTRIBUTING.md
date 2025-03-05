# Contributing to Request Query Monitor

We welcome and appreciate contributions from the community! This document provides guidelines for contributing to the Request Query Monitor package.

## Code of Conduct

Please be respectful, inclusive, and considerate of others. We aim to maintain a welcoming environment for all contributors.

## How to Contribute

### Reporting Issues

1. Check existing issues to ensure the issue hasn't been reported before.
2. Use the issue templates provided:
   - For bug reports, include:
     - Detailed description
     - Steps to reproduce
     - Expected vs. actual behavior
     - Laravel and package versions
   - For feature requests, describe the proposed feature and its potential benefits

### Pull Request Process

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request with a clear title and description

### Development Setup

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Run tests:
   ```bash
   composer test
   ```

### Coding Standards

- Follow Laravel and PHP-FIG coding standards
- Use PSR-12 code style
- Write clean, readable, and well-documented code
- Add/update tests for new features or bug fixes

### Testing

- Write unit and feature tests for new functionality
- Ensure 100% test coverage for new code
- Run the test suite before submitting a pull request

### Performance Considerations

Since this is a performance monitoring package, pay special attention to:
- Minimal overhead in query and request logging
- Efficient logging mechanisms
- Configurable thresholds

### Documentation

- Update README.md and other documentation for any new features
- Add clear examples of usage
- Update configuration options if needed

## Releasing

- Follow semantic versioning (MAJOR.MINOR.PATCH)
- Update CHANGELOG.md with notable changes
- Tag releases in the repository

## Questions?

If you have any questions about contributing, please open an issue or reach out to the maintainers.

Thank you for helping improve Request Query Monitor! 🚀
