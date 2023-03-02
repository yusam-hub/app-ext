<?php

namespace YusamHub\AppExt\Tests;

use YusamHub\AppExt\Config;
use YusamHub\AppExt\DotArray;
use YusamHub\AppExt\Env;

class ExampleTest extends \PHPUnit\Framework\TestCase
{
    public function testExample()
    {
        Config::$CONFIG_DIR = __DIR__ . '/../config';

        $dotArray = new DotArray(app_ext_config('test'));

        $this->assertFalse($dotArray->has(''));

        $this->assertTrue($dotArray->has('key1'));

        $this->assertFalse($dotArray->has('fail'));

        $this->assertFalse($dotArray->has('key1.fail'));

        $this->assertTrue($dotArray->has('key2.key22'));

        $this->assertFalse($dotArray->has('key2.key22.fail'));

        $this->assertTrue($dotArray->get('key2.key22.keyNull') === null);

        $this->assertTrue($dotArray->get('key2.key22.keyIntZero') === 0);

        $this->assertTrue($dotArray->get('key2.key22.keyIntOne') === 1);

        $this->assertTrue($dotArray->get('key2.key22.keyFloat') === 1.2);

        $this->assertTrue($dotArray->get('key2.key22.keyString') === "string");

        $dotArray->set("key2.key1.test3.test4",'newValue');

        $this->assertTrue($dotArray->get('key2.key1.test3.test4') === "newValue");
    }

    public function testExample2()
    {
        Env::$ENV_DIR = __DIR__ . '/../env';

        $this->assertTrue(app_ext_env("ENV_KEY_NULL") === null);
        $this->assertTrue(app_ext_env("ENV_KEY_STRING") === "test string long");
        $this->assertTrue(app_ext_env("ENV_KEY_BOOL_FALSE") === false);
        $this->assertTrue(app_ext_env("ENV_KEY_BOOL_TRUE") === true);
        $this->assertTrue(app_ext_env("ENV_KEY_EMPTY") === "");
        $this->assertTrue(app_ext_env("ENV_KEY_INT_ZERO") === 0);
        $this->assertTrue(app_ext_env("ENV_KEY_INT_ONE") === 1);
        $this->assertTrue(app_ext_env("ENV_KEY_FLOAT") === 1.2);


    }
}
