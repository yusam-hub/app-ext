<?php

namespace YusamHub\AppExt\Db\Model;

use YusamHub\AppExt\Exceptions\AppExtRuntimeException;
use YusamHub\JsonExt\JsonObject;
abstract class BaseModel extends JsonObject
{
    protected ?string $connectionName = null;
    protected string $tableName;
    protected string $primaryKey = 'id';

     /**
     * @param $pk
     * @return BaseModel|null|object
      */
    public static function findModel($pk)
    {
        $model = new static();
        $row = db()
            ->connection($model->connectionName)
            ->fetchOne(
                strtr("SELECT * FROM " . $model->tableName . " WHERE :primaryKey = ? LIMIT 0,1", [
                    ':primaryKey' => $model->primaryKey,
                ]),
                [
                    $pk
                ],
                get_class($model)
            );
        if (is_object($row)) {
            return $row;
        }
        return null;
    }

    /**
     * @param $pk
     * @return object|BaseModel
     */
    public static function findModelOrFail($pk)
    {
        $model = static::findModel($pk);
        if (is_null($model)) {
            throw new AppExtRuntimeException("Model not found", [
                (new static())->primaryKey => $pk,
            ]);
        }
        return $model;
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    public function save(): bool
    {
        return db()
            ->connection($this->connectionName)
            ->update($this->tableName, $this->toArray());

    }
}