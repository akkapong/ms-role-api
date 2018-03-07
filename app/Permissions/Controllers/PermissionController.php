<?php
namespace Permissions\Controllers;

use Core\Controllers\ControllerBase;

use Permissions\Schemas\PermissionSchema;
use Permissions\Collections\PermissionCollection;
use Permissions\Services\PermissionService;


/**
 * Display the default index page.
 */
class PermissionController extends ControllerBase
{
    //==== Start: Define variable ====//
    private $module = 'roles';
    private $RoleService;
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
            'fields' => ['module', 'role_id'],
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
        $this->permissionService = new PermissionService();
        $this->modelName         = PermissionCollection::class;
        $this->schemaName        = PermissionSchema::class;
    }

    //==== End: Support method ====//

    //==== Start: Main method ====//
    public function getPermissionAction()
    {
        //get input
        $params = $this->getUrlParams();

        $limit  = (isset($params['limit']))?$params['limit']:null;
        $offset = (isset($params['offset']))?$params['offset']:null;

        //validate input
        //TODO: add validate here

        //get data in service
        $result = $this->permissionService->getPermissionList($params, $limit, $offset);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], '/permissions');
        }

        // print_r(count($result['data'])); exit;

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        //get total
        $total  = (isset($result['total']))?$result['total']:null;

        return $this->response($encoder, $result['data'], $limit, $offset, $total);

        
    }

    public function getPermissiondetailAction(string $id)
    {
        //get data in service
        $result = $this->permissionService->getPermissionDetail($id);

        if (!$result['success']) {
            //process error
            return $this->responseError($result['message'], '/permissions/'.$id);
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);

        
    }

    public function postPermissionAction()
    {
        //get input
        $params = $this->getPostInput();

        //validate input
        //TODO: add validate here


        //define default
        $default = [
            'can_read'   => false,
            'can_create' => false,
            'can_update' => false,
            'can_delete' => false,
        ];

        // Validate input
        $params = $this->myValidate->validateApi($this->createRule, $default, $params);

        if (isset($params['validate_error'])) {
            //Validate error
            return $this->responseError($params['validate_error'], '/permissions');
        }

        //CREATE data by input
        $result = $this->permissionService->createPermission($params);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], '/permissions');
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);
    }

    public function putPermissionAction(string $id)
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
            return $this->responseError($params['validate_error'], '/permissions');
        }

        //UPDATE data by input
        $result = $this->permissionService->updatePermission($params);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], '/permissions');
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);
    }

    public function deletePermissionAction(string $id)
    {
        //update member data
        $result  = $this->permissionService->deletePermission($id);

        //Check response error
        if (!$result['success'])
        {
            //process error
            return $this->responseError($result['message'], '/permissions');
        }

        //return data
        $encoder = $this->createEncoder($this->modelName, $this->schemaName);

        return $this->response($encoder, $result['data']);
    }
    //==== End: Main method ====//
}
