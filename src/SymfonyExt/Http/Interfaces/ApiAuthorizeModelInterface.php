<?php

namespace YusamHub\AppExt\SymfonyExt\Http\Interfaces;

interface ApiAuthorizeModelInterface
{
    public function getAuthorizeIdentifierAsInt(): int;
    public function getAuthorizeIdentifierAsString(): string;
}