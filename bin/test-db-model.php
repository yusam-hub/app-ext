<?php

require __DIR__ . '/../global-inc.php';

try {
    $userModel = \YusamHub\AppExt\Db\Model\UserModel::findModelByAttributesOrFail([
        'id' => 1,
    ]);
    /*$userModel = new \YusamHub\AppExt\Db\Model\UserModel();
    $userModel->id = 0;
    $userModel->profileSurname = random_int(100000,999999);
    var_dump($userModel->save());*/
    print_r($userModel->toArray());
} catch (\Throwable $e) {
    print_r(app_ext_get_error_context($e));
}

