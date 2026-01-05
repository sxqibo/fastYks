<?php

/**
 * 保存门禁规则信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * 功能：
 * - 保存或更新门禁规则信息
 * - 如果提供id则更新，不提供id则新增
 * 
 * 注意：
 * - 此操作会修改门禁规则，请谨慎使用
 * - 建议先在测试环境验证后再在生产环境使用
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

    // ===== 示例1：新增门禁规则 =====
    echo "=== 示例1：新增门禁规则 ===\n";
    echo "（注意：此示例为演示代码，实际使用时请根据实际情况修改参数）\n\n";
    
    // 门禁设备列表（需要先获取设备ID，这里使用示例设备ID）
    $doorRuleDevices = [
        [
            'deviceId' => '1551223476' // 设备ID，实际使用时应从获取设备信息接口获取
        ],
        // 可以添加多个设备
        // [
        //     'deviceId' => '1551904888'
        // ]
    ];

    // 门禁规则时间列表
    $doorRuleTimeInfos = [
        [
            'startTime'    => '00:00',      // 开始时间
            'endTime'      => '21:30',      // 结束时间
            'scope'        => '1,2,3,4,5',  // 适用范围：周一到周五（1:周一 2:周二 3:周三 4:周四 5:周五 6:周六 7:周日 8:假期）
            'residentStu'  => 1,            // 住校生：1-是，0-否
            'dayStu'       => 1,            // 走读生：1-是，0-否
            'bzdStu'       => 1,            // 半住读：1-是，0-否
            'classIds'     => '14,18,75'    // 班级列表，多个班级用逗号分隔
        ],
        // 可以添加多个时间规则
        // [
        //     'startTime'    => '00:00',
        //     'endTime'      => '22:00',
        //     'scope'        => '6,7',       // 周六、周日
        //     'residentStu'  => 0,
        //     'dayStu'       => 1,
        //     'bzdStu'       => 0,
        //     'classIds'     => '14,18'
        // ]
    ];

    // 门禁规则名称
    $ruleName = '测试门禁规则_' . date('YmdHis'); // 使用时间戳确保名称唯一

    echo "准备创建门禁规则：\n";
    echo "  规则名称: {$ruleName}\n";
    echo "  绑定设备数: " . count($doorRuleDevices) . " 个\n";
    echo "  时间规则数: " . count($doorRuleTimeInfos) . " 条\n";
    echo "\n";

    // 注意：实际使用时，建议先注释掉下面的代码，确认参数无误后再执行
    /*
    echo "正在保存门禁规则...\n";
    $result = $client->saveOrUpdateDoorRule(
        $ruleName,
        $doorRuleDevices,
        $doorRuleTimeInfos
        // 不传id表示新增
        // 如果需要指定创建时间，可以传入第四个参数：$createTime = '2024-01-01 00:00:00'
    );

    if (isset($result['code']) && $result['code'] == 200) {
        echo "✅ 保存成功！\n";
        echo "返回信息: " . ($result['message'] ?? '无') . "\n";
    } else {
        echo "❌ 保存失败\n";
        print_r($result);
    }
    */
    echo "（示例代码已注释，如需测试请取消注释）\n";

    // ===== 示例2：更新门禁规则 =====
    echo "\n=== 示例2：更新门禁规则 ===\n";
    echo "（需要先获取要更新的规则ID，这里使用示例规则ID）\n\n";

    $ruleId = 9; // 规则ID，实际使用时应从获取门禁规则接口获取

    // 更新后的设备列表
    $updateDevices = [
        [
            'deviceId' => '1551223476'
        ],
        [
            'deviceId' => '1551904888' // 添加新设备
        ]
    ];

    // 更新后的时间规则
    $updateTimeInfos = [
        [
            'startTime'    => '06:00',      // 修改开始时间
            'endTime'      => '22:00',      // 修改结束时间
            'scope'        => '1,2,3,4,5,6,7', // 修改为每天
            'residentStu'  => 1,
            'dayStu'       => 1,
            'bzdStu'       => 1,
            'classIds'     => '14,18,75,100' // 添加新班级
        ]
    ];

    $updateRuleName = '更新后的门禁规则';

    echo "准备更新门禁规则：\n";
    echo "  规则ID: {$ruleId}\n";
    echo "  规则名称: {$updateRuleName}\n";
    echo "  绑定设备数: " . count($updateDevices) . " 个\n";
    echo "  时间规则数: " . count($updateTimeInfos) . " 条\n";
    echo "\n";

    // 注意：实际使用时，建议先注释掉下面的代码，确认参数无误后再执行
    /*
    echo "正在更新门禁规则...\n";
    $result2 = $client->saveOrUpdateDoorRule(
        $updateRuleName,
        $updateDevices,
        $updateTimeInfos,
        $ruleId  // 传入规则ID表示更新
    );

    if (isset($result2['code']) && $result2['code'] == 200) {
        echo "✅ 更新成功！\n";
        echo "返回信息: " . ($result2['message'] ?? '无') . "\n";
    } else {
        echo "❌ 更新失败\n";
        print_r($result2);
    }
    */
    echo "（示例代码已注释，如需测试请取消注释）\n";

    // ===== 示例3：完整的创建流程 =====
    echo "\n=== 示例3：完整的创建流程（注释代码） ===\n";
    echo "（包含获取设备、创建规则、验证结果的完整流程）\n\n";

    /*
    // 步骤1：获取可用设备
    $deviceClient = new \Sxqibo\FastYks\Device();
    $allDevices = $deviceClient->getDevices(6); // 6 表示门禁设备
    
    if (empty($allDevices)) {
        echo "没有可用的门禁设备\n";
        exit;
    }
    
    // 步骤2：获取未绑定设备（可选）
    $notBindingDevices = $deviceClient->getNotBindingDevice();
    echo "未绑定设备数: " . count($notBindingDevices) . " 个\n";
    
    // 步骤3：准备设备列表（使用前3个设备作为示例）
    $devices = [];
    $deviceCount = min(3, count($allDevices));
    for ($i = 0; $i < $deviceCount; $i++) {
        $devices[] = [
            'deviceId' => $allDevices[$i]['id'] ?? ''
        ];
    }
    
    // 步骤4：准备时间规则
    $timeInfos = [
        [
            'startTime'    => '07:00',
            'endTime'      => '20:00',
            'scope'        => '1,2,3,4,5',
            'residentStu'  => 1,
            'dayStu'       => 1,
            'bzdStu'       => 0,
            'classIds'     => '14' // 需要根据实际情况获取班级ID
        ]
    ];
    
    // 步骤5：创建规则
    $ruleName = '自动创建规则_' . date('YmdHis');
    $result = $client->saveOrUpdateDoorRule($ruleName, $devices, $timeInfos);
    
    if (isset($result['code']) && $result['code'] == 200) {
        echo "✅ 规则创建成功！\n";
        
        // 步骤6：验证规则（获取所有规则，查找刚创建的规则）
        $allRules = $client->getDoorRules();
        foreach ($allRules as $rule) {
            if (isset($rule['name']) && $rule['name'] === $ruleName) {
                echo "找到新创建的规则，ID: " . ($rule['id'] ?? '无') . "\n";
                break;
            }
        }
    } else {
        echo "❌ 规则创建失败\n";
        print_r($result);
    }
    */

    // 结果示例：
    // {
    //   "message": "request success",
    //   "code": 200,
    //   "data": null
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
    
    // 如果是参数错误
    if (strpos($e->getMessage(), '不能为空') !== false || strpos($e->getMessage(), '格式错误') !== false) {
        echo "\n💡 提示: 请检查参数是否正确填写。\n";
        echo "必填参数：\n";
        echo "- name: 门禁规则名称\n";
        echo "- doorRuleDevices: 门禁设备列表（数组，每个设备包含 deviceId）\n";
        echo "- doorRuleTimeInfos: 门禁规则时间列表（数组，包含完整的时间规则信息）\n";
    }
    
    // 如果是 SID 错误
    if (strpos($e->getMessage(), 'SID') !== false) {
        echo "\n💡 提示: 请确保配置了正确的 SID（学校编号）。\n";
        echo "可以通过 Config::set(['sid' => 学校编号]) 或构造函数传入。\n";
    }
}

