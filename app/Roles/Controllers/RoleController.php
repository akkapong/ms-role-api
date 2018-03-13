<?php
namespace Roles\Controllers;

use Core\Controllers\ControllerBase;

use Roles\Schemas\RoleSchema;
use Roles\Collections\RoleCollection;
use Roles\Services\RoleService;


/**
 * Display the default index page.
 */
class RoleController extends ControllerBase
{
    //==== Start: Define variable ====//
    private $module = 'roles';
    private $roleService;
    private $modelName;
    private $schemaName;

    private $getDetailRule = [
        [
            'type'   => 'required',
            'fields' => ['id'],
        ]
    ];

    private $createRule = [
        [
            'type'   => 'required',
            'fields' => ['name', 'ref_type'],
        ],
    ];

    private $updateRule = [
        [
            'type'   => 'required',
            'fields' => ['id'],
        ],
    ];

    private $deleteRule = [
        [
            'type'   => 'required',
            'fields' => ['id'],
        ],
    ];
    //==== End: Define variable ====//

    //==== Start: Support method ====//
    //Method for initial some variable
    public function initialize()
    {
        $this->roleService = new RoleService();
        $this->modelName   = RoleCollection::class;
        $this->schemaName  = RoleSchema::class;
    }

    //==== End: Support method ====//

    //==== Start: Main method ====//
    public function getRoleAction()
    {
        //get input
        $params = $this->getUrlParams();

        $limit  = (isset($params['limit']))?$params['limit']:null;
        $offset = (isset($params['offset']))?$params['offset']:null;

        //validate input
        //TODO: add validate here

        //get data in service
        $result = $this->roleService->getRoleList($params, $limit, $offset);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], '/roles');
        }

        // print_r(count($result['data'])); exit;

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        //get total
        $total  = (isset($result['total']))?$result['total']:null;

        return $this->response($encoder, $result['data'], $limit, $offset, $total);

        
    }

    public function getRoledetailAction(string $id)
    {
        //get data in service
        $result = $this->roleService->getRoleDetail($id);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], '/roles/'.$id);
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);

        
    }

    public function postRoleAction()
    {
        //get input
        $params = $this->getPostInput();

        //validate input
        //TODO: add validate here


        //define default
        $default = [];

        // Validate input
        $params = $this->myValidate->validateApi($this->createRule, $default, $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], '/roles');
        }

        //CREATE data by input
        $result = $this->roleService->createRole($params);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], '/roles');
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);
    }

    public function putRoleAction(string $id)
    {
        //get input
        $inputs       = $this->getPostInput();

        $inputs['id'] = $id;
        
        //define default
        $default      = [];

        // Validate input
        $params = $this->myValidate->validateApi($this->updateRule, $default, $inputs);

        if (isset($params['validate_error']))
        {
            //Validate error
            return $this->responseError($params['validate_error'], '/roles');
        }

        //UPDATE data by input
        $result = $this->roleService->updateRole($params);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], '/roles');
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);
    }

    public function deleteRoleAction(string $id)
    {
        //update member data
        $result  = $this->roleService->deleteRole($id);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], '/roles');
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);
    }
    //==== End: Main method ====//
}
