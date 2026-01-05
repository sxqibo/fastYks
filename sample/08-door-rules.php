<?php

/**
 * 获取门禁规则信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * 功能：
 * - 获取指定学校（校区）的门禁规则信息
 * - 包括规则基本信息、绑定设备、时间规则等
 */

// 自动加载：优先使用 composer，如果没有则使用简单的 bootstrap
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/bootstrap.php';
}

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Door;

// 方式1：从配置文件加载（推荐）
Config::loadFromFile(__DIR__ . '/config.php');

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number', 'sid' => 14]);

try {
    // 创建 Door 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Door();
    
    // 方式B：手动传入 SP 号和 SID
    // $client = new Door('your_sp_number_here', 14);

    // ===== 示例1：获取所有门禁规则信息 =====
    echo "=== 示例1：获取所有门禁规则信息 ===\n";
    echo "正在获取门禁规则信息...\n";
    
    $rules = $client->getDoorRules();
    
    if (empty($rules)) {
        echo "该学校没有配置门禁规则\n";
    } else {
        echo "获取成功！共有 " . count($rules) . " 条门禁规则：\n\n";
        foreach ($rules as $index => $rule) {
            $ruleId = $rule['id'] ?? '无';
            $ruleName = $rule['name'] ?? '无';
            $createTime = $rule['createTime'] ?? '无';
            $termId = $rule['termId'] ?? '无';
            
            echo "规则 " . ($index + 1) . "：\n";
            echo "  ID: {$ruleId}\n";
            echo "  名称: {$ruleName}\n";
            echo "  创建时间: {$createTime}\n";
            echo "  所属学校: {$termId}\n";
            
            // 显示绑定的设备
            $devices = $rule['doorRuleDevices'] ?? [];
            if (!empty($devices)) {
                echo "  绑定设备 (" . count($devices) . " 个):\n";
                foreach ($devices as $device) {
                    $deviceId = $device['deviceId'] ?? '无';
                    echo "    - 设备编号: {$deviceId}\n";
                }
            } else {
                echo "  绑定设备: 无\n";
            }
            
            // 显示时间规则
            $timeInfos = $rule['doorRuleTimeInfos'] ?? [];
            if (!empty($timeInfos)) {
                echo "  时间规则 (" . count($timeInfos) . " 条):\n";
                foreach ($timeInfos as $timeInfo) {
                    $startTime = $timeInfo['startTime'] ?? '无';
                    $endTime = $timeInfo['endTime'] ?? '无';
                    $scope = $timeInfo['scope'] ?? '无';
                    $residentStu = isset($timeInfo['residentStu']) ? ($timeInfo['residentStu'] == 1 ? '是' : '否') : '未知';
                    $dayStu = isset($timeInfo['dayStu']) ? ($timeInfo['dayStu'] == 1 ? '是' : '否') : '未知';
                    $bzdStu = isset($timeInfo['bzdStu']) ? ($timeInfo['bzdStu'] == 1 ? '是' : '否') : '未知';
                    $classIds = $timeInfo['classIds'] ?? '无';
                    
                    echo "    - 时间: {$startTime} ~ {$endTime}\n";
                    echo "      适用范围: {$scope}\n";
                    echo "      住校生: {$residentStu} | 走读生: {$dayStu} | 半住读: {$bzdStu}\n";
                    if ($classIds !== '无' && !empty($classIds)) {
                        $classArray = explode(',', $classIds);
                        echo "      绑定班级: " . count($classArray) . " 个班级 (ID: {$classIds})\n";
                    }
                }
            } else {
                echo "  时间规则: 无\n";
            }
            
            echo "\n";
        }
    }

    // ===== 示例2：统计门禁规则信息 =====
    echo "\n=== 示例2：统计门禁规则信息 ===\n";
    $rules2 = $client->getDoorRules();
    
    if (!empty($rules2)) {
        $totalDevices = 0;
        $totalTimeRules = 0;
        $totalClasses = 0;
        
        foreach ($rules2 as $rule) {
            $devices = $rule['doorRuleDevices'] ?? [];
            $timeInfos = $rule['doorRuleTimeInfos'] ?? [];
            
            $totalDevices += count($devices);
            $totalTimeRules += count($timeInfos);
            
            // 统计绑定的班级数量（去重）
            $allClassIds = [];
            foreach ($timeInfos as $timeInfo) {
                $classIds = $timeInfo['classIds'] ?? '';
                if (!empty($classIds)) {
                    $classArray = explode(',', $classIds);
                    $allClassIds = array_merge($allClassIds, $classArray);
                }
            }
            $totalClasses += count(array_unique($allClassIds));
        }
        
        echo "统计结果：\n";
        echo "  门禁规则总数: " . count($rules2) . " 条\n";
        echo "  绑定设备总数: {$totalDevices} 个\n";
        echo "  时间规则总数: {$totalTimeRules} 条\n";
        echo "  绑定班级总数: {$totalClasses} 个（去重后）\n";
    }

    // ===== 示例3：查找特定规则 =====
    echo "\n=== 示例3：查找特定规则（示例代码） ===\n";
    echo "（需要时可以取消注释使用）\n";
    
    /*
    $rules3 = $client->getDoorRules();
    $targetRuleName = '进门规则'; // 要查找的规则名称
    
    foreach ($rules3 as $rule) {
        if (isset($rule['name']) && $rule['name'] === $targetRuleName) {
            echo "找到规则: {$targetRuleName}\n";
            echo "规则ID: " . ($rule['id'] ?? '无') . "\n";
            echo "创建时间: " . ($rule['createTime'] ?? '无') . "\n";
            break;
        }
    }
    */

    // 结果示例：
    // {
    //   "code": 200,
    //   "message": null,
    //   "value": [
    //     {
    //       "id": 9,
    //       "name": "进门规则",
    //       "createTime": "2019-06-03 09:41:15",
    //       "termId": 14,
    //       "doorRuleDevices": [
    //         {
    //           "id": 88,
    //           "ruleId": 9,
    //           "deviceId": "1551223476",
    //           "termId": 14
    //         }
    //       ],
    //       "doorRuleTimeInfos": [
    //         {
    //           "id": 415,
    //           "ruleId": 9,
    //           "scope": "1,2,3",
    //           "startTime": "00:00",
    //           "endTime": "21:30",
    //           "residentStu": 1,
    //           "dayStu": 1,
    //           "bzdStu": 1,
    //           "classIds": "14,155610795603218"
    //         }
    //       ]
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

