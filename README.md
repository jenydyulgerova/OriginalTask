# OriginalTask

This app calculates commissions based on the currency exchange rates specified remotely. 
The URL pointing to the list is set in the .env file.

The tests use exchange rates specified in the .env file.

### What does the app need:
PHP >= 8.0.2

### How to install:

``` bash
$ composer install
```

### How to run:

``` bash
$ php bin/console app:calculate-commission-fees [path_to/data_file.csv]
```
Example:

``` bash
$ php bin/console app:calculate-commission-fees data/input.csv
```

### How to test:

```bash
$ php bin/phpunit
```