<?php

namespace Sxqibo\FastYks;

/**
 * 部门信息接口
 * 
 * 获取指定组织（校区）部门信息
 * 访问控制: 50次/分
 */
final class Department extends RequestApi
{
    /**
     * 获取组织部门信息
     * 
     * 请求方式: POST
     * 访问控制: 50次/分
     * 
     * @param string $orgName 组织名称，非必填
     * @return array 返回部门列表，每个部门包含 id（部门ID）、title（部门名称）、parentId（上级ID）
     * @throws \Exception
     * 
     * 返回示例:
     * [
     *   {"id":"155610795603218","title":"部门一","parentId":"14"}
     * ]
     */
    public function getDepartments($orgName = '')
    {
        $query = [
            'sp'      => $this->sp,
            'sid'     => $this->sid,
            'orgName' => $orgName
        ];

        $url = $this->url . UriConst::GET_DEPARTMENTS . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

    /**
     * 14. 添加部门信息
     *
     * @return array
     *
     * "name": "测试部门一定要长1111111111111",
     * "pid": "915",
     * "id": "17292321058572157",
     * "sid": 915
     */
    public function addDept($deptName = '')
    {
        $data = [
            'deptName' => $deptName,
            'pid'      => $this->sid,
            'teamId'   => $this->sid
        ];

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::ADD_DEPT . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

    /**
     * 15. 修改部门信息
     *
     * @return array
     */
    public function updateDept($deptId, $deptName, $pid)
    {
        $data = [
            'deptId'   => $deptId,
            'deptName' => $deptName,
            'pid'      => $pid,
            'teamId'   => $this->sid
        ];

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::UPDATE_DEPT . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

    /**
     * 16. 删除部门信息
     *
     * @return array
     */
    public function deleteDept($deptId)
    {
        $data = [
            'deptId' => [
                $deptId
            ],
            'teamId' => $this->sid
        ];

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::DELETE_DEPT . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

}
