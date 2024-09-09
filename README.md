Setup Factory app
================
Download and install docker on your machine

### Initial setup:
    docker-compose up -d --build
    docker-compose exec php composer install


### Database setup:
    docker-compose exec php bin/console doctrine:database:create
    docker-compose exec php bin/console doctrine:migrations:migrate
    docker-compose exec php bin/console doctrine:fixtures:load

### Running Application docs
- open localhost/api/docs in browser