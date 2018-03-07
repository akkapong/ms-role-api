<?php
namespace Roles\Services;

use Roles\Repositories\RoleRepositories;
use Roles\Collections\RoleCollection;

use Permissions\Services\PermissionService;

class RoleService extends RoleRepositories
{
    //==== Start: Define variable ====//
    private $permissionCollection;
    
    //==== End: Define variable ====//
    public function __construct()
    {
        $this->permissionService = new PermissionService();
        parent::__construct();
    }

    //==== Start: Support method ====//

    //Method for create filter for check duplicate
    protected function createFilterForCheckDup(string $name, string $refType): array
    {
        return [
            'name'     => $name,
            'ref_type' => $refType,
        ];
    }



    //Method for get permission data
    protected function getPermissionDatas(string $roleId)
    {
        $res = $this->permissionService->getPermissionByRole($roleId);

        //error case
        if (!$res['success']) return [];

        return $res['data'];
    }


    //==== End: Support method ====//


    //==== Stat: Main method ====//
    //Method for get data by filter
    public function getRoleList(array $params, ?int $limit, ?int $offset): array
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        try {
            //create filter
            $roles = $this->getDataByParams($params);
            
            if (!empty($limit)) {
                //get total record
                $outputs['total'] = $roles[1];
            }

            $outputs['data'] = $roles[0];

        } catch (\Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = 'missionFail';
        }
        

        return $outputs;
    }

    //Method for get data by id
    public function getRoleDetail(string $id): array
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        try {
            //create filter
            $role  = $this->getDataById($id);

            if (empty($role)){
                $outputs['success'] = false;
                $outputs['message'] = 'dataNotFound';
                return $outputs;
            }

            //get permission data
            $role->permissions = $this->getPermissionDatas((string)$role->_id);
            $outputs['data']   = $role;

        } catch (\Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = 'missionFail';
        }
        

        return $outputs;
    }


    //Method for insert data
    public function createRole(array $params): array
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];

        //Check Duplicate
        $filters = $this->createFilterForCheckDup($params['name'], $params['ref_type']);
        $isDups  = $this->checkDuplicate($filters);

        if ($isDups[0]) {
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'dataDuplicate';
            return $output;
        } 

        //get current date
        $current = date('Y-m-d H:i:s');
        
        //default date
        $params['created_at'] = $current;
        $params['updated_at'] = $current;

        //insert
        $res = $this->insertData($params);

        if (!$res)
        {
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'insertError';
            return $output;
        } 

        
        //add config data
        $output['data'] = $res;

        return $output;
    }

    //Method for update data
    public function updateRole(array $params): array
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];

        //get data by id
        $role  = $this->getDataById($params['id']);

        //get value for check duplicate
        $name    = isset($params['name'])? $params['name'] : $role->name;
        $refType = isset($params['ref_type'])? $params['ref_type'] : $role->ref_type;

        //Check Duplicate
        $filters = $this->createFilterForCheckDup($name, $refType);
        $isDups  = $this->checkDuplicate($filters);

        if ( $isDups[0] && ((string)$isDups[1]->_id != $params['id']) ) {
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'dataDuplicate';
            return $output;
        } 

        

        //default date
        $params['updated_at'] = date('Y-m-d H:i:s');
        //update
        $res = $this->updateData($role, $params);

        if (!$res)
        {
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'updateError';
            return $output;
        }

        
        //add config data
        $output['data'] = $res;

        return $output;
    }


    //Method for delete data
    public function deleteRole(string $id): array
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];

        //get data by id
        $role  = $this->getDataById($id);

        if (empty($role))
        {
            //No Data
            $output['success'] = false;
            $output['message'] = 'dataNotFound';
            return $output;
        }

        //delete
        $res = $this->deleteData($role);

        if (!$res)
        {
            //Cannot insert
            $output['success'] = false;
            $output['message'] = 'deleteError';
            return $output;
        }

        //get insert id
        $output['data'] = $res;

        return $output;
    }
    //==== End: Main method ====//
}