<?php

namespace YusamHub\AppExt\Db\Model;

use YusamHub\DbExt\Interfaces\PdoExtKernelInterface;
use YusamHub\DbExt\Interfaces\PdoExtModelInterface;
use YusamHub\DbExt\Traits\GetSetPdoExtKernelTrait;
use YusamHub\DbExt\Traits\PdoExtModelTrait;
use YusamHub\JsonExt\JsonObject;
use YusamHub\Validator\Validator;

/**
 * @method static PdoExtModel|null findModel(PdoExtKernelInterface $pdoExtKernel, $pk)
 * @method static PdoExtModel findModelOrFail(PdoExtKernelInterface $pdoExtKernel, $pk)
 * @method static PdoExtModel|null findModelByAttributes(PdoExtKernelInterface $pdoExtKernel, array $attributes)
 * @method static PdoExtModel findModelByAttributesOrFail(PdoExtKernelInterface $pdoExtKernel, array $attributes)
 */
abstract class PdoExtModel extends JsonObject implements \YusamHub\DbExt\Interfaces\GetSetPdoExtKernelInterface, PdoExtModelInterface
{
    use GetSetPdoExtKernelTrait;
    use PdoExtModelTrait;


    protected ?string $connectionName = null;

    protected array $rules = [];
    protected array $ruleMessages = [];

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