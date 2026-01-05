<?php

/**
 * 获取关联组织（校区）信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号（服务商编号）
 * 3. 运行此示例
 */

// 自动加载：优先使用 composer，如果没有则使用简单的 bootstrap
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/bootstrap.php';
}

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Campus;

// 方式1：从环境变量加载配置（推荐）
// 注意：需要先创建 .env 文件：cp .env.example .env，然后编辑 .env 填入实际配置
Config::loadFromEnv(dirname(__DIR__));

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number_here']);

// 方式3：从文件加载配置（兼容旧方式）
// Config::loadFromFile(__DIR__ . '/config.php');

try {
    // 创建 Campus 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Campus();
    
    // 方式B：手动传入 SP 号
    // $client = new Campus('your_sp_number_here');
    
    // 注意：获取校区信息只需要 SP 号，不需要 SID

    // 调试信息：显示当前使用的配置
    $currentConfig = Config::get();
    echo "=== 当前配置信息 ===\n";
    echo "SP: " . ($currentConfig['sp'] ?? '未设置') . "\n";
    echo "URL: " . ($currentConfig['url'] ?? '未设置') . "\n";
    echo "==================\n\n";

    // 检查 SP 号是否有效
    if (empty($currentConfig['sp']) || $currentConfig['sp'] === 'your_sp_number_here') {
        echo "⚠️  警告: 请先配置有效的 SP 号！\n";
        echo "修改方法：\n";
        echo "1. 复制 .env.example 为 .env：cp .env.example .env\n";
        echo "2. 编辑 .env 文件，将 YKS_SP 值改为你的实际 SP 号\n";
        echo "3. 或者在代码中直接传入: \$client = new Campus('your_real_sp_number');\n\n";
    }

    // 获取关联组织（校区）信息
    echo "正在请求接口...\n";
    $result = $client->getCampus();

    echo "\n✅ 获取成功！\n";
    echo "组织列表：\n";
    print_r($result);

    // 结果示例：
    // [
    //   {"teamName":"测试学校","teamId":14},
    //   {"teamName":"重庆市第三十七中学校","teamId":29}
    // ]

} catch (\Exception $e) {
    echo "\n❌ 错误: " . $e->getMessage() . "\n";
    
    // 如果是 403 错误，给出更详细的提示
    if (strpos($e->getMessage(), '403') !== false || strpos($e->getMessage(), '没有找到sp信息') !== false) {
        echo "\n💡 提示: 403 错误通常是因为 SP 号无效或未配置。\n";
        echo "解决方法：\n";
        echo "1. 确保你使用的是有效的 SP 号（请联系易科士对接人获取）\n";
        echo "2. 检查 .env 文件中的 YKS_SP 配置是否正确\n";
        echo "3. 或者直接在代码中传入有效的 SP 号\n";
    }
}

