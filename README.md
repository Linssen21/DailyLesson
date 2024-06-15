# DailyLesson

# Command for starting docker container
 - docker-compose -f docker-compose.local.yml up -d

# Command for building local container
 - docker-compose -f docker-compose.local.yml build --no-cache

# Execute installing dependency with composer inside the docker after build
 - docker exec -it laravel-api bash
 - composer install --no-dev --no-scripts --optimize-autoloader
 - composer dump-autoload --no-dev --optimize

# Generating ide helper's
 - php artisan ide-helper:generate

# Create a controller
 - php artisan make:controller Web/UserController

# Create unit test with artisan
 - php artisan make:test {UnitTestNameTest} --unit
 - i.e php artisan make:test Domains/User/StatusTest --unit

# Create feature test with artisan
 - php artisan make:test Api/UserTest
 - php artisan make:test Command/CreateAdminTest

# Note in Mocking
 - It is not recommended to Mock the Model

# Running migration in testing database
 - php artisan migrate --database=mysql_testing --env=testing

# Creating an event
 - php artisan make:event Domains/User/Events/UserCreated

# Creating a command 
 - php artisan make:command CreateAdmin

# Telescope
 Run the rollback
 - php artisan migrate:rollback --step=1 --path=database/migrations/2024_06_10_103642_create_telescope_entries_table.php
 Re run the migration
 - php artisan migrate --path=database/migrations/2024_06_10_103642_create_telescope_entries_table.php


# Deployment
 - Disable xDebug
 - Set Environment variable APP_DEBUG=false
 - Set health: '/status'
 Commands:
 - php artisan view:cache
 - php artisan config:cache
 - php artisan event:cache
 - php artisan route:cache
 - php artisan optimize