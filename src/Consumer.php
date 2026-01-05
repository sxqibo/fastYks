<?php

namespace Sxqibo\FastYks;

/**
 * 消费流水信息接口
 * 
 * 获取指定学校（校区）的消费流水信息
 * 访问控制: 10次/分
 * 
 * 注意：
 * - page不能大于100，limit不能大于1000
 * - 交易高峰期接口不再返回数据，如果对数据实时性要求很高的厂家，建议对接流水推送接口
 * - 2022年3月后纠错流水平台会生产一条金额为负的流水通过本接口返回，trade_no号为原纠错流水的out_trade_no
 */
final class Consumer extends RequestApi
{
    /**
     * 获取消费流水信息
     * 
     * 请求方式: POST
     * 访问控制: 10次/分
     * 
     * @param int $page 页数，页数从1页开始，不能大于100
     * @param int $limit 分页返回条数，不能大于1000
     * @param string $beginTime 流水开始时间（平台收单时间而不是用户实际刷卡时间），格式采用yyyy-MM-dd HH:mm:ss
     * @param string $endTime 流水结束时间（平台收单时间而不是用户实际刷卡时间），格式采用yyyy-MM-dd HH:mm:ss
     * @param int|null $cid 用户ID（选填）
     * @param string|null $idcard 用户身份证号码（选填）
     * @return array 返回消费流水信息，包含：
     *   - total: 总记录数
     *   - list: 流水列表，每个流水包含：
     *     - gmt_create: 交易创建时间
     *     - device_name: 设备名称
     *     - gmt_payment: 支付时间
     *     - id_card: 消费人员id
     *     - out_trade_no: 交易编号
     *     - total_amount: 总金额
     *     - device_code: 设备编号
     *     - trade_no: 外部订单交易号
     *     - buyer_pay_amount: 支付金额
     *     - tradeTime: 刷卡时间
     *     - tradeType: 交易类型（支付宝刷脸、补助消费、支付宝联机刷脸、联机刷卡、刷卡消费、刷脸消费、计次消费、微信刷卡代扣、微信刷脸代扣、订餐消费、在线点餐消费、外卖消费）
     *     - endSubBalance: 消费后补助
     *     - endCashMoney: 消费后金额
     *   - pageNum: 当前页码
     *   - pageSize: 每页大小
     *   - pages: 总页数
     *   - 其他分页相关信息
     * @throws \Exception
     * 
     * 返回示例:
     * {
     *   "code": 200,
     *   "message": null,
     *   "value": {
     *     "total": 3361,
     *     "list": [
     *       {
     *         "gmt_create": "2019-07-04T01:10:54.000+0000",
     *         "device_name": "测试人脸1号",
     *         "gmt_payment": "2019-07-04T01:11:30.000+0000",
     *         "out_trade_no": "201907040911299939926442271C0",
     *         "total_amount": 0.01,
     *         "device_code": "1550016555",
     *         "id_card": "18",
     *         "trade_no": "201907040911299942162760765",
     *         "buyer_pay_amount": 0.01,
     *         "tradeType": "补助",
     *         "endSubBalance": 0.01,
     *         "tradeTime": "2020-04-07 10:05:03",
     *         "endCashMoney": 2.00
     *       }
     *     ],
     *     "pageNum": 1,
     *     "pageSize": 1,
     *     "pages": 3361
     *   }
     * }
     */
    public function getConsumeBySchool($page = 1, $limit = 100, $beginTime = '', $endTime = '', $cid = null, $idcard = null)
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

        // 验证时间参数
        if (empty($beginTime)) {
            throw new \InvalidArgumentException('开始时间（beginTime）不能为空，格式：yyyy-MM-dd HH:mm:ss');
        }

        if (empty($endTime)) {
            throw new \InvalidArgumentException('结束时间（endTime）不能为空，格式：yyyy-MM-dd HH:mm:ss');
        }

        // 验证时间格式
        $beginTimestamp = strtotime($beginTime);
        $endTimestamp = strtotime($endTime);
        if ($beginTimestamp === false) {
            throw new \InvalidArgumentException('开始时间格式错误，请使用格式：yyyy-MM-dd HH:mm:ss');
        }
        if ($endTimestamp === false) {
            throw new \InvalidArgumentException('结束时间格式错误，请使用格式：yyyy-MM-dd HH:mm:ss');
        }
        if ($beginTimestamp > $endTimestamp) {
            throw new \InvalidArgumentException('开始时间不能大于结束时间');
        }

        // 构建请求参数
        $query = [
            'sp'        => $this->sp,
            'sid'       => $this->sid,
            'page'      => $page,
            'limit'     => $limit,
            'beginTime' => $beginTime,
            'endTime'   => $endTime,
        ];

        // 添加可选参数
        if ($cid !== null) {
            $query['cid'] = $cid;
        }

        if ($idcard !== null && !empty($idcard)) {
            $query['idcard'] = $idcard;
        }

        $url = $this->url . UriConst::GET_CONSUME_BY_SCHOOL . '?' . http_build_query($query);

        // POST 请求，参数在 URL 查询字符串中（与 Door 接口保持一致）
        $response = Http::post($url);

        $ret = $this->parseResponse($response);

        return $ret['value'] ?? [];
    }
}

