<?php

/**
 * 获取组织部门信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 */

// 自动加载：优先使用 composer，如果没有则使用简单的 bootstrap
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/bootstrap.php';
}

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Department;

// 方式1：从配置文件加载（推荐）
Config::loadFromFile(__DIR__ . '/config.php');

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number', 'sid' => 14]);

// 方式3：使用默认配置（代码中已内置默认值）

try {
    // 创建 Department 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Department();
    
    // 方式B：手动传入 SP 号和 SID
    // $client = new Department('your_sp_number_here', 14);

    // 方式1：获取所有部门信息
    $result = $client->getDepartments();
    
    echo "获取成功！\n";
    echo "部门列表：\n";
    print_r($result);

    // 方式2：根据组织名称筛选部门（可选参数）
    // $result = $client->getDepartments('体验学校05');
    // print_r($result);

    // 结果示例：
    // [
    //   {"id":"155610795603218","title":"部门一","parentId":"14"}
    // ]

} catch (\Exception $e) {
    echo "错误: " . $e->getMessage() . "\n";
}

