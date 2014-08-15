# VARAA

Currently this is a separate branch that incorporates Laravel 4 as the core
framework. Bug fixes and changes of old source code are still continued
developing in branch `master`. Developers are advised (or forced) to create a
new VHost pointing to `laravel/public` to facilitate development process.

### Prerequisites
- Apache
- PHP >= 5.4.0
- MySQL >= 5.6.0
- Composer
- Note: You can use any existing LAMP stack like
 `https://www.apachefriends.org/index.html`
  or `http://www.easyphp.org/` or vagrant if you are advanced user.

### Configuration
The site is using hostname-aware to detect environment. Please set your hostname
to `dev` or add a new hostname to line 29 of `bootstrap/start.php`.

After that, config values for local development are set in `app/config/local`.
This folder is ignored by default, so developers can safely set anything they
want without polluting others' environment. Please copy `config/database.php`
into the folder and change its settings. Some other useful settings:

###### `app/config/local/app.php`
```
return [
    'debug' => true,
    'locale' => 'fi',
];
```

###### `app/config/local/mail.php`
```
return [
    'pretend' => true
];
```

### PHP coding convention
- PSR-2 `http://www.php-fig.org/psr/psr-2/` for L4 or framework specific
convention.
- For Sublime Text 3 user, try [Editor Config](http://editorconfig.org/) 
(a `.editorconfig` is already included in the repo).

### Tests
- Read http://codeception.com/docs/01-Introduction and http://codeception.com/docs/04-AcceptanceTests
- Generate your acceptance tests in tests/acceptance/ folder: 
`php codecept.phar generate:cest acceptance YourTestName`
- Run the test: `php codecept.phar run`
- A best practice guide http://www.sitepoint.com/ruling-the-swarm-of-tests-with-codeception/
