# sampler

run `composer install`

check out `urandom.sh`

tests: `./vendor/phpunit/phpunit/phpunit --configuration phpunit.xml.dist`

### could do:

* memory jumped up 100k after last refactoring, I could easily (probably) find the issue
* strategy for input adapters
* http input (random.org)
* better algorithm for more meaningful sampling (though not required)
