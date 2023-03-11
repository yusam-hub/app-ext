<?php

namespace YusamHub\AppExt\Traits\Interfaces;

use YusamHub\AppExt\Db\DbKernel;

interface GetSetDbKernelInterface
{
    public function hasDbKernel(): bool;
    public function getDbKernel(): ?DbKernel;
    public function setDbKernel(?DbKernel $dbKernel): void;
}