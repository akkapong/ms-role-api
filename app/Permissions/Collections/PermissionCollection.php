<?php

namespace Permissions\Collections;

use Phalcon\Mvc\MongoCollection;

class PermissionCollection extends MongoCollection
{
    public $module;
    public $role_id;
    public $can_read;
    public $can_create;
    public $can_update;
    public $can_delete;
    public $created_at;
    public $updated_at;

    public function getSource()
    {
        return 'permissions';
    }
}