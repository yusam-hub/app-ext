#### dockers

    docker exec -it yusam-php74 bash
    docker exec -it yusam-php74 sh -c "htop"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && composer update"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console db:check"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console daemon:react-http-server"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console daemon:rabbit-mq-consumer"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php ./bin/console client:rabbit-mq-publisher"

    docker exec -it yusam-php74 sh -c "ps | grep 'daemon:react-http-server'"
    docker exec -it yusam-php74 sh -c "ps | grep 'daemon:rabbit-mq-consumer'"
    docker exec -it yusam-php74 sh -c "pkill 6025"

#### curl

    docker exec -it yusam-php74 sh -c "curl --help"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X GET http://localhost/debug/test"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X POST http://localhost/debug/test -F foo=test"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X POST http://localhost/debug/test --data foo=test"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X POST http://localhost/debug/test --data 'foo=test' -H 'X-Token: testing' -H 'X-Sign: testing'"