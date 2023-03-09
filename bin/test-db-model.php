<?php

require __DIR__ . '/../global-inc.php';

try {
    $userModel = \YusamHub\AppExt\Db\Model\UserModel::findModelOrFail(1);
    print_r($userModel->toArray());
    $userModel->profileSurname = random_int(100000,999999);
    $userModel->save();
    print_r($userModel->toArray());
} catch (\Throwable $e) {
    print_r(app_ext_get_error_context($e));
}

