<?php

/**
 * 删除部门信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * ⚠️ 重要提示：
 * 1. 只有当部门下没有子部门或者人员时，才能成功删除
 * 2. 如果部门下还有子部门或人员，删除会失败
 * 3. 建议删除前先检查部门下是否还有子部门或人员
 * 4. 删除操作不可逆，请谨慎操作
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

    // ===== 示例1：删除单个部门 =====
    echo "=== 示例1：删除单个部门 ===\n";
    echo "⚠️  注意：此示例已注释，请先确认部门下没有子部门或人员后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $deptId = '15952281722262157';  // 要删除的部门ID

    echo "正在删除部门...\n";
    echo "部门ID: {$deptId}\n";
    echo "⚠️  警告: 只有当部门下没有子部门或人员时，才能成功删除\n";
    
    // 确认提示
    echo "\n是否确认删除？请取消下面的注释继续：\n";
    // $result = $client->deleteDept($deptId);
    
    // echo "✅ 删除成功！\n";
    // print_r($result);
    */

    // ===== 示例2：批量删除多个部门 =====
    echo "\n=== 示例2：批量删除多个部门 ===\n";
    echo "⚠️  注意：此示例已注释，请先确认这些部门下都没有子部门或人员后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $deptIds = ['15952281722262157', '15952281722262158'];  // 要删除的部门ID数组

    echo "正在批量删除部门...\n";
    echo "部门ID列表: " . implode(', ', $deptIds) . "\n";
    echo "删除数量: " . count($deptIds) . "\n";
    echo "⚠️  警告: 只有当这些部门下都没有子部门或人员时，才能成功删除\n";
    
    // 确认提示
    echo "\n是否确认批量删除？请取消下面的注释继续：\n";
    // $result = $client->deleteDept($deptIds);
    
    // echo "✅ 批量删除成功！\n";
    // print_r($result);
    */

    // ===== 示例3：安全删除流程（推荐） =====
    echo "\n=== 示例3：安全删除流程（推荐） ===\n";
    echo "先检查部门状态，再决定是否删除\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $targetDeptId = '15952281722262157';
    
    // 第一步：获取所有部门信息
    echo "第一步：获取部门列表，检查部门关系...\n";
    $departments = $client->getDepartments();
    
    $targetDept = null;
    $hasChildren = false;
    
    // 查找目标部门
    foreach ($departments as $dept) {
        if ($dept['id'] === $targetDeptId) {
            $targetDept = $dept;
        }
        // 检查是否有子部门
        if (isset($dept['parentId']) && $dept['parentId'] === $targetDeptId) {
            $hasChildren = true;
            echo "  ⚠️  发现子部门: {$dept['title']} (ID: {$dept['id']})\n";
        }
    }
    
    if (!$targetDept) {
        echo "❌ 部门不存在或已被删除\n";
    } else {
        echo "找到目标部门: {$targetDept['title']} (ID: {$targetDept['id']})\n\n";
        
        // 第二步：检查是否可以删除
        echo "第二步：检查是否可以删除...\n";
        
        if ($hasChildren) {
            echo "❌ 无法删除：该部门下还有子部门\n";
            echo "建议：先删除或移动所有子部门，再删除该部门\n";
        } else {
            echo "✅ 该部门下没有子部门\n";
            echo "⚠️  注意：还需要确认该部门下没有人员\n";
            echo "建议：通过人员管理系统确认该部门下没有人员后再删除\n";
            
            // 第三步：执行删除（需要确认该部门下也没有人员）
            echo "\n第三步：执行删除...\n";
            echo "如果确认该部门下也没有人员，取消下面的注释继续删除：\n";
            // $result = $client->deleteDept($targetDeptId);
            // echo "✅ 删除成功！\n";
            // print_r($result);
        }
    }
    */

    // ===== 示例4：清空部门树结构（从下往上删除） =====
    echo "\n=== 示例4：清空部门树结构（从下往上删除） ===\n";
    echo "⚠️  注意：此示例演示了如何正确删除整个部门树\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 假设有如下部门结构：
    // 技术部
    // ├── 研发组
    // │   ├── 前端团队
    // │   └── 后端团队
    // └── 测试组
    
    // 正确的删除顺序：从最底层开始，逐层向上删除
    $deletionOrder = [
        ['id' => '前端团队ID', 'name' => '前端团队'],
        ['id' => '后端团队ID', 'name' => '后端团队'],
        ['id' => '研发组ID', 'name' => '研发组'],
        ['id' => '测试组ID', 'name' => '测试组'],
        ['id' => '技术部ID', 'name' => '技术部'],
    ];
    
    echo "正在清空部门树结构...\n";
    echo "删除顺序（从下往上）：\n";
    foreach ($deletionOrder as $index => $dept) {
        echo ($index + 1) . ". {$dept['name']}\n";
    }
    echo "\n";
    
    foreach ($deletionOrder as $index => $dept) {
        echo "删除第 " . ($index + 1) . " 个部门: {$dept['name']}...\n";
        
        try {
            $result = $client->deleteDept($dept['id']);
            echo "  ✅ 删除成功\n";
        } catch (\Exception $e) {
            echo "  ❌ 删除失败: " . $e->getMessage() . "\n";
            echo "  提示: 可能该部门下还有人员，请先处理人员后再删除\n";
        }
        
        // 间隔1秒
        if ($index < count($deletionOrder) - 1) {
            sleep(1);
        }
        echo "\n";
    }
    
    echo "✅ 部门树清空完成！\n";
    */

    // ===== 示例5：获取可删除的部门列表 =====
    echo "\n=== 示例5：获取可删除的部门列表 ===\n";
    echo "⚠️  注意：此示例演示了如何找出所有可以安全删除的部门\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    echo "正在分析部门结构...\n";
    $departments = $client->getDepartments();
    
    // 统计每个部门的子部门数量
    $childrenCount = [];
    foreach ($departments as $dept) {
        if (isset($dept['parentId'])) {
            $parentId = $dept['parentId'];
            if (!isset($childrenCount[$parentId])) {
                $childrenCount[$parentId] = 0;
            }
            $childrenCount[$parentId]++;
        }
    }
    
    echo "\n可以删除的部门（没有子部门的部门）：\n";
    $deletableDepts = [];
    foreach ($departments as $dept) {
        $deptId = $dept['id'];
        $hasChildren = isset($childrenCount[$deptId]) && $childrenCount[$deptId] > 0;
        
        if (!$hasChildren) {
            $deletableDepts[] = $dept;
            echo "  - {$dept['title']} (ID: {$deptId})\n";
            echo "    ⚠️  注意：还需确认该部门下没有人员\n";
        }
    }
    
    if (empty($deletableDepts)) {
        echo "  没有找到可以直接删除的部门（所有部门都有子部门）\n";
    } else {
        echo "\n找到 " . count($deletableDepts) . " 个可能可以删除的部门\n";
        echo "删除前请确认这些部门下都没有人员\n";
    }
    */

    // ===== 重要说明 =====
    echo "\n=== 重要说明 ===\n";
    echo "【删除部门的前提条件】\n";
    echo "1. 部门下没有子部门\n";
    echo "2. 部门下没有人员\n";
    echo "3. 只有同时满足以上两个条件，才能成功删除\n\n";
    
    echo "【删除失败的常见原因】\n";
    echo "1. 部门下还有子部门：需要先删除或移动所有子部门\n";
    echo "2. 部门下还有人员：需要先删除或移动所有人员到其他部门\n";
    echo "3. 部门ID不存在：部门可能已被删除或ID错误\n";
    echo "4. 权限不足：SP号可能没有删除部门的权限\n\n";
    
    echo "【安全删除流程】\n";
    echo "第一步：使用 getDepartments() 获取部门列表\n";
    echo "第二步：检查目标部门是否有子部门\n";
    echo "第三步：确认目标部门下没有人员（通过人员管理系统）\n";
    echo "第四步：如果都满足，再调用 deleteDept() 删除\n";
    echo "第五步：删除后验证结果\n\n";
    
    echo "【删除部门树的正确顺序】\n";
    echo "1. 从最底层的叶子部门开始删除\n";
    echo "2. 逐层向上，删除上级部门\n";
    echo "3. 最后删除顶级部门\n";
    echo "4. 每删除一个部门，间隔1秒\n\n";
    
    echo "【注意事项】\n";
    echo "1. 删除操作不可逆，请谨慎操作\n";
    echo "2. 建议在删除前做好数据备份\n";
    echo "3. 批量删除时建议间隔1秒，避免频率过快\n";
    echo "4. 删除后建议验证结果，确认删除成功\n";

} catch (\InvalidArgumentException $e) {
    echo "\n❌ 参数错误: " . $e->getMessage() . "\n";
    echo "\n💡 提示：请检查以下内容：\n";
    echo "1. deptId（部门ID）不能为空\n";
    echo "2. deptId 可以是单个ID或ID数组\n";
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
    if (strpos($e->getMessage(), '部门') !== false) {
        echo "\n💡 提示: 部门删除失败，可能的原因：\n";
        echo "1. 部门下还有子部门，需要先删除或移动子部门\n";
        echo "2. 部门下还有人员，需要先删除或移动人员到其他部门\n";
        echo "3. 部门ID不存在或已被删除\n";
        echo "4. 没有删除该部门的权限\n";
        echo "\n建议：\n";
        echo "1. 使用 getDepartments() 检查部门结构\n";
        echo "2. 确认部门下没有子部门和人员\n";
        echo "3. 从最底层的叶子部门开始删除\n";
    }
}

