<?php

/**
 * 删除人员信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * ⚠️ 重要警告：
 * 1. 建议不要直接删除人员，直接删除人员后设备内的人员不会被删除（除非设备进行清除人员信息重新下载）
 * 2. 这些人员仍然可以在设备内进行正常操作，存在安全隐患
 * 3. 强烈建议需要删除人员时优先把人员状态更新设置为停用（将 roleIdList 设为 [0]）
 * 4. 只有停用的人员，设备上才会生效并阻止停用人员继续正常使用
 * 5. 如果确实需要删除，请确保已经先将人员设置为停用状态
 */

// 自动加载：优先使用 composer，如果没有则使用简单的 bootstrap
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    require_once __DIR__ . '/bootstrap.php';
}

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Person;

// 方式1：从环境变量加载配置（推荐）
Config::loadFromEnv(dirname(__DIR__));

// 方式2：直接设置配置
// Config::set(['sp' => 'your_sp_number', 'sid' => 6657]);

try {
    // 创建 Person 客户端
    // 方式A：使用配置中的默认值（推荐）
    $client = new Person();
    
    // 方式B：手动传入 SP 号和 SID
    // $client = new Person('your_sp_number_here', 6657);

    // 调试信息：显示当前使用的配置
    $currentConfig = Config::get();
    echo "=== 当前配置信息 ===\n";
    echo "SP: " . ($currentConfig['sp'] ?? '未设置') . "\n";
    echo "SID: " . ($currentConfig['sid'] ?? '未设置') . "\n";
    echo "URL: " . ($currentConfig['url'] ?? '未设置') . "\n";
    echo "==================\n\n";

    // ===== 推荐方式：停用人员而非删除 =====
    echo "=== 推荐方式：停用人员（而非删除） ===\n";
    echo "⚠️  强烈建议：不要直接删除人员，而是将其设置为停用状态\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 推荐做法：使用 updatePerson 将人员设置为停用状态
    $disablePersonData = [
        'id'              => 483307,                           // 人员编号
        'studentName'     => '待停用人员',
        'deptList'        => ['157959913636118'],
        'job'             => '测试',
        'email'           => 'test@example.com',
        'roleIdList'      => [0],                              // 0 表示停用（重要）
        'idCard'          => '511024198808230004',
        'phone'           => '17311327518',
        'comeSchoolTime'  => '2020-01-01',
        'remarke'         => '已停用，不再使用',
        'sex'             => 1,
        'sub_type'        => 1,
        'couponSelected'  => 1,                                // 状态设为1（离职）
    ];

    echo "正在停用人员...\n";
    echo "人员ID: {$disablePersonData['id']}\n";
    echo "操作: 设置为停用状态（roleIdList=[0]）\n";
    
    $userId = $client->updatePerson($disablePersonData);
    
    echo "✅ 停用成功！\n";
    echo "用户ID: {$userId}\n";
    echo "💡 提示: 停用的人员，设备上才会生效并阻止其继续使用\n";
    */

    // ===== 示例1：删除单个人员（不推荐） =====
    echo "\n=== 示例1：删除单个人员（不推荐） ===\n";
    echo "⚠️  警告：此操作不推荐使用，仅作为示例展示\n";
    echo "⚠️  建议先将人员设置为停用状态后再考虑删除\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $personId = 483307;  // 要删除的人员ID

    echo "正在删除人员...\n";
    echo "人员ID: {$personId}\n";
    echo "⚠️  警告: 删除后设备内的人员不会被删除，可能存在安全隐患\n";
    
    // 确认提示
    echo "\n是否确认删除？建议先停用而非删除。\n";
    echo "如需继续删除，请取消下面的注释：\n";
    // $result = $client->deletePerson($personId);
    
    // echo "✅ 删除成功！\n";
    // print_r($result);
    */

    // ===== 示例2：批量删除多个人员（不推荐） =====
    echo "\n=== 示例2：批量删除多个人员（不推荐） ===\n";
    echo "⚠️  警告：此操作不推荐使用，仅作为示例展示\n";
    echo "⚠️  建议先将人员设置为停用状态后再考虑删除\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $personIds = [1024, 1535];  // 要删除的人员ID数组

    echo "正在批量删除人员...\n";
    echo "人员ID列表: " . implode(', ', $personIds) . "\n";
    echo "删除人数: " . count($personIds) . "\n";
    echo "⚠️  警告: 删除后设备内的人员不会被删除，可能存在安全隐患\n";
    
    // 确认提示
    echo "\n是否确认批量删除？建议先批量停用而非删除。\n";
    echo "如需继续删除，请取消下面的注释：\n";
    // $result = $client->deletePerson($personIds);
    
    // echo "✅ 批量删除成功！\n";
    // print_r($result);
    */

    // ===== 示例3：安全删除流程（推荐） =====
    echo "\n=== 示例3：安全删除流程（推荐） ===\n";
    echo "先停用，再删除（如确实需要删除）\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $personId = 483307;
    
    // 第一步：先查询人员信息，确认是否存在
    echo "第一步：查询人员信息...\n";
    $personInfo = $client->queryAccountInfo($personId);
    
    if (empty($personInfo)) {
        echo "人员不存在，无需删除\n";
    } else {
        echo "找到人员：{$personInfo['cust_name']}\n\n";
        
        // 第二步：将人员设置为停用状态
        echo "第二步：将人员设置为停用状态...\n";
        $disableData = [
            'id'              => $personId,
            'studentName'     => $personInfo['cust_name'],
            'deptList'        => [$personInfo['deptId']],
            'job'             => $personInfo['job'] ?? '',
            'email'           => $personInfo['email'] ?? '',
            'roleIdList'      => [0],                          // 停用
            'idCard'          => $personInfo['idcard'],
            'phone'           => $personInfo['mobilephone'],
            'comeSchoolTime'  => '',
            'remarke'         => '已停用，准备删除',
            'sex'             => $personInfo['sex'],
            'sub_type'        => 1,
            'couponSelected'  => 1,
        ];
        
        $userId = $client->updatePerson($disableData);
        echo "✅ 停用成功！用户ID: {$userId}\n\n";
        
        // 第三步：等待一段时间后再删除（可选）
        echo "第三步：等待设备同步停用状态...\n";
        echo "建议等待至少30分钟，确保设备已同步停用状态\n";
        sleep(2); // 示例中只等待2秒，实际应等待更长时间
        
        // 第四步：如果确实需要删除，再执行删除操作
        echo "第四步：执行删除操作...\n";
        echo "⚠️  最后确认：是否确认删除？取消下面的注释继续\n";
        // $result = $client->deletePerson($personId);
        // echo "✅ 删除成功！\n";
        // print_r($result);
    }
    */

    // ===== 重要说明 =====
    echo "\n=== 重要说明 ===\n";
    echo "【为什么不推荐直接删除人员？】\n";
    echo "1. 直接删除只会从平台数据库删除，设备内的人员不会被删除\n";
    echo "2. 设备内的人员仍然可以正常使用门禁、消费等功能\n";
    echo "3. 这会造成安全隐患，已删除的人员仍可继续使用\n";
    echo "4. 除非设备进行「清除人员信息重新下载」，否则设备内的人员会一直存在\n\n";
    
    echo "【推荐的处理方式】\n";
    echo "1. 使用 updatePerson() 方法将 roleIdList 设置为 [0]（停用）\n";
    echo "2. 停用状态会同步到设备，设备会阻止该人员继续使用\n";
    echo "3. 如果确实需要删除，建议先停用，等待设备同步后再删除\n";
    echo "4. 删除前务必确认该人员已在所有设备上停用\n\n";
    
    echo "【安全删除流程】\n";
    echo "第一步：使用 updatePerson() 将人员设置为停用状态（roleIdList=[0]）\n";
    echo "第二步：等待至少30分钟，确保所有设备已同步停用状态\n";
    echo "第三步：验证该人员在设备上已无法使用\n";
    echo "第四步：如确实需要删除，再调用 deletePerson() 方法\n";

} catch (\InvalidArgumentException $e) {
    echo "\n❌ 参数错误: " . $e->getMessage() . "\n";
    echo "\n💡 提示：请检查以下内容：\n";
    echo "1. personId（人员ID）不能为空\n";
    echo "2. personId 可以是单个ID或ID数组\n";
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
    
    // 如果是人员不存在错误
    if (strpos($e->getMessage(), '人员') !== false || strpos($e->getMessage(), '不存在') !== false) {
        echo "\n💡 提示: 人员ID可能不存在或已被删除。\n";
        echo "建议：\n";
        echo "1. 先通过查询接口确认人员ID是否存在\n";
        echo "2. 使用 queryAccountInfo() 方法查询人员信息\n";
        echo "3. 使用 getUsers() 方法获取人员列表\n";
    }
}

