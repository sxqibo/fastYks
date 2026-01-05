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
     * 添加部门信息
     * 
     * 请求方式: POST
     * 请求体: JSON格式
     * 
     * @param string $deptName 部门名称（必填）
     * @param string|int $pid 上级部门ID，顶级部门设置为学校ID（可选，默认为学校ID）
     * @return array 返回新建部门信息，包含 teamId、name、pid、id
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "code": 200,
     *   "message": null,
     *   "value": {
     *     "teamId": "14",
     *     "name": "接口测试",
     *     "pid": "155610795603218",
     *     "id": "15668908891012157"
     *   }
     * }
     * 
     * 其中 id 为新建立部门在一脸通的ID
     */
    public function addDept($deptName, $pid = null)
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }
    
        if (empty($deptName)) {
            throw new \InvalidArgumentException('部门名称（deptName）不能为空');
        }
    
        // 如果没有指定 pid，默认使用 sid（学校ID）作为顶级部门
        if ($pid === null) {
            $pid = $this->sid;
        }
    
        $data = [
            'deptName' => $deptName,
            'pid'      => (string)$pid,
            'teamId'   => $this->sid
        ];
    
        $query = [
            'sp' => $this->sp
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
     * 修改部门信息
     * 
     * 请求方式: POST
     * 请求体: JSON格式
     * 
     * @param string $deptId 部门ID（必填）
     * @param string $deptName 部门名称（必填）
     * @param string|int $pid 上级部门ID，顶级部门设置为学校ID（必填）
     * @return array 返回结果
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "code": 200,
     *   "message": null,
     *   "value": null
     * }
     */
    public function updateDept($deptId, $deptName, $pid)
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        if (empty($deptId)) {
            throw new \InvalidArgumentException('部门ID（deptId）不能为空');
        }

        if (empty($deptName)) {
            throw new \InvalidArgumentException('部门名称（deptName）不能为空');
        }

        if ($pid === null || $pid === '') {
            throw new \InvalidArgumentException('上级部门ID（pid）不能为空');
        }

        $data = [
            'deptId'   => (string)$deptId,
            'deptName' => $deptName,
            'pid'      => (string)$pid,
            'teamId'   => $this->sid
        ];

        $query = [
            'sp' => $this->sp
        ];

        $url = $this->url . UriConst::UPDATE_DEPT . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = $this->parseResponse($response);

        return $ret;
    }

    /**
     * 删除部门信息
     * 
     * 请求方式: POST
     * 请求体: JSON格式
     * 
     * ⚠️ 重要提示：
     * 1. 只有当部门下没有子部门或者人员时，才能成功删除
     * 2. 如果部门下还有子部门或人员，删除会失败
     * 3. 建议删除前先检查部门下是否还有子部门或人员
     * 
     * @param array|string $deptId 部门ID，可以是单个ID或ID数组，例如：['15952281722262157']
     * @return array 返回结果
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "code": 200,
     *   "message": null,
     *   "value": null
     * }
     */
    public function deleteDept($deptId)
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        // 如果是单个ID，转换为数组
        if (!is_array($deptId)) {
            $deptId = [$deptId];
        }

        // 验证部门ID不为空
        if (empty($deptId)) {
            throw new \InvalidArgumentException('部门ID（deptId）不能为空');
        }

        // 转换为字符串数组
        $deptId = array_map('strval', $deptId);

        $data = [
            'deptId' => $deptId,
            'teamId' => $this->sid
        ];

        $query = [
            'sp' => $this->sp
        ];

        $url = $this->url . UriConst::DELETE_DEPT . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = $this->parseResponse($response);

        return $ret;
    }

}
