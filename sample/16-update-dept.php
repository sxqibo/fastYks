<?php

/**
 * 修改部门信息示例
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

    // ===== 示例1：修改部门名称 =====
    echo "=== 示例1：修改部门名称 ===\n";
    echo "⚠️  注意：此示例已注释，请先获取实际的部门ID后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $deptId = '15952281722262157';        // 要修改的部门ID
    $newDeptName = '研发部门工作群';       // 新的部门名称
    $pid = '14';                          // 上级部门ID（保持不变）
    
    echo "正在修改部门名称...\n";
    echo "部门ID: {$deptId}\n";
    echo "新名称: {$newDeptName}\n";
    echo "上级部门ID: {$pid}\n";
    
    $result = $client->updateDept($deptId, $newDeptName, $pid);
    
    echo "✅ 修改成功！\n";
    print_r($result);
    */

    // ===== 示例2：调整部门层级（移动到新的上级部门） =====
    echo "\n=== 示例2：调整部门层级（移动到新的上级部门） ===\n";
    echo "⚠️  注意：此示例已注释，请先获取实际的部门ID后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $deptId = '15952281722262157';        // 要调整的部门ID
    $deptName = '研发组';                 // 部门名称（保持不变）
    $newPid = '155610795603218';          // 新的上级部门ID
    
    echo "正在调整部门层级...\n";
    echo "部门ID: {$deptId}\n";
    echo "部门名称: {$deptName}\n";
    echo "新上级部门ID: {$newPid}\n";
    echo "说明: 将该部门移动到新的上级部门下\n";
    
    $result = $client->updateDept($deptId, $deptName, $newPid);
    
    echo "✅ 调整成功！\n";
    print_r($result);
    */

    // ===== 示例3：获取部门信息后再修改（推荐方式） =====
    echo "\n=== 示例3：获取部门信息后再修改（推荐方式） ===\n";
    echo "⚠️  注意：此示例演示了如何先查询部门信息，再进行修改\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 第一步：获取所有部门信息
    echo "第一步：获取部门列表...\n";
    $departments = $client->getDepartments();
    
    if (empty($departments)) {
        echo "暂无部门信息\n";
    } else {
        echo "找到 " . count($departments) . " 个部门：\n";
        foreach ($departments as $index => $dept) {
            if ($index >= 5) {  // 只显示前5个
                echo "  ... 还有 " . (count($departments) - 5) . " 个部门\n";
                break;
            }
            echo "  - {$dept['title']} (ID: {$dept['id']}, 上级ID: {$dept['parentId']})\n";
        }
        
        // 第二步：选择要修改的部门（这里选择第一个）
        $targetDept = $departments[0];
        echo "\n第二步：选择要修改的部门...\n";
        echo "目标部门: {$targetDept['title']} (ID: {$targetDept['id']})\n";
        
        // 第三步：修改部门信息
        echo "\n第三步：修改部门信息...\n";
        $newDeptName = $targetDept['title'] . '_已更新';
        echo "新部门名称: {$newDeptName}\n";
        
        $result = $client->updateDept(
            $targetDept['id'],
            $newDeptName,
            $targetDept['parentId']
        );
        
        echo "✅ 修改成功！\n";
        print_r($result);
        
        // 第四步：验证修改结果
        echo "\n第四步：验证修改结果...\n";
        sleep(1);  // 等待1秒
        $updatedDepts = $client->getDepartments();
        foreach ($updatedDepts as $dept) {
            if ($dept['id'] === $targetDept['id']) {
                echo "验证成功！新部门名称: {$dept['title']}\n";
                break;
            }
        }
    }
    */

    // ===== 示例4：批量修改部门信息 =====
    echo "\n=== 示例4：批量修改部门信息 ===\n";
    echo "⚠️  注意：此示例演示了如何批量修改多个部门\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 要修改的部门列表
    $updateList = [
        [
            'deptId'   => '15952281722262157',
            'deptName' => '研发一部',
            'pid'      => '14'
        ],
        [
            'deptId'   => '15952281722262158',
            'deptName' => '研发二部',
            'pid'      => '14'
        ],
        [
            'deptId'   => '15952281722262159',
            'deptName' => '研发三部',
            'pid'      => '14'
        ],
    ];
    
    echo "正在批量修改 " . count($updateList) . " 个部门...\n\n";
    
    foreach ($updateList as $index => $updateData) {
        echo "修改第 " . ($index + 1) . " 个部门...\n";
        echo "  部门ID: {$updateData['deptId']}\n";
        echo "  新名称: {$updateData['deptName']}\n";
        
        try {
            $result = $client->updateDept(
                $updateData['deptId'],
                $updateData['deptName'],
                $updateData['pid']
            );
            echo "  ✅ 修改成功\n\n";
        } catch (\Exception $e) {
            echo "  ❌ 修改失败: " . $e->getMessage() . "\n\n";
        }
        
        // 间隔1秒，避免频率过快
        if ($index < count($updateList) - 1) {
            sleep(1);
        }
    }
    
    echo "✅ 批量修改完成！\n";
    */

    // ===== 示例5：重新组织部门结构 =====
    echo "\n=== 示例5：重新组织部门结构 ===\n";
    echo "⚠️  注意：此示例演示了如何重新组织部门层级关系\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 假设原来的部门结构：
    // 学校
    // ├── 部门A
    // └── 部门B
    //     └── 部门C
    
    // 目标：将部门C从部门B移到部门A下
    // 新结构：
    // 学校
    // ├── 部门A
    // │   └── 部门C (移动到这里)
    // └── 部门B
    
    $deptCId = '15952281722262159';       // 部门C的ID
    $deptCName = '部门C';                 // 部门C的名称
    $newParentId = '15952281722262157';   // 部门A的ID（新的上级部门）
    
    echo "正在重新组织部门结构...\n";
    echo "将【{$deptCName}】从当前上级部门移动到新上级部门\n";
    echo "部门C ID: {$deptCId}\n";
    echo "新上级部门ID: {$newParentId}\n";
    
    $result = $client->updateDept($deptCId, $deptCName, $newParentId);
    
    echo "✅ 部门结构调整成功！\n";
    print_r($result);
    */

    // ===== 使用说明 =====
    echo "\n=== 使用说明 ===\n";
    echo "1. 参数说明：\n";
    echo "   - deptId: 要修改的部门ID（必填）\n";
    echo "   - deptName: 新的部门名称（必填）\n";
    echo "   - pid: 上级部门ID（必填，可用于调整部门层级）\n\n";
    
    echo "2. 常见使用场景：\n";
    echo "   - 修改部门名称：只改 deptName，pid 保持不变\n";
    echo "   - 调整部门层级：改变 pid 值，将部门移到新的上级部门下\n";
    echo "   - 同时修改名称和层级：同时修改 deptName 和 pid\n\n";
    
    echo "3. 获取部门ID的方法：\n";
    echo "   - 使用 getDepartments() 方法获取所有部门\n";
    echo "   - 从返回结果中找到目标部门的 id 和 parentId 字段\n\n";
    
    echo "4. 注意事项：\n";
    echo "   - 所有参数都不能为空\n";
    echo "   - deptId 必须是已存在的部门ID\n";
    echo "   - pid 必须是有效的部门ID或学校ID\n";
    echo "   - 修改多个部门时建议间隔1秒，避免频率过快\n";
    echo "   - 修改后可以通过 getDepartments() 验证修改结果\n";
    echo "   - 调整部门层级时要注意避免形成循环引用（如A是B的上级，不能把A移到B下）\n";

} catch (\InvalidArgumentException $e) {
    echo "\n❌ 参数错误: " . $e->getMessage() . "\n";
    echo "\n💡 提示：请检查以下内容：\n";
    echo "1. deptId（部门ID）不能为空\n";
    echo "2. deptName（部门名称）不能为空\n";
    echo "3. pid（上级部门ID）不能为空\n";
    echo "4. SID（学校编号）必须已配置\n";
    
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
    if (strpos($e->getMessage(), '部门') !== false || strpos($e->getMessage(), '不存在') !== false) {
        echo "\n💡 提示: 部门操作失败，可能的原因：\n";
        echo "1. 部门ID不存在或已被删除\n";
        echo "2. 上级部门ID不存在或无效\n";
        echo "3. 没有操作该部门的权限\n";
        echo "4. 部门名称与已有部门重复\n";
        echo "5. 尝试将部门移动到自己或其子部门下（循环引用）\n";
        echo "\n建议：\n";
        echo "1. 先通过 getDepartments() 查询确认部门是否存在\n";
        echo "2. 验证上级部门ID是否有效\n";
        echo "3. 确保不会形成循环引用的部门结构\n";
    }
}

