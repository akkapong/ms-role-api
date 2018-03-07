<?php
namespace Roles\Repositories;

use Core\Repositories\CollectionRepositories;
use Roles\Collections\RoleCollection;

class RoleRepositories extends CollectionRepositories {

    //==== Start: Define variable ====//
    public $module         = 'roles';
    public $collectionName = 'RoleCollection';
    public $allowFilter    = ['name', 'ref_type', 'ref_id', 'created_at', 'updated_at'];
    public $model;
    //==== Start: Define variable ====//


    //==== Start: Support method ====//
    public function __construct()
    {
        $this->model = new RoleCollection();
        parent::__construct();
    }
    //==== End: Support method ====//
}