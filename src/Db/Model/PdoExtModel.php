<?php

namespace YusamHub\AppExt\Db\Model;

use YusamHub\DbExt\Interfaces\GetSetPdoExtKernelInterface;
use YusamHub\DbExt\Interfaces\PdoExtKernelInterface;
use YusamHub\DbExt\Interfaces\PdoExtModelInterface;
use YusamHub\DbExt\Traits\GetSetPdoExtKernelTrait;
use YusamHub\DbExt\Traits\PdoExtModelTrait;
use YusamHub\ModelExt\ModelExt;
use YusamHub\Validator\Validator;

/**
 * @method static PdoExtModel|null findModel(PdoExtKernelInterface $pdoExtKernel, $pk)
 * @method static PdoExtModel findModelOrFail(PdoExtKernelInterface $pdoExtKernel, $pk)
 * @method static PdoExtModel|null findModelByAttributes(PdoExtKernelInterface $pdoExtKernel, array $attributes)
 * @method static PdoExtModel findModelByAttributesOrFail(PdoExtKernelInterface $pdoExtKernel, array $attributes)
 */
abstract class PdoExtModel
    extends
    ModelExt
    implements
    GetSetPdoExtKernelInterface,
    PdoExtModelInterface
{
    use GetSetPdoExtKernelTrait;
    use PdoExtModelTrait;

    protected array $rules = [];
    protected array $ruleMessages = [];

    /**
     * @param $errors
     * @return bool
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

    public function modelExtImportAttributes(array $attributes, bool $exceptionNotExists = true): void
    {
        $this->savedAttributes = [];
        parent::modelExtImportAttributes($attributes, $exceptionNotExists);
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->modelExtAttributes();
    }
}