<?php

/**
 * 获取门禁流水信息示例
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

    // ===== 示例1：获取最近10分钟的门禁流水（全部设备） =====
    echo "=== 示例1：获取最近10分钟的门禁流水（全部设备） ===\n";
    echo "正在获取门禁流水信息（默认时间范围：最近10分钟）...\n";
    $result = $client->getDoorPass(1, 20, 0);
    
    if (empty($result) || empty($result['list'])) {
        echo "该时间段内没有门禁流水记录\n";
    } else {
        $total = $result['total'] ?? 0;
        $list = $result['list'] ?? [];
        echo "获取成功！总共 {$total} 条记录，当前页显示 " . count($list) . " 条：\n";
        foreach ($list as $record) {
            $cname = $record['cname'] ?? '无';
            $passTime = $record['passTime'] ?? '无';
            $inOutDesc = $record['inOutDesc'] ?? '无';
            $mode = $record['mode'] ?? 0;
            $deviceType = $record['device_type'] ?? 0;
            
            // 解析模式
            $modeDesc = '';
            if ($mode == 1) $modeDesc = '人脸';
            elseif ($mode == 2) $modeDesc = '刷卡';
            elseif ($mode == 3) $modeDesc = '指纹';
            else $modeDesc = '未知';
            
            $deviceTypeDesc = $deviceType == 5 ? '消费' : ($deviceType == 6 ? '门禁' : '未知');
            
            echo "  - {$cname} | {$passTime} | {$inOutDesc} | {$modeDesc} | {$deviceTypeDesc}\n";
        }
        
        // 显示分页信息
        if (isset($result['pages']) && $result['pages'] > 1) {
            echo "\n分页信息：\n";
            echo "  当前页: {$result['pageNum']}/{$result['pages']}\n";
            echo "  每页: {$result['pageSize']} 条\n";
            echo "  总记录数: {$total} 条\n";
        }
    }

    // ===== 示例2：获取指定时间范围的门禁流水 =====
    echo "\n=== 示例2：获取指定时间范围的门禁流水 ===\n";
    $beginTime = date('Y-m-d 00:00:00', strtotime('-7 days')); // 7天前
    $endTime = date('Y-m-d H:i:s'); // 当前时间
    echo "时间范围：{$beginTime} 至 {$endTime}\n";
    echo "正在获取门禁流水信息...\n";
    
    $result2 = $client->getDoorPass(1, 50, 0, $beginTime, $endTime);
    
    if (empty($result2) || empty($result2['list'])) {
        echo "该时间段内没有门禁流水记录\n";
    } else {
        $total2 = $result2['total'] ?? 0;
        $list2 = $result2['list'] ?? [];
        echo "获取成功！总共 {$total2} 条记录，当前页显示 " . count($list2) . " 条：\n";
        
        // 统计进出情况
        $inCount = 0;
        $outCount = 0;
        foreach ($list2 as $record) {
            $inOrOut = $record['inOrOut'] ?? 0;
            if ($inOrOut == 1) {
                $inCount++;
            } elseif ($inOrOut == 2) {
                $outCount++;
            }
        }
        echo "  进门: {$inCount} 次，出门: {$outCount} 次\n";
    }

    // ===== 示例3：获取指定设备的门禁流水 =====
    echo "\n=== 示例3：获取指定设备的门禁流水 ===\n";
    echo "（需要先获取设备ID，这里使用示例设备ID）\n";
    $deviceId = 1550914795; // 设备ID，实际使用时应从获取设备信息接口获取
    $beginTime3 = date('Y-m-d 00:00:00', strtotime('-1 day')); // 1天前
    $endTime3 = date('Y-m-d H:i:s'); // 当前时间
    
    echo "设备ID: {$deviceId}\n";
    echo "时间范围：{$beginTime3} 至 {$endTime3}\n";
    echo "正在获取指定设备的门禁流水信息...\n";
    
    $result3 = $client->getDoorPass(1, 100, $deviceId, $beginTime3, $endTime3);
    
    if (empty($result3) || empty($result3['list'])) {
        echo "该设备在该时间段内没有门禁流水记录\n";
    } else {
        $total3 = $result3['total'] ?? 0;
        $list3 = $result3['list'] ?? [];
        echo "获取成功！该设备在该时间段内共有 {$total3} 条记录，当前页显示 " . count($list3) . " 条：\n";
        foreach ($list3 as $index => $record) {
            if ($index >= 5) { // 只显示前5条
                echo "  ... 还有 " . (count($list3) - 5) . " 条记录\n";
                break;
            }
            $cname = $record['cname'] ?? '无';
            $passTime = $record['passTime'] ?? '无';
            $inOutDesc = $record['inOutDesc'] ?? '无';
            echo "  - {$cname} | {$passTime} | {$inOutDesc}\n";
        }
    }

    // ===== 示例4：分页获取所有门禁流水 =====
    echo "\n=== 示例4：分页获取所有门禁流水（注释代码） ===\n";
    echo "（需要时可以取消注释使用）\n";
    
    /*
    $beginTime4 = date('Y-m-d 00:00:00', strtotime('-1 day'));
    $endTime4 = date('Y-m-d H:i:s');
    $page = 1;
    $limit = 100;
    $allRecords = [];
    
    do {
        echo "正在获取第 {$page} 页...\n";
        $result = $client->getDoorPass($page, $limit, 0, $beginTime4, $endTime4);
        
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
    
    echo "总共获取到 " . count($allRecords) . " 条门禁流水记录\n";
    */

    // 结果示例：
    // {
    //   "total": 86,
    //   "list": [
    //     {
    //       "mode": 1,
    //       "custno": "88888",
    //       "inOutDesc": "进门",
    //       "SEX": 1,
    //       "passTime": "2019-05-14 16:52:15",
    //       "inOrOut": 1,
    //       "cname": "艾京",
    //       "device_type": 6,
    //       "cid": 28436
    //     }
    //   ],
    //   "pageNum": 1,
    //   "pageSize": 5,
    //   "pages": 18
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
    if (strpos($e->getMessage(), '参数错误') !== false) {
        echo "\n💡 提示:\n";
        echo "- 页数必须大于等于1且不能大于100\n";
        echo "- 分页条数必须大于等于1且不能大于1000\n";
    }
    
    // 如果是 SID 错误
    if (strpos($e->getMessage(), 'SID') !== false) {
        echo "\n💡 提示: 请确保配置了正确的 SID（学校编号）。\n";
        echo "可以通过 Config::set(['sid' => 学校编号]) 或构造函数传入。\n";
    }
}

