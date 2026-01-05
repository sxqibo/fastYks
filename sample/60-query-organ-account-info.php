<?php

/**
 * 获取部门人员信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * 访问频率限制：30次/分钟
 */

// 自动加载：优先使用 composer，如果没有则使用简单的 bootstrap
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/bootstrap.php';
}

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Person;
use Sxqibo\FastYks\Department;

// 方式1：从环境变量加载配置（推荐）
Config::loadFromEnv(dirname(__DIR__));

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number', 'sid' => 6657]);

try {
    // 创建客户端
    $personClient = new Person();
    $deptClient = new Department();
    
    // 调试信息：显示当前使用的配置
    $currentConfig = Config::get();
    echo "=== 当前配置信息 ===\n";
    echo "SP: " . ($currentConfig['sp'] ?? '未设置') . "\n";
    echo "SID: " . ($currentConfig['sid'] ?? '未设置') . "\n";
    echo "URL: " . ($currentConfig['url'] ?? '未设置') . "\n";
    echo "==================\n\n";

    // ===== 示例1：获取指定部门的人员信息 =====
    echo "=== 示例1：获取指定部门的人员信息 ===\n";
    echo "⚠️  注意：此示例已注释，请先获取实际的部门ID后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $organId = 123456;  // 替换为实际的部门ID

    echo "正在获取部门人员信息...\n";
    echo "部门ID: {$organId}\n\n";
    
    $result = $personClient->queryOrganAccountInfo($organId);
    
    if (empty($result)) {
        echo "该部门下没有人员\n";
    } else {
        echo "找到 " . count($result) . " 个人员：\n\n";
        foreach ($result as $index => $person) {
            echo "人员 " . ($index + 1) . ":\n";
            echo "  UID: " . ($person['id'] ?? '无') . "\n";
            echo "  姓名: " . ($person['cust_name'] ?? '无') . "\n";
            echo "  证件号: " . ($person['idcard'] ?? '无') . "\n";
            echo "  手机号: " . ($person['mobilephone'] ?? '无') . "\n";
            echo "  补助余额: ¥" . ($person['sub_balance'] ?? 0) . "\n";
            echo "  账户余额: ¥" . ($person['cashMoney'] ?? 0) . "\n";
            echo "\n";
        }
    }
    */

    // ===== 示例2：先获取部门列表，再查询部门人员（推荐方式） =====
    echo "\n=== 示例2：先获取部门列表，再查询部门人员（推荐方式） ===\n";
    echo "⚠️  注意：此示例演示了完整的查询流程\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 第一步：获取所有部门
    echo "第一步：获取部门列表...\n";
    $departments = $deptClient->getDepartments();
    
    if (empty($departments)) {
        echo "暂无部门信息\n";
    } else {
        echo "找到 " . count($departments) . " 个部门：\n";
        foreach ($departments as $index => $dept) {
            if ($index >= 5) {  // 只显示前5个
                echo "  ... 还有 " . (count($departments) - 5) . " 个部门\n";
                break;
            }
            echo "  - {$dept['title']} (ID: {$dept['id']})\n";
        }
        
        // 第二步：选择第一个部门查询人员
        $targetDept = $departments[0];
        echo "\n第二步：查询部门【{$targetDept['title']}】的人员信息...\n";
        echo "部门ID: {$targetDept['id']}\n\n";
        
        $persons = $personClient->queryOrganAccountInfo($targetDept['id']);
        
        if (empty($persons)) {
            echo "该部门下没有人员\n";
        } else {
            echo "找到 " . count($persons) . " 个人员：\n\n";
            foreach ($persons as $index => $person) {
                if ($index >= 10) {  // 只显示前10个
                    echo "  ... 还有 " . (count($persons) - 10) . " 个人员\n";
                    break;
                }
                echo "人员 " . ($index + 1) . ": {$person['cust_name']} (UID: {$person['id']})\n";
                echo "  证件号: {$person['idcard']}\n";
                echo "  手机号: {$person['mobilephone']}\n";
                echo "  补助余额: ¥{$person['sub_balance']}\n";
                echo "  账户余额: ¥{$person['cashMoney']}\n\n";
            }
        }
    }
    */

    // ===== 示例3：批量查询多个部门的人员信息 =====
    echo "\n=== 示例3：批量查询多个部门的人员信息 ===\n";
    echo "⚠️  注意：访问频率限制为 30次/分钟\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $organIds = [123456, 123457, 123458];  // 要查询的部门ID列表

    echo "正在批量查询 " . count($organIds) . " 个部门的人员信息...\n\n";
    
    $totalPersons = 0;
    foreach ($organIds as $index => $organId) {
        echo "查询部门 " . ($index + 1) . " (ID: {$organId})...\n";
        
        try {
            $persons = $personClient->queryOrganAccountInfo($organId);
            $personCount = count($persons);
            $totalPersons += $personCount;
            
            echo "  ✅ 找到 {$personCount} 个人员\n";
            
            // 显示部分人员信息
            if ($personCount > 0) {
                $sampleCount = min(3, $personCount);
                echo "  示例人员：\n";
                for ($i = 0; $i < $sampleCount; $i++) {
                    echo "    - {$persons[$i]['cust_name']} (余额: ¥{$persons[$i]['cashMoney']})\n";
                }
                if ($personCount > 3) {
                    echo "    ... 还有 " . ($personCount - 3) . " 个人员\n";
                }
            }
            
        } catch (\Exception $e) {
            echo "  ❌ 查询失败: " . $e->getMessage() . "\n";
        }
        
        echo "\n";
        
        // 注意访问频率限制（30次/分钟），如果部门较多，建议适当延迟
        if ($index < count($organIds) - 1 && count($organIds) > 10) {
            sleep(2);  // 延迟2秒
        }
    }
    
    echo "✅ 批量查询完成！\n";
    echo "共查询 " . count($organIds) . " 个部门，找到 {$totalPersons} 个人员\n";
    */

    // ===== 示例4：统计部门人员及余额信息 =====
    echo "\n=== 示例4：统计部门人员及余额信息 ===\n";
    echo "⚠️  注意：此示例演示了如何统计部门人员的余额情况\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $organId = 123456;  // 要统计的部门ID

    echo "正在统计部门人员及余额信息...\n";
    echo "部门ID: {$organId}\n\n";
    
    $persons = $personClient->queryOrganAccountInfo($organId);
    
    if (empty($persons)) {
        echo "该部门下没有人员\n";
    } else {
        $totalCount = count($persons);
        $totalCashMoney = 0;
        $totalSubBalance = 0;
        $hasPhoneCount = 0;
        $hasIdCardCount = 0;
        
        // 统计数据
        foreach ($persons as $person) {
            $totalCashMoney += floatval($person['cashMoney'] ?? 0);
            $totalSubBalance += floatval($person['sub_balance'] ?? 0);
            if (!empty($person['mobilephone'])) {
                $hasPhoneCount++;
            }
            if (!empty($person['idcard'])) {
                $hasIdCardCount++;
            }
        }
        
        // 显示统计结果
        echo "=== 统计结果 ===\n";
        echo "总人数: {$totalCount}\n";
        echo "账户总余额: ¥" . number_format($totalCashMoney, 2) . "\n";
        echo "补助总余额: ¥" . number_format($totalSubBalance, 2) . "\n";
        echo "平均账户余额: ¥" . number_format($totalCashMoney / $totalCount, 2) . "\n";
        echo "平均补助余额: ¥" . number_format($totalSubBalance / $totalCount, 2) . "\n";
        echo "有手机号人数: {$hasPhoneCount} (" . round($hasPhoneCount / $totalCount * 100, 2) . "%)\n";
        echo "有证件号人数: {$hasIdCardCount} (" . round($hasIdCardCount / $totalCount * 100, 2) . "%)\n";
        
        // 找出余额最高的前3名
        echo "\n=== 账户余额TOP3 ===\n";
        usort($persons, function($a, $b) {
            return floatval($b['cashMoney'] ?? 0) <=> floatval($a['cashMoney'] ?? 0);
        });
        
        for ($i = 0; $i < min(3, $totalCount); $i++) {
            $person = $persons[$i];
            echo ($i + 1) . ". {$person['cust_name']} - ¥{$person['cashMoney']}\n";
        }
    }
    */

    // ===== 示例5：导出部门人员信息到CSV =====
    echo "\n=== 示例5：导出部门人员信息到CSV ===\n";
    echo "⚠️  注意：此示例演示了如何将部门人员信息导出为CSV文件\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $organId = 123456;  // 要导出的部门ID
    $outputFile = __DIR__ . '/organ_persons_' . $organId . '.csv';

    echo "正在导出部门人员信息...\n";
    echo "部门ID: {$organId}\n";
    echo "输出文件: {$outputFile}\n\n";
    
    $persons = $personClient->queryOrganAccountInfo($organId);
    
    if (empty($persons)) {
        echo "该部门下没有人员，无需导出\n";
    } else {
        // 打开CSV文件
        $fp = fopen($outputFile, 'w');
        
        // 写入UTF-8 BOM，确保Excel正确识别中文
        fwrite($fp, "\xEF\xBB\xBF");
        
        // 写入表头
        fputcsv($fp, ['UID', '姓名', '证件号', '手机号', '补助余额', '账户余额']);
        
        // 写入数据
        foreach ($persons as $person) {
            fputcsv($fp, [
                $person['id'] ?? '',
                $person['cust_name'] ?? '',
                $person['idcard'] ?? '',
                $person['mobilephone'] ?? '',
                $person['sub_balance'] ?? 0,
                $person['cashMoney'] ?? 0
            ]);
        }
        
        fclose($fp);
        
        echo "✅ 导出成功！\n";
        echo "共导出 " . count($persons) . " 个人员\n";
        echo "文件路径: {$outputFile}\n";
    }
    */

    // ===== 示例6：获取所有部门的人员总数统计 =====
    echo "\n=== 示例6：获取所有部门的人员总数统计 ===\n";
    echo "⚠️  注意：此示例会查询所有部门，请注意访问频率限制\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    echo "正在获取所有部门的人员总数统计...\n\n";
    
    // 第一步：获取所有部门
    $departments = $deptClient->getDepartments();
    
    if (empty($departments)) {
        echo "暂无部门信息\n";
    } else {
        echo "共有 " . count($departments) . " 个部门\n";
        echo "正在逐个查询人员数量...\n\n";
        
        $deptStats = [];
        $totalPersons = 0;
        $requestCount = 0;
        
        foreach ($departments as $dept) {
            try {
                $persons = $personClient->queryOrganAccountInfo($dept['id']);
                $personCount = count($persons);
                $totalPersons += $personCount;
                $requestCount++;
                
                $deptStats[] = [
                    'name' => $dept['title'],
                    'id' => $dept['id'],
                    'count' => $personCount
                ];
                
                echo "  - {$dept['title']}: {$personCount} 人\n";
                
                // 控制访问频率（30次/分钟 = 2秒/次）
                if ($requestCount % 25 == 0) {
                    echo "\n  已查询 {$requestCount} 个部门，暂停60秒避免超过访问频率限制...\n\n";
                    sleep(60);
                }
                
            } catch (\Exception $e) {
                echo "  - {$dept['title']}: 查询失败 ({$e->getMessage()})\n";
            }
        }
        
        // 按人数排序
        usort($deptStats, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        
        echo "\n=== 统计结果 ===\n";
        echo "总部门数: " . count($departments) . "\n";
        echo "总人数: {$totalPersons}\n";
        echo "平均每个部门人数: " . round($totalPersons / count($departments), 2) . "\n\n";
        
        echo "=== 人数最多的5个部门 ===\n";
        for ($i = 0; $i < min(5, count($deptStats)); $i++) {
            $dept = $deptStats[$i];
            echo ($i + 1) . ". {$dept['name']}: {$dept['count']} 人\n";
        }
    }
    */

    // ===== 使用说明 =====
    echo "\n=== 使用说明 ===\n";
    echo "1. 参数说明：\n";
    echo "   - organId: 部门编号（必填）\n";
    echo "   - 可以通过 Department::getDepartments() 获取部门列表\n\n";
    
    echo "2. 返回字段说明：\n";
    echo "   - id: 用户UID\n";
    echo "   - cust_name: 姓名\n";
    echo "   - idcard: 证件号\n";
    echo "   - mobilephone: 电话号码\n";
    echo "   - sub_balance: 补助余额\n";
    echo "   - cashMoney: 账户余额\n\n";
    
    echo "3. 访问频率限制：\n";
    echo "   - 30次/分钟\n";
    echo "   - 批量查询时建议控制频率，避免超限\n";
    echo "   - 超过限制可能导致请求失败\n\n";
    
    echo "4. 常见使用场景：\n";
    echo "   - 查询指定部门的所有人员\n";
    echo "   - 统计部门人员的余额情况\n";
    echo "   - 导出部门人员信息报表\n";
    echo "   - 批量查询多个部门的人员\n";
    echo "   - 分析部门人员分布情况\n\n";
    
    echo "5. 注意事项：\n";
    echo "   - organId 必须是有效的部门ID\n";
    echo "   - 如果部门下没有人员，返回空数组\n";
    echo "   - 批量查询时注意控制频率（建议每次查询间隔2秒）\n";
    echo "   - 查询所有部门时，每25次查询后暂停60秒\n";
    echo "   - 返回的余额信息为实时数据\n";

} catch (\InvalidArgumentException $e) {
    echo "\n❌ 参数错误: " . $e->getMessage() . "\n";
    echo "\n💡 提示：请检查以下内容：\n";
    echo "1. organId（部门编号）不能为空\n";
    echo "2. organId 必须是有效的部门ID\n";
    echo "3. SID（学校编号）必须已配置\n";
    
} catch (\Exception $e) {
    echo "\n❌ 错误: " . $e->getMessage() . "\n";
    
    // 如果是 403 错误，给出更详细的提示
    if (strpos($e->getMessage(), '403') !== false || strpos($e->getMessage(), '没有找到sp信息') !== false) {
        echo "\n💡 提示: 403 错误通常是因为 SP 号无效或未配置。\n";
        echo "解决方法：\n";
        echo "1. 确保你使用的是有效的 SP 号（请联系易科士对接人获取）\n";
        echo "2. 检查 .env 文件中的 YKS_SP 和 YKS_SID 配置是否正确\n";
        echo "3. 或者直接在代码中传入有效的 SP 号和 SID\n";
    }
    
    // 如果是 SID 错误
    if (strpos($e->getMessage(), 'SID') !== false) {
        echo "\n💡 提示: 请确保配置了正确的 SID（学校编号）。\n";
        echo "可以通过 Config::set(['sid' => 学校编号]) 或构造函数传入。\n";
    }
    
    // 如果是部门相关错误
    if (strpos($e->getMessage(), '部门') !== false || strpos($e->getMessage(), 'organId') !== false) {
        echo "\n💡 提示: 部门查询失败，可能的原因：\n";
        echo "1. 部门ID不存在或无效\n";
        echo "2. 没有查询该部门的权限\n";
        echo "3. 部门已被删除\n";
        echo "\n建议：\n";
        echo "1. 先通过 Department::getDepartments() 获取有效的部门列表\n";
        echo "2. 使用返回的部门ID进行查询\n";
    }
    
    // 如果是频率限制错误
    if (strpos($e->getMessage(), '频率') !== false || strpos($e->getMessage(), '限制') !== false) {
        echo "\n💡 提示: 访问频率超限，请稍后再试。\n";
        echo "本接口限制为 30次/分钟\n";
        echo "建议：\n";
        echo "1. 批量查询时在每次请求之间增加延迟（建议2秒）\n";
        echo "2. 等待1分钟后重新尝试\n";
        echo "3. 减少并发请求数量\n";
    }
}

