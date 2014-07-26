# Codeception Test Suite Configuration

# suite for acceptance tests.
# perform tests in browser using the WebDriver or PhpBrowser.
# If you need both WebDriver and PHPBrowser tests - create a separate suite.

class_name: AcceptanceTester
modules:
    enabled:
        - PhpBrowser
        - AcceptanceHelper
    config:
        PhpBrowser:
            # your path to varaa app
            url: 'http://localhost/myapp/'
