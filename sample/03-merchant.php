<?php

/**
 * 获取商户信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * 注意：访问控制为 10次/分，请控制调用频率
 */

// 自动加载：优先使用 composer，如果没有则使用简单的 bootstrap
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/bootstrap.php';
}

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Merchant;

// 方式1：从配置文件加载（推荐）
Config::loadFromFile(__DIR__ . '/config.php');

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number', 'sid' => 14]);

// 方式3：使用默认配置（代码中已内置默认值）

try {
    // 创建 Merchant 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Merchant();
    
    // 方式B：手动传入 SP 号和 SID
    // $client = new Merchant('your_sp_number_here', 14);

    // 获取第一页商户信息（每页固定返回20条）
    echo "=== 获取商户信息 ===\n";
    echo "正在获取第 1 页商户信息...\n";
    $result = $client->getMerchants(1);
    
    if (empty($result)) {
        echo "该学校下没有商户信息\n";
    } else {
        echo "获取成功！找到 " . count($result) . " 个商户：\n";
        foreach ($result as $merchant) {
            $code = $merchant['code'] ?? '无';
            $id = $merchant['id'] ?? '无';
            $name = $merchant['name'] ?? '无';
            echo "  - {$name} (ID: {$id}, Code: {$code})\n";
        }
    }

    // 如果需要获取更多页的数据，可以循环调用
    // 注意：访问控制为 10次/分，请控制调用频率
    /*
    echo "\n=== 获取所有商户信息 ===\n";
    $page = 1;
    $allMerchants = [];
    do {
        echo "正在获取第 {$page} 页...\n";
        $merchants = $client->getMerchants($page);
        if (!empty($merchants)) {
            $allMerchants = array_merge($allMerchants, $merchants);
            // 如果返回的数据少于20条，说明已经是最后一页
            if (count($merchants) < 20) {
                break;
            }
            $page++;
            // 控制调用频率，避免超过 10次/分的限制
            sleep(7); // 等待7秒，确保不超过频率限制
        } else {
            break;
        }
    } while (true);
    
    echo "总共找到 " . count($allMerchants) . " 个商户\n";
    */

    // 结果示例：
    // [
    //   {"code":"设备code","id":"商户ID号","name":"商户名称"}
    // ]

} catch (\Exception $e) {
    echo "\n❌ 错误: " . $e->getMessage() . "\n";
    
    // 如果是 403 错误，给出更详细的提示
    if (strpos($e->getMessage(), '403') !== false || strpos($e->getMessage(), '没有找到sp信息') !== false) {
        echo "\n💡 提示: 403 错误通常是因为 SP 号无效或未配置。\n";
        echo "解决方法：\n";
        echo "1. 确保你使用的是有效的 SP 号（请联系易科士对接人获取）\n";
        echo "2. 检查 sample/config.php 中的 'sp' 配置是否正确\n";
        echo "3. 或者直接在代码中传入有效的 SP 号\n";
    }
}

