# DailyLesson

# Command for starting docker container
 - docker-compose -f docker-compose.local.yml up -d

# Command for building local container
 - docker-compose -f docker-compose.local.yml build --no-cache


# Execute installing dependency with composer inside the docker after build
 - docker exec -it laravel-api bash
 - composer install --no-dev --no-scripts --optimize-autoloader
 - composer dump-autoload --no-dev --optimize

# Create unit test with artisan
 - php artisan make:test {UnitTestNameTest} --unit