**This code is part of the [SymfonyContrib](http://symfonycontrib.com/) community.**

SymfonyContrib Dynamic Menu Bundle
==================================

Symfony2 menu content management. Allows content admin to manage menus.

Extends the KnpMenuBundle to allow storage of menus in Doctrine ORM.

## Features
* Easy to use administrative interface.
* Create menus and menu items/links which are stored in the database.
* Order items in menus in a tree format.
* Options to display children. @incomplete
* Enable/disable menus and links.
* Use Symfony routes as URI of menu item. @todo
* Provide default managed menus in bundles. @todo

Installation
============

### 1. Install & configure dependencies

** Note: KnpMenuBundle version 2 is used which is currently in dev, which means
you will need to place it and its dependencies in your root composer file with
@dev flags. **

```json
"require": {
    ...
    "knplabs/knp-menu": "2.0.*@dev",
    "knplabs/knp-menu-bundle": "2.0.*@dev",
}
```

* [Doctrine](http://symfony.com/doc/current/book/doctrine.html)
* [KnpMenuBundle](https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md)
* [ConfirmBundle](https://github.com/SymfonyContrib/ConfirmBundle)
* [LinkButtonBundle](https://github.com/SymfonyContrib/LinkButtonBundle)

### 2. Add the bundle to your composer.json

```json
"require": {
    ...
    "symfonycontrib/dynamic-menu-bundle": "@stable"
}
```

### 3. Install the bundle using composer

```bash
$ composer update symfonycontrib/dynamic-menu-bundle
```

### 4. Add this bundle to your application's kernel:

```php
    new SymfonyContrib\Bundle\DynamicMenuBundle\DynamicMenuBundle(),
```

### 5. Add routing to your application's routing.yml:

```yml
dynamic_menu_admin:
    resource: "@DynamicMenuBundle/Resources/config/routing.admin.yml"
    prefix:   /admin/structure/
```

### 6. Update your database schema

```bash
$ app/console doctrine:schema:update --force
```

Configuration
=============

See [KnpMenuBundle](https://github.com/KnpLabs/KnpMenuBundle/blob/master/Resources/doc/index.md)

Usage
=====

** Note: As an extension of KnpMenuBundle, most of its usage and documentation
still apply. **

### Print menu in Twig

```
{{ knp_menu_render('acme_menu') }}
```
