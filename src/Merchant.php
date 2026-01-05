<?php

namespace Sxqibo\FastYks;

/**
 * 商户信息接口
 * 
 * 获取指定学校商户信息
 * 访问控制: 10次/分
 */
final class Merchant extends RequestApi
{
    /**
     * 获取商户信息
     * 
     * 请求方式: POST
     * 访问控制: 10次/分
     * 
     * @param int $page 页数，固定每页返回20条
     * @return array 返回商户列表，每个商户包含 code（设备code）、id（商户ID号）、name（商户名称）
     * @throws \Exception
     * 
     * 返回示例:
     * [
     *   {"code":"设备code","id":"商户ID号","name":"商户名称"}
     * ]
     */
    public function getMerchants($page = 1)
    {
        // 验证必填参数
        if (empty($this->sid)) {
            throw new \InvalidArgumentException('SID（学校编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }

        $query = [
            'sp'  => $this->sp,
            'sid' => $this->sid,
            'page' => $page
        ];

        $url = $this->url . UriConst::QUERY_MERCHANT_INFO . '?' . http_build_query($query);

        $response = Http::post($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }
}

