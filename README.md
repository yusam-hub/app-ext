#### yusam-hub/app-ext

    "php": "^7.4|^8.0|^8.1|^8.2"

#### tests

    sh phpinit

#### setup

    "repositories": {
        ...
        "yusam-hub/app-ext": {
            "type": "git",
            "url": "https://github.com/yusam-hub/app-ext.git"
        }
        ...
    },
    "require": {
        ...
        "yusam-hub/app-ext": "dev-master"
        ...
    }

#### dockers

    docker exec -it yusam-php74 sh -c "cd /var/www/data/yusam/github/yusam-hub/app-ext && composer update"