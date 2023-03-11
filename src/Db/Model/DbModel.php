<?php

namespace YusamHub\AppExt\Db\Model;

use YusamHub\AppExt\Db\DbKernel;
use YusamHub\AppExt\Exceptions\AppExtRuntimeException;
use YusamHub\AppExt\Traits\GetSetDbKernelTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetDbKernelInterface;
use YusamHub\JsonExt\JsonObject;
abstract class DbModel
    extends JsonObject
    implements GetSetDbKernelInterface
{
    use GetSetDbKernelTrait;
    protected ?string $connectionName = null;
    protected string $tableName;
    protected string $primaryKey = 'id';
    protected array $originalValues = [];

    /**
     * @param DbKernel $dbKernel
     * @param $pk
     * @return DbModel|null|object
     * @throws \ReflectionException
     */
    public static function findModel(DbKernel $dbKernel, $pk)
    {
        $model = new static();
        $row = $dbKernel->newPdoExt($model->connectionName)
            ->findModel(get_class($model), $model->tableName, $model->primaryKey, $pk);
        if ($row instanceof DbModel) {
            $row->setDbKernel($dbKernel);
            $row->triggerAfterLoad();
            return $row;
        }
        return null;
    }

    /**
     * @param DbKernel $dbKernel
     * @param array $attributes
     * @return DbModel|null|object
     * @throws \ReflectionException
     */
    public static function findModelByAttributes(DbKernel $dbKernel, array $attributes)
    {
        $model = new static();
        $row = $dbKernel->newPdoExt($model->connectionName)
            ->findModelByAttributes(get_class($model), $model->tableName, $attributes);
        if ($row instanceof DbModel) {
            $row->setDbKernel($dbKernel);
            $row->triggerAfterLoad();
            return $row;
        }
        return null;
    }

    /**
     * @param DbKernel $dbKernel
     * @param $pk
     * @return object|DbModel
     * @throws \ReflectionException
     */
    public static function findModelOrFail(DbKernel $dbKernel, $pk)
    {
        $model = static::findModel($dbKernel, $pk);
        if (is_null($model)) {
            throw new AppExtRuntimeException("Model not found", [
                (new static())->primaryKey => $pk,
            ]);
        }
        return $model;
    }

    /**
     * @param DbKernel $dbKernel
     * @param array $attributes
     * @return DbModel|null|object
     * @throws \ReflectionException
     */
    public static function findModelByAttributesOrFail(DbKernel $dbKernel, array $attributes)
    {
        $model = static::findModelByAttributes($dbKernel, $attributes);
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

            $primaryValue = $this->getDbKernel()->newPdoExt($this->connectionName)->insertReturnId(
                    $this->tableName,
                    $this->toArray()
                );

            if (!empty($primaryValue)) {
                $this->{$this->primaryKey} = $primaryValue;
            }

            if (app_ext_db_global()->newPdoExt($this->connectionName)->affectedRows() === 1) {

                $this->originalValues = $this->toArray();

                $this->triggerAfterSave(true);

                return true;
            }

            $this->triggerAfterSave(false);

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

        $result = $this->getDbKernel()->newPdoExt($this->connectionName)->update(
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

        $this->triggerAfterSave($result);

        return $result;
    }

    protected function triggerBeforeUpdate(): void
    {

    }

    protected function triggerBeforeInsert(): void
    {

    }

    protected function triggerAfterSave(bool $saveResult): void
    {

    }

    /**
     * @throws \ReflectionException
     */
    protected function triggerAfterLoad(): void
    {
        $this->originalValues = $this->toArray();
    }
}