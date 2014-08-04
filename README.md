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

### PHP coding convention
- PSR-2 `http://www.php-fig.org/psr/psr-2/` for L4 or framework specific
convention.
- To be consistent with the rest of the project, let's use 1TBS
(One True Brace Style: http://en.wikipedia.org/wiki/Indent_style#Variant:_1TBS) 
style for braces
- For Sublime Text 3 user, try [Editor Config](http://editorconfig.org/) 
(a `.editorconfig` is already included in the repo).

### Tests
- Read http://codeception.com/docs/01-Introduction and http://codeception.com/docs/04-AcceptanceTests
- Create and change test config: 
`cp tests/acceptance.suite.yml.tpl tests/acceptance.suite.yml`
- Generate your acceptance tests in tests/acceptance/ folder: 
`php codecept.phar generate:cest acceptance YourTestName`
- Run the test: `php codecept.phar run`
