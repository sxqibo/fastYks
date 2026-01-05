<?php

namespace Sxqibo\FastYks;

/**
 * 请求 API 基类
 */
class RequestApi
{
    /**
     * API 基础 URL
     * @var string
     */
    protected $url;

    /**
     * 服务商编号
     * @var string
     */
    protected $sp;

    /**
     * 学校编号 ID
     * @var int
     */
    protected $sid;

    /**
     * 充值密钥（特殊权限需要）
     * @var string
     */
    protected $chargeSecretKey = '';

    /**
     * 构造函数
     *
     * @param string|null $sp 服务商编号（可选，不传则使用配置中的默认值）
     * @param int|null $sid 学校编号 ID（可选，某些接口不需要）
     * @param string|null $url 自定义 URL（可选，默认根据 sid 自动判断）
     */
    public function __construct($sp = null, $sid = null, $url = null)
    {
        // 如果未传入 sp，尝试从配置中获取
        $this->sp = $sp ?? Config::get('sp');
        
        // 验证 SP 号是否有效
        if (empty($this->sp)) {
            throw new \InvalidArgumentException('SP 号（服务商编号）不能为空，请在构造函数中传入或通过 Config::set() 设置');
        }
        
        // 如果未传入 sid，尝试从配置中获取
        $this->sid = $sid ?? Config::get('sid');

        // 如果提供了自定义 URL，使用自定义 URL
        if ($url !== null) {
            $this->url = rtrim($url, '/');
        } else {
            // 根据 sid 判断使用哪个 URL
            // sid <= 5000 使用 https://face.cdyqsh.com/
            // sid > 5000 使用 https://twface.cdyqsh.com/
            if ($this->sid !== null && $this->sid > 5000) {
                $this->url = 'https://twface.cdyqsh.com';
            } else {
                // 尝试从配置中获取 URL，如果没有则使用默认值
                $this->url = Config::get('url') ?: 'https://face.cdyqsh.com';
            }
        }

        // 从配置中加载充值密钥
        $chargeSecretKey = Config::get('charge_secret_key');
        if ($chargeSecretKey) {
            $this->chargeSecretKey = $chargeSecretKey;
        }
    }

    /**
     * 设置充值密钥
     *
     * @param string $secretKey
     * @return $this
     */
    public function setChargeSecretKey($secretKey)
    {
        $this->chargeSecretKey = $secretKey;
        return $this;
    }

    /**
     * 解析响应结果
     *
     * @param string $response
     * @return array
     * @throws \Exception
     */
    protected function parseResponse($response)
    {
        $result = json_decode($response, true);

        if ($result === null) {
            throw new \Exception('响应解析失败: ' . $response);
        }

        // 检查返回码
        if (isset($result['code']) && $result['code'] !== 200) {
            $message = $result['message'] ?? '未知错误';
            throw new \Exception("接口调用失败: [{$result['code']}] {$message}");
        }

        return $result;
    }
}
