<?php

/**
 * 获取设备信息示例
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
use Sxqibo\FastYks\Device;

// 方式1：从配置文件加载（推荐）
Config::loadFromFile(__DIR__ . '/config.php');

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number', 'sid' => 14]);

// 方式3：使用默认配置（代码中已内置默认值）

try {
    // 创建 Device 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Device();
    
    // 方式B：手动传入 SP 号和 SID
    // $client = new Device('your_sp_number_here', 14);

    // 获取门禁设备信息（设备类型：6）
    echo "=== 获取门禁设备信息 ===\n";
    echo "正在获取门禁设备信息（类型：6）...\n";
    $devices = $client->getDevices(6);
    
    if (empty($devices)) {
        echo "该学校下没有门禁设备信息\n";
    } else {
        echo "获取成功！找到 " . count($devices) . " 个门禁设备：\n";
        foreach ($devices as $device) {
            $id = $device['id'] ?? '无';
            $name = $device['DEVICE_NAME'] ?? '无';
            $type = $device['DEVICE_TYPE'] ?? '无';
            $merchName = $device['merchName'] ?? '无';
            $funcs = $device['funcs'] ?? '无';
            $createDate = $device['createDate'] ?? '无';
            $lastRunTime = $device['lastRunTime'] ?? ($device['lastRunTimeT8'] ?? '无');
            
            // 解析支持的功能
            $funcNames = [];
            if (strpos($funcs, '1') !== false) $funcNames[] = '人脸';
            if (strpos($funcs, '2') !== false) $funcNames[] = '刷卡';
            if (strpos($funcs, '3') !== false) $funcNames[] = '指纹';
            $funcStr = !empty($funcNames) ? implode('、', $funcNames) : '无';
            
            echo "  - {$name} (ID: {$id})\n";
            echo "    类型: " . ($type == 5 ? '消费' : '门禁') . "\n";
            echo "    商户: {$merchName}\n";
            echo "    支持功能: {$funcStr}\n";
            echo "    创建时间: {$createDate}\n";
            echo "    最后联机时间: {$lastRunTime}\n";
            echo "\n";
        }
    }

    // 获取消费设备信息（设备类型：5）
    echo "\n=== 获取消费设备信息 ===\n";
    echo "正在获取消费设备信息（类型：5）...\n";
    $consumeDevices = $client->getDevices(5);
    
    if (empty($consumeDevices)) {
        echo "该学校下没有消费设备信息\n";
    } else {
        echo "获取成功！找到 " . count($consumeDevices) . " 个消费设备：\n";
        foreach ($consumeDevices as $device) {
            $id = $device['id'] ?? '无';
            $name = $device['DEVICE_NAME'] ?? '无';
            $merchName = $device['merchName'] ?? '无';
            echo "  - {$name} (ID: {$id}, 商户: {$merchName})\n";
        }
    }

    // 结果示例：
    // [
    //   {
    //     "DEVICE_TYPE": 6,
    //     "end_user_id": 14,
    //     "funcs": "1",
    //     "DEVICE_NAME": "AJMJ2",
    //     "id": "1557134696",
    //     "createDate": "2019-04-26 15:19:57",
    //     "merchId": "商户ID",
    //     "merchName": "商户名称",
    //     "lastRunTime": "最后联机时间",
    //     "lastRunTimeT8": "最后联机时间"
    //   }
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
    
    // 如果是参数错误
    if (strpos($e->getMessage(), '设备类型参数错误') !== false) {
        echo "\n💡 提示: 设备类型只能是 5（消费）或 6（门禁）。\n";
    }
    
    // 如果是 SID 错误
    if (strpos($e->getMessage(), 'SID') !== false) {
        echo "\n💡 提示: 请确保配置了正确的 SID（学校编号）。\n";
        echo "可以通过 Config::set(['sid' => 学校编号]) 或构造函数传入。\n";
    }
}

