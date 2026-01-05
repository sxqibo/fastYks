<?php

namespace Sxqibo\FastYks;

/**
 * 设备信息接口
 * 
 * 提供设备相关的接口功能：
 * - 获取设备信息
 * - 获取未绑定设备信息
 * 
 * 访问控制: 10次/分（获取设备信息接口）
 */
final class Device extends RequestApi
{
    /**
     * 获取设备信息
     * 
     * 请求方式: POST
     * 访问控制: 10次/分
     * 
     * @param int $type 设备类型，5:消费，6：门禁，默认为6（门禁）
     * @return array 返回设备列表，每个设备包含：
     *   - id: 设备id
     *   - DEVICE_NAME: 设备名称
     *   - DEVICE_TYPE: 设备类型
     *   - end_user_id: 所属学校
     *   - createDate: 创建时间
     *   - merchId: 所属商户ID
     *   - merchName: 所属商户名称
     *   - funcs: 支持功能(1:人脸，2：刷卡，3：指纹)
     *   - lastRunTime: 最后联机时间
     *   - lastRunTimeT8: 最后联机时间
     * @throws \Exception
     * 
     * 返回示例:
     * [
     *   {
     *     "DEVICE_TYPE": 6,
     *     "end_user_id": 14,
     *     "funcs": "1",
     *     "DEVICE_NAME": "AJMJ2",
     *     "id": "1557134696",
     *     "createDate": "2019-04-26 15:19:57"
     *   }
     * ]
     */
    public function getDevices($type = 6)
    {
        // 验证设备类型
        if (!in_array($type, [5, 6])) {
            throw new \InvalidArgumentException('设备类型参数错误，只能是 5（消费）或 6（门禁）');
        }

        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        $query = [
            'type' => $type,
            'sp'   => $this->sp,
            'sid'  => $this->sid
        ];

        $url = $this->url . UriConst::GET_DEVICES . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

    /**
     * 获取未绑定设备信息
     * 
     * 请求方式: POST
     * 
     * @return array 返回未绑定设备列表，每个设备包含：
     *   - deviceId: 设备id
     *   - deviceName: 设备名称
     *   - deviceIp: 设备ip
     *   - deviceSn: 设备sn号
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "code": 200,
     *   "message": null,
     *   "value": [
     *     {
     *       "deviceId": "1550861629",
     *       "deviceName": "门禁机测试",
     *       "deviceIp": "192.168.2.111",
     *       "deviceSn": "201900004271"
     *     }
     *   ]
     * }
     */
    public function getNotBindingDevice()
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::GET_NOT_BINDING_DEVICE . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }

    /**
     * 49. 设备信息维护
     *
     * @return array
     */
    public function handleDevice($data)
    {
        // $data = [
        //     'operType'     => $this->request->post('oper_type'),
        //     'deviceCode'   => $this->request->post('device_code'),
        //     'deviceName'   => $this->request->post('device_name'),
        //     'deviceIp'     => $this->request->post('device_ip'),
        //     'deviceType'   => $this->request->post('device_type'),
        //     'deviceAdress' => $this->request->post('device_address'),
        //     'linkType'     => $this->request->post('link_type'),
        //     'yktNo'        => $this->request->post('ykt_no'),
        //     'mac'          => $this->request->post('mac'),
        //     'groupNum'     => $this->request->post('group_num'),
        //     'organIds'     => $this->request->post('organ_ids'),
        //     'merchantId'   => $this->request->post('merchant_id')
        // ];

        $data['sign'] = YskUtil::generateSignature($data, $this->chargeSecretKey);

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid
        ];

        $url = $this->url . UriConst::HANDLE_DEVICE . '?' . http_build_query($query);

        $response = Http::post($url, json_encode($data, JSON_UNESCAPED_UNICODE), [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ]
        ]);

        $ret = json_decode($response, true);

        if(!$ret){
            exception('设备维护异常');
        }

        return $ret['value'] ?? [];
    }
}
