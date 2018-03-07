<?php
namespace Permissions\Repositories;

use Core\Repositories\CollectionRepositories;
use Permissions\Collections\PermissionCollection;

class PermissionRepositories extends CollectionRepositories {

    //==== Start: Define variable ====//
    public $module         = 'permissions';
    public $collectionName = 'PermissionCollection';
    public $allowFilter    = ['module', 'role_id', 'can_read', 'can_create', 'can_update', 'can_delete', 'created_at', 'updated_at'];
    public $model;
    //==== Start: Define variable ====//


    //==== Start: Support method ====//
    public function __construct()
    {
        $this->model = new PermissionCollection();
        parent::__construct();
    }
    //==== End: Support method ====//
}