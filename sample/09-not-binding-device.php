<?php

/**
 * 获取未绑定设备信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * 功能：
 * - 获取指定学校（校区）的未绑定设备信息
 * - 未绑定设备是指尚未绑定到门禁规则的设备
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

try {
    // 创建 Device 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Device();
    
    // 方式B：手动传入 SP 号和 SID
    // $client = new Device('your_sp_number_here', 14);

    // ===== 示例1：获取所有未绑定设备 =====
    echo "=== 示例1：获取所有未绑定设备 ===\n";
    echo "正在获取未绑定设备信息...\n";
    
    $devices = $client->getNotBindingDevice();
    
    if (empty($devices)) {
        echo "该学校下没有未绑定设备\n";
        echo "（所有设备都已绑定到门禁规则）\n";
    } else {
        echo "获取成功！找到 " . count($devices) . " 个未绑定设备：\n\n";
        foreach ($devices as $index => $device) {
            $deviceId = $device['deviceId'] ?? '无';
            $deviceName = $device['deviceName'] ?? '无';
            $deviceIp = $device['deviceIp'] ?? '无';
            $deviceSn = $device['deviceSn'] ?? '无';
            
            echo "设备 " . ($index + 1) . "：\n";
            echo "  设备ID: {$deviceId}\n";
            echo "  设备名称: {$deviceName}\n";
            echo "  设备IP: {$deviceIp}\n";
            echo "  设备SN: {$deviceSn}\n";
            echo "\n";
        }
    }

    // ===== 示例2：统计未绑定设备信息 =====
    echo "\n=== 示例2：统计未绑定设备信息 ===\n";
    $devices2 = $client->getNotBindingDevice();
    
    if (!empty($devices2)) {
        echo "统计结果：\n";
        echo "  未绑定设备总数: " . count($devices2) . " 个\n";
        
        // 统计IP地址段
        $ipSegments = [];
        foreach ($devices2 as $device) {
            $ip = $device['deviceIp'] ?? '';
            if (!empty($ip)) {
                $parts = explode('.', $ip);
                if (count($parts) >= 3) {
                    $segment = $parts[0] . '.' . $parts[1] . '.' . $parts[2] . '.x';
                    if (!isset($ipSegments[$segment])) {
                        $ipSegments[$segment] = 0;
                    }
                    $ipSegments[$segment]++;
                }
            }
        }
        
        if (!empty($ipSegments)) {
            echo "  IP地址段分布：\n";
            foreach ($ipSegments as $segment => $count) {
                echo "    {$segment}: {$count} 个设备\n";
            }
        }
    } else {
        echo "没有未绑定设备，无需统计\n";
    }

    // ===== 示例3：查找特定设备 =====
    echo "\n=== 示例3：查找特定设备（示例代码） ===\n";
    echo "（需要时可以取消注释使用）\n";
    
    /*
    $devices3 = $client->getNotBindingDevice();
    $targetDeviceName = '门禁机测试'; // 要查找的设备名称
    
    foreach ($devices3 as $device) {
        if (isset($device['deviceName']) && $device['deviceName'] === $targetDeviceName) {
            echo "找到设备: {$targetDeviceName}\n";
            echo "设备ID: " . ($device['deviceId'] ?? '无') . "\n";
            echo "设备IP: " . ($device['deviceIp'] ?? '无') . "\n";
            echo "设备SN: " . ($device['deviceSn'] ?? '无') . "\n";
            break;
        }
    }
    */

    // ===== 示例4：对比已绑定和未绑定设备 =====
    echo "\n=== 示例4：对比已绑定和未绑定设备 ===\n";
    echo "（需要时可以取消注释使用）\n";
    
    /*
    // 获取所有设备
    $allDevices = $client->getDevices(6); // 6 表示门禁设备
    $allDeviceIds = [];
    foreach ($allDevices as $device) {
        $allDeviceIds[] = $device['id'] ?? '';
    }
    
    // 获取未绑定设备
    $notBindingDevices = $client->getNotBindingDevice();
    $notBindingDeviceIds = [];
    foreach ($notBindingDevices as $device) {
        $notBindingDeviceIds[] = $device['deviceId'] ?? '';
    }
    
    // 计算已绑定设备
    $bindingDeviceIds = array_diff($allDeviceIds, $notBindingDeviceIds);
    
    echo "设备统计：\n";
    echo "  总设备数: " . count($allDeviceIds) . " 个\n";
    echo "  已绑定设备: " . count($bindingDeviceIds) . " 个\n";
    echo "  未绑定设备: " . count($notBindingDeviceIds) . " 个\n";
    */

    // 结果示例：
    // {
    //   "code": 200,
    //   "message": null,
    //   "value": [
    //     {
    //       "deviceId": "1550861629",
    //       "deviceName": "门禁机测试",
    //       "deviceIp": "192.168.2.111",
    //       "deviceSn": "201900004271"
    //     }
    //   ]
    // }

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
    
    // 如果是 SID 错误
    if (strpos($e->getMessage(), 'SID') !== false) {
        echo "\n💡 提示: 请确保配置了正确的 SID（学校编号）。\n";
        echo "可以通过 Config::set(['sid' => 学校编号]) 或构造函数传入。\n";
    }
}

