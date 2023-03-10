<?php

namespace YusamHub\AppExt\Db\Model;

use YusamHub\AppExt\Exceptions\AppExtRuntimeException;
use YusamHub\JsonExt\JsonObject;
abstract class DbModel extends JsonObject
{
    protected ?string $connectionName = null;
    protected string $tableName;
    protected string $primaryKey = 'id';

    protected array $originalValues = [];

    /**
     * @param $pk
     * @return DbModel|null|object
     * @throws \ReflectionException
     */
    public static function findModel($pk)
    {
        $model = new static();
        $row = db()
            ->connection($model->connectionName)
            ->fetchOne(
                strtr("SELECT * FROM `" . $model->tableName . "` WHERE `:primaryKey` = ? LIMIT 0,1", [
                    ':primaryKey' => $model->primaryKey,
                ]),
                [
                    $pk
                ],
                get_class($model)
            );
        if ($row instanceof DbModel) {
            $row->originalValues = $row->toArray();
            return $row;
        }
        return null;
    }

    /**
     * @param array $attributes
     * @return DbModel|null|object
     * @throws \ReflectionException
     */
    public static function findModelByAttributes(array $attributes)
    {
        $model = new static();
        $where = [];
        $bindings = [];
        foreach($attributes as $key => $value) {
            $where[] = sprintf("`%s` = ?", $key);
            $bindings[] = $value;
        }
        $row = db()
            ->connection($model->connectionName)
            ->fetchOne(
                strtr("SELECT * FROM `" . $model->tableName . "`:where LIMIT 0,1", [
                    ':where' => !empty($where) ? ' WHERE '. implode('AND', $where) : '',
                ]),
                $bindings,
                get_class($model)
            );
        if ($row instanceof DbModel) {
            $row->originalValues = $row->toArray();
            return $row;
        }
        return null;
    }

    /**
     * @param $pk
     * @return object|DbModel
     * @throws \ReflectionException
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
     * @param array $attributes
     * @return DbModel|null|object
     * @throws \ReflectionException
     */
    public static function findModelByAttributesOrFail(array $attributes)
    {
        $model = static::findModelByAttributes($attributes);
        if (is_null($model)) {
            throw new AppExtRuntimeException("Model not found", $attributes);
        }
        return $model;
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    public function save(): bool
    {
        /**
         * INSERT
         */
        if (empty($this->{$this->primaryKey})) {

            $this->triggerBeforeInsert();

            $primaryValue = db()
                ->connection($this->connectionName)
                ->insertReturnId(
                    $this->tableName,
                    $this->toArray()
                );

            if (!empty($primaryValue)) {
                $this->{$this->primaryKey} = $primaryValue;
            }

            if (db()->connection($this->connectionName)->affectedRows() === 1) {
                $this->originalValues = $this->toArray();
                return true;
            }

            return false;
        }
        /**
         * UPDATE
         */
        $changedValues = array_diff_assoc($this->toArray(), $this->originalValues);

        if (isset($changedValues[$this->primaryKey])) {
            unset($changedValues[$this->primaryKey]);
        }

        if (empty($changedValues)) {
            return true;
        }

        $this->triggerBeforeUpdate();

        $result = db()
            ->connection($this->connectionName)
            ->update(
                $this->tableName,
                $changedValues,
                [
                    $this->primaryKey => $this->{$this->primaryKey}
                ],
                1
            );

        if ($result) {
            $this->originalValues = $this->toArray();
        }

        return $result;
    }

    protected function triggerBeforeUpdate(): void
    {

    }

    protected function triggerBeforeInsert(): void
    {

    }
}