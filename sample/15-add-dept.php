<?php

/**
 * 添加部门信息示例
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

// 方式1：从环境变量加载配置（推荐）
Config::loadFromEnv(dirname(__DIR__));

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number', 'sid' => 6657]);

try {
    // 创建 Department 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Department();
    
    // 方式B：手动传入 SP 号和 SID
    // $client = new Department('your_sp_number_here', 6657);

    // 调试信息：显示当前使用的配置
    $currentConfig = Config::get();
    echo "=== 当前配置信息 ===\n";
    echo "SP: " . ($currentConfig['sp'] ?? '未设置') . "\n";
    echo "SID: " . ($currentConfig['sid'] ?? '未设置') . "\n";
    echo "URL: " . ($currentConfig['url'] ?? '未设置') . "\n";
    echo "==================\n\n";

    // ===== 示例1：添加顶级部门（直属学校） =====
    echo "=== 示例1：添加顶级部门（直属学校） ===\n";
    
    $topDeptName = '研发部门';
    
    echo "正在添加顶级部门...\n";
    echo "部门名称: {$topDeptName}\n";
    echo "上级部门: 学校（顶级）\n";
    
    // 不传 pid 参数，默认使用学校ID作为上级部门（即顶级部门）
    $result = $client->addDept($topDeptName);
    
    echo "✅ 添加成功！\n";
    echo "部门信息：\n";
    echo "  - 部门ID: " . ($result['id'] ?? '') . "\n";
    echo "  - 部门名称: " . ($result['name'] ?? '') . "\n";
    echo "  - 上级ID: " . ($result['pid'] ?? '') . "\n";
    echo "  - 学校ID: " . ($result['teamId'] ?? '') . "\n";
    echo "\n完整返回数据：\n";
    print_r($result);

    // ===== 示例2：添加子部门（指定上级部门） =====
    echo "\n=== 示例2：添加子部门（指定上级部门） ===\n";
    echo "⚠️  注意：此示例已注释，请先获取实际的上级部门ID后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 假设上级部门ID为 155610795603218（可以通过 getDepartments 接口获取）
    $parentDeptId = '155610795603218';
    $subDeptName = '研发一组';
    
    echo "正在添加子部门...\n";
    echo "部门名称: {$subDeptName}\n";
    echo "上级部门ID: {$parentDeptId}\n";
    
    // 传入 pid 参数，指定上级部门
    $result = $client->addDept($subDeptName, $parentDeptId);
    
    echo "✅ 添加成功！\n";
    echo "部门信息：\n";
    echo "  - 部门ID: " . ($result['id'] ?? '') . "\n";
    echo "  - 部门名称: " . ($result['name'] ?? '') . "\n";
    echo "  - 上级ID: " . ($result['pid'] ?? '') . "\n";
    echo "  - 学校ID: " . ($result['teamId'] ?? '') . "\n";
    echo "\n完整返回数据：\n";
    print_r($result);
    */

    // ===== 示例3：获取现有部门后添加子部门（推荐方式） =====
    echo "\n=== 示例3：获取现有部门后添加子部门（推荐方式） ===\n";
    echo "⚠️  注意：此示例演示了如何先获取部门列表，再添加子部门\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 第一步：获取现有部门列表
    echo "第一步：获取现有部门列表...\n";
    $departments = $client->getDepartments();
    
    if (empty($departments)) {
        echo "暂无部门信息\n";
    } else {
        echo "找到 " . count($departments) . " 个部门：\n";
        foreach ($departments as $dept) {
            echo "  - {$dept['title']} (ID: {$dept['id']}, 上级ID: {$dept['parentId']})\n";
        }
        
        // 第二步：选择一个部门作为上级部门（这里选择第一个）
        $parentDept = $departments[0];
        echo "\n第二步：选择上级部门...\n";
        echo "上级部门: {$parentDept['title']} (ID: {$parentDept['id']})\n";
        
        // 第三步：在该部门下添加子部门
        echo "\n第三步：添加子部门...\n";
        $newSubDeptName = '研发二组';
        echo "新部门名称: {$newSubDeptName}\n";
        
        $result = $client->addDept($newSubDeptName, $parentDept['id']);
        
        echo "✅ 添加成功！\n";
        echo "部门信息：\n";
        echo "  - 部门ID: " . ($result['id'] ?? '') . "\n";
        echo "  - 部门名称: " . ($result['name'] ?? '') . "\n";
        echo "  - 上级ID: " . ($result['pid'] ?? '') . "\n";
        echo "  - 学校ID: " . ($result['teamId'] ?? '') . "\n";
        echo "\n完整返回数据：\n";
        print_r($result);
    }
    */

    // ===== 示例4：批量添加部门结构 =====
    echo "\n=== 示例4：批量添加部门结构 ===\n";
    echo "⚠️  注意：此示例演示了如何批量创建多级部门结构\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 创建一个完整的部门树结构
    echo "正在创建部门树结构...\n\n";
    
    // 第一层：添加顶级部门
    echo "1. 添加顶级部门【技术部】...\n";
    $techDept = $client->addDept('技术部');
    echo "   ✅ 创建成功，ID: {$techDept['id']}\n\n";
    sleep(1); // 间隔1秒，避免频率过快
    
    // 第二层：在技术部下添加子部门
    echo "2. 在技术部下添加【研发组】...\n";
    $devDept = $client->addDept('研发组', $techDept['id']);
    echo "   ✅ 创建成功，ID: {$devDept['id']}\n\n";
    sleep(1);
    
    echo "3. 在技术部下添加【测试组】...\n";
    $testDept = $client->addDept('测试组', $techDept['id']);
    echo "   ✅ 创建成功，ID: {$testDept['id']}\n\n";
    sleep(1);
    
    // 第三层：在研发组下继续添加子部门
    echo "4. 在研发组下添加【前端团队】...\n";
    $frontendDept = $client->addDept('前端团队', $devDept['id']);
    echo "   ✅ 创建成功，ID: {$frontendDept['id']}\n\n";
    sleep(1);
    
    echo "5. 在研发组下添加【后端团队】...\n";
    $backendDept = $client->addDept('后端团队', $devDept['id']);
    echo "   ✅ 创建成功，ID: {$backendDept['id']}\n\n";
    
    echo "✅ 部门树结构创建完成！\n";
    echo "结构如下：\n";
    echo "技术部 (ID: {$techDept['id']})\n";
    echo "├── 研发组 (ID: {$devDept['id']})\n";
    echo "│   ├── 前端团队 (ID: {$frontendDept['id']})\n";
    echo "│   └── 后端团队 (ID: {$backendDept['id']})\n";
    echo "└── 测试组 (ID: {$testDept['id']})\n";
    */

    // ===== 使用说明 =====
    echo "\n=== 使用说明 ===\n";
    echo "1. pid（上级部门ID）说明：\n";
    echo "   - 创建顶级部门：不传 pid 或 pid = 学校ID（SID）\n";
    echo "   - 创建子部门：pid = 上级部门的 id\n\n";
    
    echo "2. 获取上级部门ID的方法：\n";
    echo "   - 使用 getDepartments() 方法获取所有部门\n";
    echo "   - 从返回结果中找到目标部门的 id 字段\n\n";
    
    echo "3. 返回值说明：\n";
    echo "   - id: 新建部门的ID（重要，用于创建子部门或分配人员）\n";
    echo "   - name: 部门名称\n";
    echo "   - pid: 上级部门ID\n";
    echo "   - teamId: 所属学校ID\n\n";
    
    echo "4. 注意事项：\n";
    echo "   - 部门名称不能为空\n";
    echo "   - 建议部门名称具有描述性，便于管理\n";
    echo "   - 创建多个部门时建议间隔1秒，避免频率过快\n";
    echo "   - 记录返回的部门ID，用于后续操作\n";

} catch (\InvalidArgumentException $e) {
    echo "\n❌ 参数错误: " . $e->getMessage() . "\n";
    echo "\n💡 提示：请检查以下内容：\n";
    echo "1. deptName（部门名称）不能为空\n";
    echo "2. SID（学校编号）必须已配置\n";
    echo "3. pid（上级部门ID）必须是有效的部门ID或学校ID\n";
    
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
    if (strpos($e->getMessage(), '部门') !== false) {
        echo "\n💡 提示: 部门操作失败，可能的原因：\n";
        echo "1. 部门名称已存在\n";
        echo "2. 上级部门ID不存在或无效\n";
        echo "3. 没有操作该部门的权限\n";
    }
}

