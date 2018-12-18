# netz98-developer-sample-task

## Clone this repo

```bash
git clone https://github.com/Sven-Herzog/netz98-developer-sample-task.git
cd netz98-developer-sample-task
```

## Build & Run Docker

```bash
docker-compose up --build -d
```

## Run Composer Update

```bash
docker-compose exec php composer update
No composer.json in current directory, do you want to use the one at /var/www? [Y,n]? 
```
Y to use the correct composer.json

## Run Migration for Database

```bash
docker-compose exec php php /var/www/artisan migrate:install
docker-compose exec php php /var/www/artisan migrate --force
```

## Run ParseRss Command 

```bash
docker-compose exec php php /var/www/artisan ParseRssCommand
```

## URLs

```bash
Restfull
http://localhost/v1/posts All Posts
http://localhost/v1/posts/3 One Post

Rest
http://localhost/posts All Posts with pagination
http://localhost/creators All Creatos with pagination
http://localhost/comments All Comments with pagination
```

### Stop Everything

```bash
docker-compose down
```
