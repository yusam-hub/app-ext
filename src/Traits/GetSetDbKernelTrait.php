<?php

namespace YusamHub\AppExt\Traits;

use YusamHub\AppExt\Db\DbKernel;

trait GetSetDbKernelTrait
{
    private ?DbKernel $dbKernel = null;

    public function hasDbKernel(): bool
    {
        return !is_null($this->dbKernel);
    }
    public function getDbKernel(): ?DbKernel
    {
        return $this->dbKernel;
    }
    public function setDbKernel(?DbKernel $dbKernel): void
    {
        $this->dbKernel = $dbKernel;
    }
}