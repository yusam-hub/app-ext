#### yusam-hub/app-ext

    "php": "^7.4|^8.0|^8.1|^8.2"

#### tests

    sh phpinit

#### setup

    "repositories": {
        ...
        "yusam-hub/debug": {
            "type": "git",
            "url": "https://github.com/yusam-hub/debug.git"
        },
        "yusam-hub/curl-ext": {
            "type": "git",
            "url": "https://github.com/yusam-hub/curl-ext"
        },
        "yusam-hub/db-ext": {
            "type": "git",
            "url": "https://github.com/yusam-hub/db-ext.git"
        },
        "yusam-hub/json-ext": {
            "type": "git",
            "url": "https://github.com/yusam-hub/json-ext.git"
        },
        "yusam-hub/captcha": {
            "type": "git",
            "url": "https://github.com/yusam-hub/captcha.git"
        },
        "yusam-hub/daemon": {
            "type": "git",
            "url": "https://github.com/yusam-hub/daemon.git"
        },
        "yusam-hub/redis-ext": {
            "type": "git",
            "url": "https://github.com/yusam-hub/redis-ext.git"
        },
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