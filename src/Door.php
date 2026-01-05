<?php

namespace Sxqibo\FastYks;

/**
 * 门禁信息接口
 * 
 * 提供门禁相关的接口功能：
 * - 获取门禁流水信息
 * - 获取门禁规则信息
 * - 保存或更新门禁规则信息
 * - 删除门禁规则信息
 * 
 * 访问控制: 10次/分（门禁流水接口）
 */
final class Door extends RequestApi
{
    /**
     * 获取门禁流水信息
     * 
     * 请求方式: POST
     * 访问控制: 10次/分
     * 
     * @param int $page 页数，页数从1页开始，不能大于100
     * @param int $limit 分页返回条数，不能大于1000
     * @param int $doorId 设备编号，请参见获取设备信息返回结果。传入0为获取全部流水
     * @param string $beginTime 流水开始时间，格式采用yyyy-MM-dd HH:mm:ss。如果为空，则默认为当前时间前10分钟
     * @param string $endTime 流水结束时间，格式采用yyyy-MM-dd HH:mm:ss。如果为空，则默认为当前时间
     * @return array 返回门禁流水信息，包含：
     *   - total: 总记录数
     *   - list: 流水列表，每个流水包含：
     *     - cid: 用户id
     *     - inOutDesc: 进门或出门
     *     - SEX: 性别
     *     - passTime: 通过时间
     *     - inOrOut: 状态（1：进门，2：出门）
     *     - cname: 人员姓名
     *     - device_type: 设备类型（5：消费，6：门禁）
     *     - mode: 模式（1：人脸，2：刷卡，3：指纹）
     *     - custno: 客户编号
     *   - pageNum: 当前页码
     *   - pageSize: 每页大小
     *   - pages: 总页数
     *   - 其他分页相关信息
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "total": 86,
     *   "list": [
     *     {
     *       "mode": 1,
     *       "custno": "88888",
     *       "inOutDesc": "进门",
     *       "SEX": 1,
     *       "passTime": "2019-05-14 16:52:15",
     *       "inOrOut": 1,
     *       "cname": "艾京",
     *       "device_type": 6,
     *       "cid": 28436
     *     }
     *   ],
     *   "pageNum": 1,
     *   "pageSize": 5
     * }
     */
    public function getDoorPass($page = 1, $limit = 20, $doorId = 0, $beginTime = '', $endTime = '')
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        // 验证页数
        if ($page < 1 || $page > 100) {
            throw new \InvalidArgumentException('页数参数错误，必须大于等于1且不能大于100');
        }

        // 验证分页条数
        if ($limit < 1 || $limit > 1000) {
            throw new \InvalidArgumentException('分页条数参数错误，必须大于等于1且不能大于1000');
        }

        // 设置默认时间
        $nowTime = time();
        $beginDefaultTime = date('Y-m-d H:i:s', ($nowTime - 10 * 60)); // 10分钟前
        $endDefaultTime = date('Y-m-d H:i:s', $nowTime); // 当前时间

        $query = [
            'sp'        => $this->sp,
            'sid'       => $this->sid,
            'page'      => $page,
            'limit'     => $limit,
            'doorid'    => $doorId,
            'beginTime' => $beginTime ?: $beginDefaultTime,
            'endTime'   => $endTime ?: $endDefaultTime,
        ];

        $url = $this->url . UriConst::GET_DOOR_PASS . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

    /**
     * 获取门禁规则信息
     * 
     * 请求方式: POST
     * 
     * @return array 返回门禁规则信息列表，每个规则包含：
     *   - id: 门禁规则id
     *   - name: 门禁规则名称
     *   - termId: 所属学校
     *   - createTime: 创建时间
     *   - doorRuleDevices: 绑定设备列表，每个设备包含：
     *     - id: 设备规则关联id
     *     - ruleId: 规则id
     *     - deviceId: 设备编号
     *     - termId: 所属学校
     *   - doorRuleTimeInfos: 规则时间信息列表，每个时间信息包含：
     *     - id: 时间规则id
     *     - ruleId: 规则id
     *     - scope: 适用范围（1,2,3等）
     *     - startTime: 开始时间（格式：HH:mm）
     *     - endTime: 结束时间（格式：HH:mm）
     *     - residentStu: 是否住校生（1：是，0：否）
     *     - dayStu: 是否走读生（1：是，0：否）
     *     - bzdStu: 是否半住读（1：是，0：否）
     *     - classIds: 绑定班级列表（逗号分隔的班级ID）
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "code": 200,
     *   "message": null,
     *   "value": [
     *     {
     *       "id": 9,
     *       "name": "进门规则",
     *       "createTime": "2019-06-03 09:41:15",
     *       "termId": 14,
     *       "doorRuleDevices": [
     *         {
     *           "id": 88,
     *           "ruleId": 9,
     *           "deviceId": "1551223476",
     *           "termId": 14
     *         }
     *       ],
     *       "doorRuleTimeInfos": [
     *         {
     *           "id": 415,
     *           "ruleId": 9,
     *           "scope": "1,2,3",
     *           "startTime": "00:00",
     *           "endTime": "21:30",
     *           "residentStu": 1,
     *           "dayStu": 1,
     *           "bzdStu": 1,
     *           "classIds": "14,155610795603218"
     *         }
     *       ]
     *     }
     *   ]
     * }
     */
    public function getDoorRules()
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid,
        ];

        $url = $this->url . UriConst::GET_DOOR_RULES . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

    /**
     * 保存或更新门禁规则信息
     * 
     * 请求方式: POST
     * 请求体: JSON格式
     * 
     * @param string $name 门禁规则名称
     * @param array $doorRuleDevices 门禁设备列表，每个设备包含：
     *   - deviceId: 门禁设备id（必填）
     * @param array $doorRuleTimeInfos 门禁规则时间列表，每个时间信息包含：
     *   - startTime: 开始时间，格式例如：14:00（必填）
     *   - endTime: 结束时间，格式例如：14:00（必填）
     *   - scope: 范围，1:周一 2:周二 3:周三 4:周四 5:周五 6:周六 7:周日 8:假期。多个用逗号分割，例如：1,2,3（必填）
     *   - residentStu: 住校生，是：1，否：0（必填）
     *   - dayStu: 走读生，是：1，否：0（必填）
     *   - bzdStu: 半住读，是：1，否：0（必填）
     *   - classIds: 班级列表，多个班级采用逗号（,）进行分割，例如：14,18,75（必填）
     * @param int|null $id 门禁规则id，存在id则会更新该id对应的门禁信息。如果id不存在则新增门禁信息（可选）
     * @param string|null $createTime 创建时间，格式：2024-01-01 00:00:00。如果不传则使用当前时间（可选）
     * @return array 返回操作结果
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "message": "request success",
     *   "code": 200,
     *   "data": null
     * }
     */
    public function saveOrUpdateDoorRule($name, $doorRuleDevices, $doorRuleTimeInfos, $id = null, $createTime = null)
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        if (empty($name)) {
            throw new \InvalidArgumentException('门禁规则名称（name）不能为空');
        }

        if (empty($doorRuleDevices) || !is_array($doorRuleDevices)) {
            throw new \InvalidArgumentException('门禁设备列表（doorRuleDevices）不能为空且必须是数组');
        }

        if (empty($doorRuleTimeInfos) || !is_array($doorRuleTimeInfos)) {
            throw new \InvalidArgumentException('门禁规则时间列表（doorRuleTimeInfos）不能为空且必须是数组');
        }

        // 验证设备列表格式
        foreach ($doorRuleDevices as $index => $device) {
            if (!isset($device['deviceId']) || empty($device['deviceId'])) {
                throw new \InvalidArgumentException("门禁设备列表第 " . ($index + 1) . " 个设备缺少 deviceId 字段");
            }
        }

        // 验证时间规则列表格式
        foreach ($doorRuleTimeInfos as $index => $timeInfo) {
            $requiredFields = ['startTime', 'endTime', 'scope', 'residentStu', 'dayStu', 'bzdStu', 'classIds'];
            foreach ($requiredFields as $field) {
                if (!isset($timeInfo[$field])) {
                    throw new \InvalidArgumentException("门禁规则时间列表第 " . ($index + 1) . " 条规则缺少 {$field} 字段");
                }
            }

            // 验证时间格式
            if (!preg_match('/^\d{2}:\d{2}$/', $timeInfo['startTime'])) {
                throw new \InvalidArgumentException("门禁规则时间列表第 " . ($index + 1) . " 条规则的开始时间格式错误，应为 HH:mm 格式，例如：14:00");
            }

            if (!preg_match('/^\d{2}:\d{2}$/', $timeInfo['endTime'])) {
                throw new \InvalidArgumentException("门禁规则时间列表第 " . ($index + 1) . " 条规则的结束时间格式错误，应为 HH:mm 格式，例如：14:00");
            }

            // 验证学生类型值
            if (!in_array($timeInfo['residentStu'], [0, 1])) {
                throw new \InvalidArgumentException("门禁规则时间列表第 " . ($index + 1) . " 条规则的住校生（residentStu）值错误，只能是 0 或 1");
            }

            if (!in_array($timeInfo['dayStu'], [0, 1])) {
                throw new \InvalidArgumentException("门禁规则时间列表第 " . ($index + 1) . " 条规则的走读生（dayStu）值错误，只能是 0 或 1");
            }

            if (!in_array($timeInfo['bzdStu'], [0, 1])) {
                throw new \InvalidArgumentException("门禁规则时间列表第 " . ($index + 1) . " 条规则的半住读（bzdStu）值错误，只能是 0 或 1");
            }
        }

        // 设置默认创建时间
        if ($createTime === null) {
            $createTime = date('Y-m-d H:i:s');
        } else {
            // 验证创建时间格式
            if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $createTime)) {
                throw new \InvalidArgumentException('创建时间格式错误，应为 yyyy-MM-dd HH:mm:ss 格式，例如：2024-01-01 00:00:00');
            }
        }

        // 构建请求数据
        $data = [
            'sp'                => $this->sp,
            'name'              => $name,
            'termId'            => $this->sid,
            'createTime'        => $createTime,
            'doorRuleDevices'   => $doorRuleDevices,
            'doorRuleTimeInfos' => $doorRuleTimeInfos,
        ];

        // 如果提供了id，则添加到数据中（用于更新）
        if ($id !== null) {
            $data['id'] = $id;
        }

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::SAVE_OR_UPDATE_DOOR_RULE . '?' . http_build_query($query);

        // POST 请求，JSON格式数据
        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = $this->parseResponse($response);

        return $ret;
    }

    /**
     * 删除门禁规则
     * 
     * 请求方式: POST
     * 请求体: JSON格式
     * 
     * @param int $id 门禁规则id（必填）
     * @return array 返回操作结果
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "message": "request success",
     *   "code": 200,
     *   "data": null
     * }
     */
    public function delDoorRule($id)
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        if (empty($id) || !is_numeric($id)) {
            throw new \InvalidArgumentException('门禁规则ID（id）不能为空且必须是数字');
        }

        // 构建请求数据
        $data = [
            'sp' => $this->sp,
            'id' => (int)$id
        ];

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::DEL_DOOR_RULE . '?' . http_build_query($query);

        // POST 请求，JSON格式数据
        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = $this->parseResponse($response);

        return $ret;
    }
}
