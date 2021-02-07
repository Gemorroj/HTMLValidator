# Port PEAR package [HTMLValidator](http://pear.php.net/package/Services_W3C_HTMLValidator)

[![Continuous Integration](https://github.com/Gemorroj/HTMLValidator/workflows/Continuous%20Integration/badge.svg?branch=master)](https://github.com/Gemorroj/HTMLValidator/actions?query=workflow%3A%22Continuous+Integration%22)


#### Rewritten to use the new API https://validator.w3.org/docs/api.html


### Requirements:

- PHP >= 7.3

### Installation:
```bash
composer require gemorroj/htmlvalidator
```

### Example:

```php
<?php
use HTMLValidator\HTMLValidator;

$validator = new HTMLValidator();
$result = $validator->validateFragment('<html lang="en"><body> </body></html>');
$result = $validator->validateFile('/path/to/file.html');
$result = $validator->validateUri('http://example.com');

var_dump($result->isValid());

print_r($result->getErrors());
print_r($result->getWarnings());
```
