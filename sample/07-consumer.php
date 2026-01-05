<?php

/**
 * 获取消费流水信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * 注意：
 * - 访问控制为 10次/分，请控制调用频率
 * - page不能大于100，limit不能大于1000
 * - 交易高峰期接口不再返回数据，如果对数据实时性要求很高的厂家，建议对接流水推送接口
 * - 2022年3月后纠错流水平台会生产一条金额为负的流水通过本接口返回，trade_no号为原纠错流水的out_trade_no
 */

// 自动加载：优先使用 composer，如果没有则使用简单的 bootstrap
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/bootstrap.php';
}

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Consumer;

// 方式1：从配置文件加载（推荐）
Config::loadFromFile(__DIR__ . '/config.php');

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number', 'sid' => 14]);

try {
    // 创建 Consumer 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Consumer();
    
    // 方式B：手动传入 SP 号和 SID
    // $client = new Consumer('your_sp_number_here', 14);

    // ===== 示例1：获取最近7天的消费流水 =====
    echo "=== 示例1：获取最近7天的消费流水 ===\n";
    $beginTime = date('Y-m-d 00:00:00', strtotime('-7 days')); // 7天前
    $endTime = date('Y-m-d H:i:s'); // 当前时间
    echo "时间范围：{$beginTime} 至 {$endTime}\n";
    echo "正在获取消费流水信息...\n";
    
    $result = $client->getConsumeBySchool(1, 20, $beginTime, $endTime);
    
    if (empty($result) || empty($result['list'])) {
        echo "该时间段内没有消费流水记录\n";
    } else {
        $total = $result['total'] ?? 0;
        $list = $result['list'] ?? [];
        echo "获取成功！总共 {$total} 条记录，当前页显示 " . count($list) . " 条：\n";
        foreach ($list as $record) {
            $deviceName = $record['device_name'] ?? '无';
            $tradeTime = $record['tradeTime'] ?? ($record['gmt_payment'] ?? '无');
            $tradeType = $record['tradeType'] ?? '无';
            $buyerPayAmount = $record['buyer_pay_amount'] ?? 0;
            $totalAmount = $record['total_amount'] ?? 0;
            $idCard = $record['id_card'] ?? '无';
            $outTradeNo = $record['out_trade_no'] ?? '无';
            
            echo "  - 设备: {$deviceName} | 时间: {$tradeTime} | 类型: {$tradeType} | 金额: {$buyerPayAmount}元 | 总金额: {$totalAmount}元 | 身份证: {$idCard} | 交易号: {$outTradeNo}\n";
        }
        
        // 显示分页信息
        if (isset($result['pages']) && $result['pages'] > 1) {
            echo "\n分页信息：\n";
            echo "  当前页: {$result['pageNum']}/{$result['pages']}\n";
            echo "  每页: {$result['pageSize']} 条\n";
            echo "  总记录数: {$total} 条\n";
        }
    }

    // ===== 示例2：获取指定时间范围的消费流水 =====
    echo "\n=== 示例2：获取指定时间范围的消费流水 ===\n";
    // 使用最近30天的时间范围（更合理，避免查询过旧数据导致403错误）
    $beginTime2 = date('Y-m-d 00:00:00', strtotime('-30 days')); // 30天前
    $endTime2 = date('Y-m-d H:i:s'); // 当前时间
    echo "时间范围：{$beginTime2} 至 {$endTime2}\n";
    echo "正在获取消费流水信息...\n";
    
    $result2 = $client->getConsumeBySchool(1, 50, $beginTime2, $endTime2);
    
    if (empty($result2) || empty($result2['list'])) {
        echo "该时间段内没有消费流水记录\n";
    } else {
        $total2 = $result2['total'] ?? 0;
        $list2 = $result2['list'] ?? [];
        echo "获取成功！总共 {$total2} 条记录，当前页显示 " . count($list2) . " 条：\n";
        
        // 统计交易类型
        $tradeTypeStats = [];
        $totalAmount = 0;
        foreach ($list2 as $record) {
            $tradeType = $record['tradeType'] ?? '未知';
            $amount = $record['buyer_pay_amount'] ?? 0;
            
            if (!isset($tradeTypeStats[$tradeType])) {
                $tradeTypeStats[$tradeType] = ['count' => 0, 'amount' => 0];
            }
            $tradeTypeStats[$tradeType]['count']++;
            $tradeTypeStats[$tradeType]['amount'] += $amount;
            $totalAmount += $amount;
        }
        
        echo "\n交易类型统计：\n";
        foreach ($tradeTypeStats as $type => $stats) {
            echo "  {$type}: {$stats['count']} 笔，总金额: {$stats['amount']} 元\n";
        }
        echo "  总计: {$totalAmount} 元\n";
    }

    // ===== 示例3：根据用户ID查询消费流水 =====
    echo "\n=== 示例3：根据用户ID查询消费流水 ===\n";
    echo "（需要先获取用户ID，这里使用示例用户ID）\n";
    $cid = 402319; // 用户ID，实际使用时应从获取人员信息接口获取
    $beginTime3 = date('Y-m-d 00:00:00', strtotime('-30 days')); // 30天前
    $endTime3 = date('Y-m-d H:i:s'); // 当前时间
    
    echo "用户ID: {$cid}\n";
    echo "时间范围：{$beginTime3} 至 {$endTime3}\n";
    echo "正在获取指定用户的消费流水信息...\n";
    
    $result3 = $client->getConsumeBySchool(1, 100, $beginTime3, $endTime3, $cid);
    
    if (empty($result3) || empty($result3['list'])) {
        echo "该用户在该时间段内没有消费流水记录\n";
    } else {
        $total3 = $result3['total'] ?? 0;
        $list3 = $result3['list'] ?? [];
        echo "获取成功！该用户在该时间段内共有 {$total3} 条记录，当前页显示 " . count($list3) . " 条：\n";
        foreach ($list3 as $index => $record) {
            if ($index >= 5) { // 只显示前5条
                echo "  ... 还有 " . (count($list3) - 5) . " 条记录\n";
                break;
            }
            $deviceName = $record['device_name'] ?? '无';
            $tradeTime = $record['tradeTime'] ?? ($record['gmt_payment'] ?? '无');
            $tradeType = $record['tradeType'] ?? '无';
            $buyerPayAmount = $record['buyer_pay_amount'] ?? 0;
            $endCashMoney = $record['endCashMoney'] ?? 0;
            $endSubBalance = $record['endSubBalance'] ?? 0;
            echo "  - {$deviceName} | {$tradeTime} | {$tradeType} | 消费: {$buyerPayAmount}元 | 余额: {$endCashMoney}元 | 补助: {$endSubBalance}元\n";
        }
    }

    // ===== 示例4：根据身份证号码查询消费流水 =====
    echo "\n=== 示例4：根据身份证号码查询消费流水 ===\n";
    echo "（需要先获取身份证号码，这里使用示例身份证号码）\n";
    $idcard = '510000000000'; // 身份证号码，实际使用时应从获取人员信息接口获取
    $beginTime4 = date('Y-m-d 00:00:00', strtotime('-30 days')); // 30天前
    $endTime4 = date('Y-m-d H:i:s'); // 当前时间
    
    echo "身份证号码: {$idcard}\n";
    echo "时间范围：{$beginTime4} 至 {$endTime4}\n";
    echo "正在获取指定身份证的消费流水信息...\n";
    
    $result4 = $client->getConsumeBySchool(1, 100, $beginTime4, $endTime4, null, $idcard);
    
    if (empty($result4) || empty($result4['list'])) {
        echo "该身份证在该时间段内没有消费流水记录\n";
    } else {
        $total4 = $result4['total'] ?? 0;
        $list4 = $result4['list'] ?? [];
        echo "获取成功！该身份证在该时间段内共有 {$total4} 条记录，当前页显示 " . count($list4) . " 条：\n";
        foreach ($list4 as $index => $record) {
            if ($index >= 5) { // 只显示前5条
                echo "  ... 还有 " . (count($list4) - 5) . " 条记录\n";
                break;
            }
            $deviceName = $record['device_name'] ?? '无';
            $tradeTime = $record['tradeTime'] ?? ($record['gmt_payment'] ?? '无');
            $tradeType = $record['tradeType'] ?? '无';
            $buyerPayAmount = $record['buyer_pay_amount'] ?? 0;
            echo "  - {$deviceName} | {$tradeTime} | {$tradeType} | 消费: {$buyerPayAmount}元\n";
        }
    }

    // ===== 示例5：分页获取所有消费流水（注释代码） =====
    echo "\n=== 示例5：分页获取所有消费流水（注释代码） ===\n";
    echo "（需要时可以取消注释使用）\n";
    
    /*
    $beginTime5 = date('Y-m-d 00:00:00', strtotime('-1 day'));
    $endTime5 = date('Y-m-d H:i:s');
    $page = 1;
    $limit = 100;
    $allRecords = [];
    
    do {
        echo "正在获取第 {$page} 页...\n";
        $result = $client->getConsumeBySchool($page, $limit, $beginTime5, $endTime5);
        
        if (empty($result) || empty($result['list'])) {
            break;
        }
        
        $list = $result['list'];
        $allRecords = array_merge($allRecords, $list);
        
        echo "  获取到 " . count($list) . " 条记录\n";
        
        // 判断是否还有下一页
        $pages = $result['pages'] ?? 1;
        if ($page >= $pages) {
            break;
        }
        
        $page++;
        sleep(7); // 等待7秒，避免超过频率限制（10次/分）
        
    } while (true);
    
    echo "总共获取到 " . count($allRecords) . " 条消费流水记录\n";
    */

    // 结果示例：
    // {
    //   "code": 200,
    //   "message": null,
    //   "value": {
    //     "total": 3361,
    //     "list": [
    //       {
    //         "gmt_create": "2019-07-04T01:10:54.000+0000",
    //         "device_name": "测试人脸1号",
    //         "gmt_payment": "2019-07-04T01:11:30.000+0000",
    //         "out_trade_no": "201907040911299939926442271C0",
    //         "total_amount": 0.01,
    //         "device_code": "1550016555",
    //         "id_card": "18",
    //         "trade_no": "201907040911299942162760765",
    //         "buyer_pay_amount": 0.01,
    //         "tradeType": "补助",
    //         "endSubBalance": 0.01,
    //         "tradeTime": "2020-04-07 10:05:03",
    //         "endCashMoney": 2.00
    //       }
    //     ],
    //     "pageNum": 1,
    //     "pageSize": 1,
    //     "pages": 3361
    //   }
    // }

} catch (\Exception $e) {
    echo "\n❌ 错误: " . $e->getMessage() . "\n";
    
    // 如果是 403 错误，给出更详细的提示
    if (strpos($e->getMessage(), '403') !== false || strpos($e->getMessage(), '没有找到sp信息') !== false) {
        echo "\n💡 提示: 403 错误可能的原因：\n";
        echo "1. SP 号无效或未配置\n";
        echo "   - 确保你使用的是有效的 SP 号（请联系易科士对接人获取）\n";
        echo "   - 检查 sample/config.php 中的 'sp' 配置是否正确\n";
        echo "   - 或者直接在代码中传入有效的 SP 号\n";
        echo "2. 查询的时间范围可能过旧或超出限制\n";
        echo "   - 建议查询最近1-3个月的数据\n";
        echo "   - 如果必须查询历史数据，请联系易科士确认时间范围限制\n";
        echo "3. 接口访问频率超限（10次/分）\n";
        echo "   - 请降低调用频率，建议每次调用后等待至少7秒\n";
    }
    
    // 如果是参数错误
    if (strpos($e->getMessage(), '参数错误') !== false) {
        echo "\n💡 提示:\n";
        echo "- 页数必须大于等于1且不能大于100\n";
        echo "- 分页条数必须大于等于1且不能大于1000\n";
        echo "- 开始时间和结束时间不能为空，格式：yyyy-MM-dd HH:mm:ss\n";
        echo "- 开始时间不能大于结束时间\n";
    }
    
    // 如果是 SID 错误
    if (strpos($e->getMessage(), 'SID') !== false) {
        echo "\n💡 提示: 请确保配置了正确的 SID（学校编号）。\n";
        echo "可以通过 Config::set(['sid' => 学校编号]) 或构造函数传入。\n";
    }
    
    // 如果是时间格式错误
    if (strpos($e->getMessage(), '时间格式') !== false) {
        echo "\n💡 提示: 时间格式必须为 yyyy-MM-dd HH:mm:ss，例如：2020-06-01 00:00:00\n";
    }
}

