<?php

namespace Sxqibo\FastYks;

/**
 * 校区（组织）信息接口
 * 
 * 获取关联组织（校区）信息-V1.6
 * 一个 SP 可以对应多个组织机构
 */
final class Campus extends RequestApi
{
    /**
     * 获取关联组织（校区）信息
     * 
     * 请求方式: GET/POST
     * 访问控制: 无限制
     * 
     * @return array 返回组织列表，每个组织包含 teamName（学校名称）和 teamId（学校编码）
     * @throws \Exception
     * 
     * 返回示例:
     * [
     *   {"teamName":"测试学校","teamId":14},
     *   {"teamName":"重庆市第三十七中学校","teamId":29}
     * ]
     */
    public function getCampus()
    {
        $query = [
            'sp' => $this->sp
        ];

        $url = $this->url . UriConst::GET_CAMPUS . '?' . http_build_query($query);

        // 支持 GET 和 POST，这里使用 GET
        $response = Http::get($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }
}

