<?php

namespace YusamHub\AppExt\Db\Model;

/**
 * @method static UserModel|null findModel($pk)
 * @method static UserModel findModelOrFail($pk)
 */
class UserModel extends DbModel
{
    protected string $tableName = 'users';

    public int $id;
    public string $profileSurname;
}