<?php

namespace YusamHub\AppExt\Db\Model;

use YusamHub\AppExt\Db\DbKernel;
use YusamHub\AppExt\Traits\GetSetDbKernelTrait;
use YusamHub\AppExt\Traits\Interfaces\GetSetDbKernelInterface;
use YusamHub\DbExt\Interfaces\PdoExtModelInterface;
use YusamHub\DbExt\Traits\PdoExtModelTrait;
use YusamHub\JsonExt\JsonObject;
use YusamHub\Validator\Validator;

abstract class DbModel
    extends JsonObject
    implements GetSetDbKernelInterface, PdoExtModelInterface
{
    use PdoExtModelTrait;
    use GetSetDbKernelTrait;

    protected ?string $connectionName = null;

    protected array $rules = [];
    protected array $ruleMessages = [];

    public function setDbKernel(?DbKernel $dbKernel): void
    {
        $this->dbKernel = $dbKernel;
        if (!is_null($this->dbKernel)) {
            $this->pdoExt = $this->dbKernel->pdoExt($this->connectionName);
        }
    }

    /**
     * @param $errors
     * @return bool
     * @throws \ReflectionException
     */
    public function validate(&$errors): bool
    {
        $validator = new Validator();
        $validator->setAttributes($this->getAttributes());
        $validator->setRules($this->rules);
        $validator->setRuleMessages($this->ruleMessages);
        $result = $validator->validate();
        $errors = $validator->getErrors();
        return $result;
    }

    public function import($source, array $filterKeys = []): void
    {
        $this->savedAttributes = [];
        parent::import($source, $filterKeys);
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getAttributes(): array
    {
        return $this->toArray();
    }
}