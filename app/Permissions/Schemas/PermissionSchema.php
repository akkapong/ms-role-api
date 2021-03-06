<?php

namespace Permissions\Schemas;

/**
 * Copyright 2015 info@neomerx.com (www.neomerx.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use \Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @package Neomerx\Samples\JsonApi
 */
class PermissionSchema extends SchemaProvider
{
    protected $resourceType = 'permissions';

    public function getId($permission)
    {
        /** @var Permission $permission */
        return (string)$permission->_id;
    }

    public function getAttributes($permission)
    {
        /** @var Permission $permission */
        return [
            'module'     => $permission->module,
            'role_id'    => $permission->role_id,
            'can_read'   => $permission->can_read,
            'can_create' => $permission->can_create,
            'can_update' => $permission->can_update,
            'can_delete' => $permission->can_delete,
            'created_at' => $permission->created_at,
            'updated_at' => $permission->updated_at,
        ];
    }
}