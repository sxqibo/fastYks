<?php

/**
 * 添加人员信息示例
 * 
 * 使用说明：
 * 1. 通过 composer 安装: composer require sxqibo/fast-yks
 * 2. 配置你的 SP 号和 SID（学校编号）
 * 3. 运行此示例
 * 
 * ⚠️ 重要提示：
 * 1. 为了保证数据处理的有效性，每次调用接口，请最少间隔一秒
 * 2. 此接口为异步接口，如果对时效性有要求，建议物理卡号参数（phyCard）使用同步更新卡号接口
 * 3. 证件号idCard为主字段，如果idCard一致，平台不会新增人员会直接返回已有人员ID号
 * 4. 如果物理卡号没有传入此接口，仅进行账号添加操作，不会进行开卡操作
 * 5. 如果需要开卡但暂无物理卡号，建议传入xn开头的卡号，后期可以通过接口更换卡号
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

    // ===== 示例1：添加学生信息（完整字段） =====
    echo "=== 示例1：添加学生信息（完整字段） ===\n";
    echo "⚠️  注意：此示例已注释，请根据实际情况修改身份证号后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $studentData = [
        // 必填字段
        'studentName'     => 'yqsh',                           // 姓名
        'deptList'        => ['157959913636118'],              // 所属部门（建议一个人员只在一个部门内）
        'job'             => '测试',                           // 职位
        'email'           => '303740339@qq.com',               // 电子邮箱
        'roleIdList'      => [7],                              // 所属角色：5-教师，7-学生
        'idCard'          => '511024198808230999',             // 身份证（主字段，唯一标识）- 请修改为未使用的身份证
        'phone'           => '17311327518',                    // 手机号码
        'comeSchoolTime'  => '2020-01-01',                     // 入校时间，格式：yyyy-MM-dd
        'remarke'         => '测试用例',                       // 备注
        'sex'             => 1,                                // 性别：1-男，2-女
        'sub_type'        => 1,                                // 类型：1-走读，2-住读，3-半住读，4-半走读，5-通用
        'couponSelected'  => 2,                                // 状态：1-离职，2-在职
        
        // 可选字段
        'parentName'      => '家长姓名',                       // 父（母）姓名
        'parentPhone'     => '17311327518',                    // 父（母）电话
        'parentIdCard'    => '511024198808240004',             // 父（母）身份证
        // 'phyCard'      => '000000000',                      // 物理卡号，首位去0（可选，不传则不开卡）
        // 'photo'        => 'base64/image:...',               // 图片base64（已废弃，建议使用同步更新照片接口）
    ];

    echo "正在添加学生信息...\n";
    echo "姓名: {$studentData['studentName']}\n";
    echo "身份证: {$studentData['idCard']}\n";
    echo "手机号: {$studentData['phone']}\n";
    
    $personId = $client->addPerson($studentData);
    
    echo "✅ 添加成功！\n";
    echo "一脸通系统人员ID: {$personId}\n";
    */
    // ===== 示例2：添加教师信息（最简字段） =====
    echo "\n=== 示例2：添加教师信息（最简字段） ===\n";
    echo "⚠️  注意：此示例已注释，请根据实际情况修改身份证号和部门ID后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $teacherData = [
        'studentName'     => '张老师',
        'deptList'        => ['155610797538818'],              // 需要替换为你实际的部门ID
        'job'             => '语文教师',
        'email'           => 'teacher@example.com',
        'roleIdList'      => [5],                              // 5-教师
        'idCard'          => '510124198512105999',             // 身份证必须唯一 - 请修改为未使用的身份证
        'phone'           => '13980094545',
        'comeSchoolTime'  => '',                               // 可以为空
        'remarke'         => '',
        'sex'             => 2,                                // 2-女
        'sub_type'        => 5,                                // 5-通用
        'couponSelected'  => 2,                                // 2-在职
    ];

    echo "正在添加教师信息...\n";
    echo "姓名: {$teacherData['studentName']}\n";
    echo "身份证: {$teacherData['idCard']}\n";
    
    $teacherId = $client->addPerson($teacherData);
    
    echo "✅ 添加成功！\n";
    echo "一脸通系统人员ID: {$teacherId}\n";
    */

    // ===== 示例3：添加学生并开卡（使用临时卡号） =====
    echo "\n=== 示例3：添加学生并开卡（使用临时卡号） ===\n";
    echo "⚠️  注意：此示例已注释，请根据实际情况修改身份证号后再运行\n";
    echo "（取消下面注释即可运行）\n\n";
    
    /*
    $studentWithCardData = [
        'studentName'     => '李同学',
        'deptList'        => ['157959913636118'],
        'job'             => '学生',
        'email'           => 'student@example.com',
        'roleIdList'      => [7],
        'idCard'          => '511024199001010999',             // 请修改为未使用的身份证
        'phone'           => '13800138000',
        'comeSchoolTime'  => '2024-09-01',
        'remarke'         => '测试开卡',
        'sex'             => 1,
        'sub_type'        => 2,                                // 2-住读
        'couponSelected'  => 2,
        'phyCard'         => 'xn001',                          // 使用xn开头的临时卡号，后期可替换
    ];

    echo "正在添加学生并开卡...\n";
    echo "姓名: {$studentWithCardData['studentName']}\n";
    echo "临时卡号: {$studentWithCardData['phyCard']}\n";
    
    $studentWithCardId = $client->addPerson($studentWithCardData);
    
    echo "✅ 添加成功！\n";
    echo "一脸通系统人员ID: {$studentWithCardId}\n";
    echo "💡 提示: 后续可以通过更换卡号接口修改为正式卡号\n";
    */

    // ===== 注意事项 =====
    echo "\n=== 重要注意事项 ===\n";
    echo "1. 证件号（idCard）为主字段，如果证件号已存在，不会新增人员，会返回已有人员ID\n";
    echo "2. 建议每次调用接口间隔至少1秒，避免频率过快\n";
    echo "3. 如果需要上传照片，建议使用「同步更新照片接口」而非photo字段\n";
    echo "4. 物理卡号（phyCard）如果不传，则只创建账号不开卡\n";
    echo "5. 部门ID（deptList）需要先通过「获取部门信息」接口获取\n";

} catch (\InvalidArgumentException $e) {
    echo "\n❌ 参数错误: " . $e->getMessage() . "\n";
    echo "\n💡 提示：请检查以下内容：\n";
    echo "1. 所有必填字段是否都已填写\n";
    echo "2. deptList 和 roleIdList 必须是数组格式\n";
    echo "3. sex 只能是 1（男）或 2（女）\n";
    echo "4. sub_type 只能是 1-5 之间的值\n";
    echo "5. couponSelected 只能是 1、2 或 3\n";
    echo "6. comeSchoolTime 格式必须是 yyyy-MM-dd（或空字符串）\n";
    
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
}

