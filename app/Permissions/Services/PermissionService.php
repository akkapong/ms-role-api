<?php
namespace Permissions\Services;

use Permissions\Repositories\PermissionRepositories;
use Permissions\Collections\PermissionCollection;

class PermissionService extends PermissionRepositories
{
    //==== Start: Define variable ====//
    
    //==== End: Define variable ====//


    //==== Start: Support method ====//

    //Method for create filter for check duplicate
    protected function createFilterForCheckDup(string $module, string $roleId): array
    {
        return [
            'module'  => $module,
            'role_id' => $roleId,
        ];
    }


    //==== End: Support method ====//


    //==== Stat: Main method ====//
    //Method for get data by filter
    public function getPermissionList(array $params, ?int $limit, ?int $offset): array
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        try {
            //create filter
            $permissions = $this->getDataByParams($params);
            
            if (!empty($limit)) {
                //get total record
                $outputs['total'] = $permissions[1];
            }

            $outputs['data'] = $permissions[0];

        } catch (\Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = 'missionFail';
        }
        

        return $outputs;
    }

    //Method for get data by id
    public function getPermissionDetail(string $id): array
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        try {
            //create filter
            $permission  = $this->getDataById($id);

            if (empty($permission)){
                $outputs['success'] = false;
                $outputs['message'] = 'dataNotFound';
                return $outputs;
            }

            $outputs['data'] = $permission;

        } catch (\Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = 'missionFail';
        }
        

        return $outputs;
    }


    //Method for insert data
    public function createPermission(array $params): array
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];

        //Check Duplicate
        $filters = $this->createFilterForCheckDup($params['module'], $params['role_id']);
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
    public function updatePermission(array $params): array
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];
        //get data by id
        $permission  = $this->getDataById($params['id']);

        //get value for check duplicate
        $module = isset($params['module'])? $params['module'] : $permission->module;
        $roleId = isset($params['role_id'])? $params['role_id'] : $permission->role_id;


        //Check Duplicate
        $filters = $this->createFilterForCheckDup($module, $roleId);
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
        $res = $this->updateData($permission, $params);

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
    public function deletePermission(string $id): array
    {
        //Define output
        $output = [
            'success' => true,
            'message' => '',
            'data'    => '',
        ];

        //get data by id
        $permission  = $this->getDataById($id);

        if (empty($permission))
        {
            //No Data
            $output['success'] = false;
            $output['message'] = 'dataNotFound';
            return $output;
        }

        //delete
        $res = $this->deleteData($permission);

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

    //Method for get permission by role
    public function getPermissionByRole(string $roleId): array
    {
        //Define output
        $outputs = [
            'success' => true,
            'message' => '',
        ];

        try {
            $params = [
                'role_id' => $roleId
            ];

            //create filter
            $permissions = $this->getDataByParams($params);
            $outputs['data'] = $permissions[0];

        } catch (\Exception $e) {
            $outputs['success'] = false;
            $outputs['message'] = 'missionFail';
        }
        

        return $outputs;
    }
    //==== End: Main method ====//
}