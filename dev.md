#### dockers

    docker exec -it yusam-php74 bash
    docker exec -it yusam-php74 sh -c "htop"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && sh phpunit"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && composer update"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console db:check"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console db:migrate"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console redis:check"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console daemon:web-socket-server"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console client:web-socket-internal"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console client:web-socket-external my-test-message"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console daemon:react-http-server"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console daemon:rabbit-mq-consumer"
    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console client:rabbit-mq-publisher hello-message"

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && php console smarty:check"

    docker exec -it yusam-redis sh -c "redis-cli"

    docker exec -it yusam-php74 sh -c "ps | grep 'daemon:react-http-server'"
    docker exec -it yusam-php74 sh -c "ps | grep 'daemon:rabbit-mq-consumer'"
    docker exec -it yusam-php74 sh -c "pkill 6025"

#### curl

    docker exec -it yusam-php74 sh -c "curl --help"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X GET http://localhost/api/debug/test/params"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X POST http://localhost/api/debug/test/params -F foo=test"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X POST http://localhost/api/debug/test/params --data foo=test"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X POST http://localhost/api/debug/test/params --data 'foo=test' -H 'X-Token: testing' -H 'X-Sign: testing'"
    docker exec -it yusam-php74 sh -c "curl --unix-socket /tmp/react-http-server-socks/server.worker0.sock -vvv -X GET http://localhost/api/debug/test/db -H 'X-Token: testing' -H 'X-Sign: testing'"