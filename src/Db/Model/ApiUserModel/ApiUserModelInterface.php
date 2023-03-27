<?php

namespace YusamHub\AppExt\Db\Model\ApiUserModel;

/**
 * @property int $id
 * @property string $apiToken
 * @property string $apiSign
 * @property string $description
 * @property string|null $blockedAt
 * @property string|null $blockedDescription
 * @property string $createdAt
 * @property string|null $modifiedAt
 */
interface ApiUserModelInterface
{
    const ATTRIBUTE_NAME_ID = 'id';
    const ATTRIBUTE_NAME_API_TOKEN = 'apiToken';
    const ATTRIBUTE_NAME_API_SIGN = '$apiSign';
    const ATTRIBUTE_NAME_DESCRIPTION = '$description';
    const ATTRIBUTE_NAME_BLOCKED_AT = '$blockedAt';
    const ATTRIBUTE_NAME_BLOCKED_DESCRIPTION = '$blockedDescription';
    const ATTRIBUTE_NAME_CREATED_AT = 'createdAt';
    const ATTRIBUTE_NAME_MODIFIED_AT = 'modifiedAt';
}
