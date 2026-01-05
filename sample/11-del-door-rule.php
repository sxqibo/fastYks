<?php

/**
 * 删除门禁规则示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * 功能：
 * - 删除指定的门禁规则
 * 
 * 注意：
 * - 此操作会永久删除门禁规则，请谨慎使用
 * - 删除前建议先获取规则信息确认
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

    // ===== 示例1：删除指定门禁规则 =====
    echo "=== 示例1：删除指定门禁规则 ===\n";
    echo "（注意：此示例为演示代码，实际使用时请根据实际情况修改参数）\n\n";
    
    $ruleId = 9; // 门禁规则ID，实际使用时应从获取门禁规则接口获取

    echo "准备删除门禁规则：\n";
    echo "  规则ID: {$ruleId}\n";
    echo "\n";

    // 注意：实际使用时，建议先注释掉下面的代码，确认参数无误后再执行
    /*
    echo "正在删除门禁规则...\n";
    $result = $client->delDoorRule($ruleId);

    if (isset($result['code']) && $result['code'] == 200) {
        echo "✅ 删除成功！\n";
        echo "返回信息: " . ($result['message'] ?? '无') . "\n";
    } else {
        echo "❌ 删除失败\n";
        print_r($result);
    }
    */
    echo "（示例代码已注释，如需测试请取消注释）\n";

    // ===== 示例2：删除前先获取规则信息确认 =====
    echo "\n=== 示例2：删除前先获取规则信息确认 ===\n";
    echo "（建议在实际删除前先查看规则信息）\n\n";

    $targetRuleId = 9; // 要删除的规则ID

    // 先获取所有规则
    echo "正在获取门禁规则列表...\n";
    $allRules = $client->getDoorRules();
    
    if (empty($allRules)) {
        echo "没有找到任何门禁规则\n";
    } else {
        // 查找要删除的规则
        $targetRule = null;
        foreach ($allRules as $rule) {
            if (isset($rule['id']) && $rule['id'] == $targetRuleId) {
                $targetRule = $rule;
                break;
            }
        }

        if ($targetRule) {
            echo "找到要删除的规则：\n";
            echo "  规则ID: " . ($targetRule['id'] ?? '无') . "\n";
            echo "  规则名称: " . ($targetRule['name'] ?? '无') . "\n";
            echo "  创建时间: " . ($targetRule['createTime'] ?? '无') . "\n";
            
            $devices = $targetRule['doorRuleDevices'] ?? [];
            echo "  绑定设备数: " . count($devices) . " 个\n";
            
            $timeInfos = $targetRule['doorRuleTimeInfos'] ?? [];
            echo "  时间规则数: " . count($timeInfos) . " 条\n";
            echo "\n";

            // 注意：实际使用时，建议先注释掉下面的代码，确认无误后再执行删除
            /*
            echo "确认删除此规则吗？\n";
            echo "（在实际应用中，这里可以添加用户确认逻辑）\n";
            
            $result2 = $client->delDoorRule($targetRuleId);
            
            if (isset($result2['code']) && $result2['code'] == 200) {
                echo "✅ 删除成功！\n";
            } else {
                echo "❌ 删除失败\n";
                print_r($result2);
            }
            */
            echo "（删除代码已注释，如需测试请取消注释）\n";
        } else {
            echo "未找到ID为 {$targetRuleId} 的门禁规则\n";
        }
    }

    // ===== 示例3：批量删除规则（注释代码） =====
    echo "\n=== 示例3：批量删除规则（注释代码） ===\n";
    echo "（需要时可以取消注释使用，请谨慎操作）\n\n";

    /*
    // 获取所有规则
    $allRules3 = $client->getDoorRules();
    
    if (empty($allRules3)) {
        echo "没有找到任何门禁规则\n";
    } else {
        // 定义要删除的规则ID列表（根据实际情况修改）
        $ruleIdsToDelete = [9, 10, 11]; // 要删除的规则ID列表
        
        echo "准备删除以下规则：\n";
        foreach ($ruleIdsToDelete as $ruleId) {
            // 查找规则信息
            foreach ($allRules3 as $rule) {
                if (isset($rule['id']) && $rule['id'] == $ruleId) {
                    echo "  - ID: {$ruleId}, 名称: " . ($rule['name'] ?? '无') . "\n";
                    break;
                }
            }
        }
        echo "\n";

        // 执行删除（建议先注释，确认无误后再执行）
        $successCount = 0;
        $failCount = 0;
        
        foreach ($ruleIdsToDelete as $ruleId) {
            try {
                echo "正在删除规则 ID: {$ruleId}...\n";
                $result = $client->delDoorRule($ruleId);
                
                if (isset($result['code']) && $result['code'] == 200) {
                    echo "  ✅ 删除成功\n";
                    $successCount++;
                } else {
                    echo "  ❌ 删除失败\n";
                    $failCount++;
                }
                
                // 避免请求过快，等待1秒
                sleep(1);
            } catch (\Exception $e) {
                echo "  ❌ 删除失败: " . $e->getMessage() . "\n";
                $failCount++;
            }
        }
        
        echo "\n删除完成：\n";
        echo "  成功: {$successCount} 个\n";
        echo "  失败: {$failCount} 个\n";
    }
    */

    // ===== 示例4：根据规则名称删除（注释代码） =====
    echo "\n=== 示例4：根据规则名称删除（注释代码） ===\n";
    echo "（需要时可以取消注释使用）\n\n";

    /*
    $targetRuleName = '测试门禁规则'; // 要删除的规则名称
    
    // 获取所有规则
    $allRules4 = $client->getDoorRules();
    
    foreach ($allRules4 as $rule) {
        if (isset($rule['name']) && $rule['name'] === $targetRuleName) {
            $ruleId = $rule['id'] ?? null;
            
            if ($ruleId) {
                echo "找到规则: {$targetRuleName}，ID: {$ruleId}\n";
                echo "准备删除...\n";
                
                $result = $client->delDoorRule($ruleId);
                
                if (isset($result['code']) && $result['code'] == 200) {
                    echo "✅ 删除成功！\n";
                } else {
                    echo "❌ 删除失败\n";
                    print_r($result);
                }
            } else {
                echo "未找到规则ID\n";
            }
            break;
        }
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
    if (strpos($e->getMessage(), '不能为空') !== false || strpos($e->getMessage(), '必须是数字') !== false) {
        echo "\n💡 提示: 请检查参数是否正确填写。\n";
        echo "必填参数：\n";
        echo "- id: 门禁规则ID（必须是数字）\n";
    }
    
    // 如果是 SID 错误
    if (strpos($e->getMessage(), 'SID') !== false) {
        echo "\n💡 提示: 请确保配置了正确的 SID（学校编号）。\n";
        echo "可以通过 Config::set(['sid' => 学校编号]) 或构造函数传入。\n";
    }
}

