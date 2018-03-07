<?php

namespace Roles\Collections;

use Phalcon\Mvc\MongoCollection;

class RoleCollection extends MongoCollection
{
    public $name;
    public $ref_type;
    public $ref_id;
    public $created_at;
    public $updated_at;
    public $permissions;

    public function getSource()
    {
        return 'roles';
    }
}