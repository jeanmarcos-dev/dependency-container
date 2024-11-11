# Jeanmarcos Dependency Container

[![Latest Version](https://img.shields.io/badge/version-v1.0.0--alpha-blue.svg?style=flat-square)](https://packagist.org/packages/jeanmarcos/dependency-container)
[![Latest Version](https://img.shields.io/packagist/v/jeanmarcos/dependency-container.svg?style=flat-square)](https://packagist.org/packages/jeanmarcos/dependency-container)
[![Build Status](https://img.shields.io/github/actions/workflow/status/jeanmarcos/dependency-container/test.yml?branch=main&style=flat-square)](https://github.com/jeanmarcos-dev/dependency-container/actions)
[![License](https://img.shields.io/packagist/l/jeanmarcos/dependency-container.svg?style=flat-square)](https://packagist.org/packages/jeanmarcos/dependency-container)

> ⚠️ **Note**: This is an alpha version and is not ready for production use. Upcoming releases will include new features
> and improvements.

A lightweight, PSR-4 compatible Dependency Injection (DI) container for managing dependencies in PHP applications.

## 📜 Overview

`jeanmarcos/dependency-container` is a flexible and simple-to-use dependency injection container designed to help you
manage your services and dependencies in PHP projects. The project is inspired by the DI container of Magento, aiming to
offer similar extensibility and flexibility..

## ✨ Features

- PSR-4 autoloading compatibility.
- Auto-wiring capabilities.
- Support for shared (singleton) and non-shared instances.
- Circular dependency detection.
- Flexible configuration for optional and nullable dependencies.
- Singleton container implementation.
- **Upcoming**: Interceptors to extend and modify class behaviors dynamically.

## 🛠️ Installation

Install the package via [Composer](https://getcomposer.org/):

```bash
composer require jeanmarcos/dependency-container
```

## ⚡ Quick Start

### Instantiating with Services in the Constructor

You can directly instantiate the container with services provided as configurations:

```php
<?php

use Jeanmarcos\DependencyContainer\Container;
use Jeanmarcos\DependencyContainer\Configs\ServiceConfig;

class MyService {}

$container = new Container(null, [
    new ServiceConfig(className: MyService::class)
]);

$myService = $container->get(MyService::class);
```

### Configuring Singleton Services

You can configure a specific service as a singleton (shared instance) when registering it:

```php
$container = new Container(
                servicesConfig: [
                    new ServiceConfig(className: MyService::class, shared: true)
                ]
            );

$firstInstance = $container->get(MyService::class);
$secondInstance = $container->get(MyService::class);

// Both instances are the same
assert($firstInstance === $secondInstance);
```

### Configuring the Entire Container for Singleton Behavior

If you want all services to be shared by default, configure the container itself for singleton behavior:

```php
use Jeanmarcos\DependencyContainer\Configs\ContainerConfig;

$config = new ContainerConfig(allInstanceSharedByDefault: true);
$servicesConfig = [
    new ServiceConfig(className: MyService::class)
];
$container = new Container($config, $servicesConfig);

$firstInstance = $container->get(MyService::class);
$secondInstance = $container->get(MyService::class);

// Both instances are the same due to container configuration
assert($firstInstance === $secondInstance);
```

### Singleton Container Pattern

For applications that require a single shared container instance, you can use `SingletonContainer`:

```php
use Jeanmarcos\DependencyContainer\SingletonContainer;

// Retrieve the singleton instance of the container
$singletonContainer = SingletonContainer::getInstance(null, [
    new ServiceConfig(className: MyService::class)
]);

$myService = $singletonContainer->get(MyService::class);
```

The `SingletonContainer` class ensures only one instance of the container is created, providing a global access point to
your services.

## 🧪 Testing

The package includes unit and integration tests. To run the tests, use the following command:

```bash
composer test
```

### Testing Dependencies

- **PHPUnit**: PHPUnit is used for both unit and integration testing. The configuration for PHPUnit is included in the
  `phpunit.xml` file.
- **PHP CodeSniffer**: Ensure code quality by running PHP CodeSniffer.

Run PHP CodeSniffer with:

```bash
composer phpcs
```

## 📂 Directory Structure

Here's an overview of the directory structure:

```plaintext
src/
├── Configs/            # Configuration classes
├── Exceptions/         # Custom exceptions
├── Container.php       # Main container class
├── SingletonContainer.php # Singleton container class
tests/
├── Unit/               # Unit tests
└── Integration/        # Integration tests
```

## 📜 Documentation

The documentation for `jeanmarcos/dependency-container` is currently in progress and will be expanded in future
releases.

## 📥 Contributing

Contributions are welcome! Follow these steps to contribute:

1. Fork the repository.
2. Create a new branch (`feature/my-feature`).
3. Commit your changes.
4. Push the branch and create a pull request.

Please ensure your code follows the existing style and includes tests where appropriate.

## 📅 Changelog

### v1.0.0-alpha

- Initial alpha release.
- Basic dependency injection container with singleton and non-shared instances.
- Auto-wiring capabilities.
- Support for optional dependencies and nullable parameters.
- Basic circular dependency detection.
- **Upcoming Features**: Interceptors for extending class methods dynamically.

## 📝 License

This package is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

Happy coding! 😄
