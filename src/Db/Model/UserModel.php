<?php

namespace YusamHub\AppExt\Db\Model;

/**
 * @method static UserModel|null findModel($pk)
 * @method static UserModel findModelOrFail($pk)
 * @method static UserModel|null findModelByAttributes(array $attributes)
 * @method static UserModel findModelByAttributesOrFail(array $attributes)
 */
class UserModel extends DbModel
{
    protected string $tableName = 'users';

    public int $id;
    public string $profileSurname;
}