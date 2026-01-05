<?php

/**
 * 修改人员信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * ⚠️ 重要提示：
 * 1. 为了保证数据处理的有效性，每次调用接口，请最少间隔一秒
 * 2. 建议不要直接删除人员，直接删除人员后设备内的人员不会被删除（除非设备进行清除人员信息重新下载）
 * 3. 建议不要的人员更新设置为停用（roleIdList设为[0]），只有停用的人员，设备上才会生效并阻止停用人员继续正常使用
 * 4. 此接口为异步接口，如果对时效性有要求，请使用同步更新卡号接口
 * 5. 由于接口调用会触发平台、前置、设备进行人员出入库操作（此操作非常耗时），这个期间用户是无法使用的
 * 6. 只有在用户真实发生信息变更的时候才调用此接口，禁止无效调用，否则会造成用户无法正常使用
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

    // ===== 示例1：修改人员基本信息 =====
    echo "=== 示例1：修改人员基本信息 ===\n";
    echo "⚠️  注意：此示例已注释，请先获取实际的人员ID后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $updateData = [
        // 必填字段
        'id'              => 483307,                           // 人员编号（必须是已存在的人员ID）
        'studentName'     => 'yqsh',                           // 姓名
        'deptList'        => ['157959913636118'],              // 所属部门（建议一个人员只在一个部门内）
        'job'             => '测试',                           // 职位
        'email'           => '303740339@qq.com',               // 电子邮箱
        'roleIdList'      => [7],                              // 所属角色：5-教师，7-学生
        'idCard'          => '511024198808230004',             // 身份证
        'phone'           => '17311327518',                    // 手机号码
        'comeSchoolTime'  => '2020-01-01',                     // 入校时间，格式：yyyy-MM-dd
        'remarke'         => '测试用例',                       // 备注
        'sex'             => 1,                                // 性别：1-男，2-女
        'sub_type'        => 1,                                // 类型：1-走读，2-住读，3-半住读，4-半走读，5-通用
        'couponSelected'  => 2,                                // 状态：默认1
        
        // 可选字段
        'parentName'      => '家长姓名',                       // 父（母）姓名
        'parentPhone'     => '17311327518',                    // 父（母）电话
        'parentIdCard'    => '511024198808240004',             // 父（母）身份证
        // 'phyCard'      => '000000000',                      // 物理卡号，首位去0（异步接口，如需时效性请使用同步更新卡号接口）
        // 'photo'        => 'base64/image:...',               // 图片base64（已废弃，建议使用同步更新照片接口）
    ];

    echo "正在修改人员信息...\n";
    echo "人员ID: {$updateData['id']}\n";
    echo "姓名: {$updateData['studentName']}\n";
    
    $userId = $client->updatePerson($updateData);
    
    echo "✅ 修改成功！\n";
    echo "用户ID: {$userId}\n";
    */

    // ===== 示例2：停用人员（设置为停用状态） =====
    echo "\n=== 示例2：停用人员（设置为停用状态） ===\n";
    echo "⚠️  注意：此示例已注释，请先获取实际的人员ID后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 重要提示：建议不要直接删除人员，而是将人员设置为停用状态
    // 停用人员的方式：将 roleIdList 设置为 [0]
    $disablePersonData = [
        'id'              => 483307,                           // 人员编号
        'studentName'     => 'yqsh',
        'deptList'        => ['157959913636118'],
        'job'             => '测试',
        'email'           => '303740339@qq.com',
        'roleIdList'      => [0],                              // 0 表示停用
        'idCard'          => '511024198808230004',
        'phone'           => '17311327518',
        'comeSchoolTime'  => '2020-01-01',
        'remarke'         => '已停用',
        'sex'             => 1,
        'sub_type'        => 1,
        'couponSelected'  => 1,                                // 状态设为1（离职）
    ];

    echo "正在停用人员...\n";
    echo "人员ID: {$disablePersonData['id']}\n";
    echo "姓名: {$disablePersonData['studentName']}\n";
    echo "操作: 设置为停用状态（roleIdList=[0]）\n";
    
    $userId = $client->updatePerson($disablePersonData);
    
    echo "✅ 停用成功！\n";
    echo "用户ID: {$userId}\n";
    echo "💡 提示: 停用的人员，设备上才会生效并阻止其继续使用\n";
    */

    // ===== 示例3：修改人员部门 =====
    echo "\n=== 示例3：修改人员部门 ===\n";
    echo "⚠️  注意：此示例已注释，请先获取实际的人员ID和部门ID后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $changeDeptData = [
        'id'              => 483307,                           // 人员编号
        'studentName'     => 'yqsh',
        'deptList'        => ['155610797538818'],              // 修改为新的部门ID
        'job'             => '测试',
        'email'           => '303740339@qq.com',
        'roleIdList'      => [7],
        'idCard'          => '511024198808230004',
        'phone'           => '17311327518',
        'comeSchoolTime'  => '2020-01-01',
        'remarke'         => '已调整部门',
        'sex'             => 1,
        'sub_type'        => 1,
        'couponSelected'  => 2,
    ];

    echo "正在修改人员部门...\n";
    echo "人员ID: {$changeDeptData['id']}\n";
    echo "新部门ID: " . implode(',', $changeDeptData['deptList']) . "\n";
    
    $userId = $client->updatePerson($changeDeptData);
    
    echo "✅ 修改成功！\n";
    echo "用户ID: {$userId}\n";
    */

    // ===== 示例4：获取人员信息后再修改 =====
    echo "\n=== 示例4：获取人员信息后再修改（推荐方式） ===\n";
    echo "⚠️  注意：此示例演示了如何先查询人员信息，再进行修改\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    // 第一步：通过身份证查询人员信息
    $idCard = '511024198808230004';
    echo "正在查询人员信息（身份证：{$idCard}）...\n";
    
    $personInfo = $client->queryAccountByIdcard($idCard);
    
    if (empty($personInfo)) {
        echo "未找到该人员信息\n";
    } else {
        echo "找到人员：\n";
        print_r($personInfo);
        
        // 第二步：基于查询到的信息进行修改
        // 注意：只修改需要变更的字段，其他字段保持原值
        $updateData = [
            'id'              => $personInfo['id'],
            'studentName'     => $personInfo['cust_name'],
            'deptList'        => [$personInfo['deptId']],         // 保持原部门
            'job'             => '新职位',                         // 修改职位
            'email'           => $personInfo['email'] ?? '',
            'roleIdList'      => [$personInfo['cust_type']],
            'idCard'          => $personInfo['idcard'],
            'phone'           => $personInfo['mobilephone'],
            'comeSchoolTime'  => '',
            'remarke'         => '更新了职位信息',                  // 修改备注
            'sex'             => $personInfo['sex'],
            'sub_type'        => 1,
            'couponSelected'  => 2,
        ];
        
        echo "\n正在修改人员信息...\n";
        sleep(1); // 间隔1秒，避免频率过快
        
        $userId = $client->updatePerson($updateData);
        
        echo "✅ 修改成功！\n";
        echo "用户ID: {$userId}\n";
    }
    */

    // ===== 注意事项 =====
    echo "\n=== 重要注意事项 ===\n";
    echo "1. 每次调用接口至少间隔 1秒，避免频率过快\n";
    echo "2. 建议不要直接删除人员，而是将 roleIdList 设置为 [0] 停用人员\n";
    echo "3. 停用的人员，设备上才会生效并阻止其继续使用\n";
    echo "4. 只有在用户真实发生信息变更时才调用此接口，禁止无效调用\n";
    echo "5. 接口调用会触发平台、前置、设备进行人员出入库操作，非常耗时\n";
    echo "6. 在人员出入库操作期间，用户可能无法正常使用\n";
    echo "7. 物理卡号修改建议使用同步更新卡号接口，以保证时效性\n";
    echo "8. 照片更新建议使用同步更新照片接口，photo字段即将废弃\n";

} catch (\InvalidArgumentException $e) {
    echo "\n❌ 参数错误: " . $e->getMessage() . "\n";
    echo "\n💡 提示：请检查以下内容：\n";
    echo "1. id（人员编号）必须是已存在的有效人员ID\n";
    echo "2. 所有必填字段是否都已填写\n";
    echo "3. deptList 和 roleIdList 必须是数组格式\n";
    echo "4. sex 只能是 1（男）或 2（女）\n";
    echo "5. sub_type 只能是 1-5 之间的值\n";
    echo "6. comeSchoolTime 格式必须是 yyyy-MM-dd（或空字符串）\n";
    echo "7. roleIdList 设置为 [0] 可以停用人员\n";
    
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
        echo "2. 使用 queryAccountByIdcard() 方法通过身份证查询人员信息\n";
        echo "3. 使用 getUsers() 方法获取人员列表\n";
    }
}

