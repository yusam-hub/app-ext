#### dockers

    docker exec -it yusam-php74 bash
    docker exec -it yusam-php74 sh -c "htop"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && composer update"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console daemon:react-http-server"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console daemon:rabbit-mq-consumer"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console client:rabbit-mq-publisher"

    docker exec -it yusam-php74 sh -c "ps | grep 'daemon:react-http-server'"
    docker exec -it yusam-php74 sh -c "ps | grep 'daemon:rabbit-mq-consumer'"
    docker exec -it yusam-php74 sh -c "pkill 6025"