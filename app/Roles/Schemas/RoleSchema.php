<?php

namespace Roles\Schemas;

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
class RoleSchema extends SchemaProvider
{
    protected $resourceType = 'roles';



    public function getId($role)
    {
        /** @var Role $role */
        return (string)$role->_id;
    }

    //Method for formate permission data
    protected function formatPermission($permissions)
    {
        //Define outputs 
        $outputs = [];

        foreach ($permissions as $permission) {
            //format id
            $permission->id = (string)$permission->_id;
            //unset _id
            unset($permission->_id);
            $outputs[]      = $permission;
        }

        return $outputs;
    }

    public function getAttributes($role)
    {
        /** @var Role $role */
        $datas = [
            'name'       => $role->name,
            'ref_type'   => $role->ref_type,
            'ref_id'     => $role->ref_id,
            'created_at' => $role->created_at,
            'updated_at' => $role->updated_at,
        ];

        if (property_exists($role, 'permissions') && !empty($role->permissions)) {
            $datas['permissions'] = $this->formatPermission($role->permissions);
        }

        return $datas;
    }
}