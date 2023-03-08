#### dockers

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && composer update"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console daemon:rabbit-mq-consumer"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console client:rabbit-mq-publisher"