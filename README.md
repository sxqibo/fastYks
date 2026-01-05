# FastYks - 易科士开放平台 PHP SDK

易科士开放平台 PHP SDK，提供丰富的服务端接口能力，帮助开发者快速集成易科士系统。

## 安装

通过 Composer 安装：

```bash
composer require sxqibo/fast-yks
```

## 快速开始

### 配置方式

SDK 支持三种配置方式：

#### 方式1：使用配置文件（推荐）

```php
use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Campus;

// 加载配置文件
Config::loadFromFile(__DIR__ . '/config.php');

// 使用配置中的默认值创建客户端
$client = new Campus();
```

#### 方式2：直接设置配置

```php
use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Campus;

// 直接设置配置
Config::set([
    'sp' => 'your_sp_number',
    'sid' => 14,
]);

// 使用配置中的默认值
$client = new Campus();
```

#### 方式3：使用内置默认值

代码中已内置默认配置值，可以直接使用：

```php
use Sxqibo\FastYks\Campus;

// 直接使用，会自动使用内置的默认配置
$client = new Campus();
```

#### 方式4：手动传入参数（覆盖配置）

```php
use Sxqibo\FastYks\Campus;

// 手动传入参数，会覆盖配置中的值
$client = new Campus('your_sp_number', 14);
```

### 1. 获取关联组织（校区）信息

```php
<?php

require_once 'vendor/autoload.php';

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Campus;

// 加载配置（可选）
Config::loadFromFile(__DIR__ . '/config.php');

// 初始化客户端
// 方式A：使用配置中的默认值
$client = new Campus();

// 方式B：手动传入 SP 号
// $client = new Campus('your_sp_number');

// 获取校区信息
$result = $client->getCampus();

print_r($result);
// 输出示例：
// [
//   {"teamName":"测试学校","teamId":14},
//   {"teamName":"重庆市第三十七中学校","teamId":29}
// ]
```

### 2. 获取组织部门信息

```php
<?php

require_once 'vendor/autoload.php';

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Department;

// 加载配置（可选）
Config::loadFromFile(__DIR__ . '/config.php');

// 初始化客户端
// 方式A：使用配置中的默认值
$client = new Department();

// 方式B：手动传入 SP 号和 SID
// $client = new Department('your_sp_number', 14);

// 获取部门信息
$result = $client->getDepartments();

print_r($result);
// 输出示例：
// [
//   {"id":"155610795603218","title":"部门一","parentId":"14"}
// ]

// 可选：根据组织名称筛选
// $result = $client->getDepartments('体验学校05');
```

### 3. 完整示例

```php
<?php

require_once 'vendor/autoload.php';

use Sxqibo\FastYks\Config;
use Sxqibo\FastYks\Campus;
use Sxqibo\FastYks\Department;

// 加载配置
Config::loadFromFile(__DIR__ . '/config.php');

// 第一步：获取校区信息
$campusClient = new Campus();
$campuses = $campusClient->getCampus();

// 第二步：获取第一个校区的部门信息
$firstCampus = $campuses[0];
$departmentClient = new Department(null, $firstCampus['teamId']);
$departments = $departmentClient->getDepartments();

print_r($departments);
```

## 配置说明

### 配置文件

配置文件示例（`sample/config.php`）：

```php
return [
    'sp' => '8422f70fb99e43dea228f54bae4e5853',  // 服务商编号
    'sid' => 915,                                // 学校编号 ID
    'url' => 'https://face.cdyqsh.com',          // API 地址（可选）
    'charge_secret_key' => '...',                // 充值密钥（可选）
];
```

### 配置项说明

- **sp（服务商编号）**：请联系易科士对接人获取
  - 默认值：`8422f70fb99e43dea228f54bae4e5853`
  
- **sid（学校编号 ID）**：通过 `getCampus()` 接口获取，某些接口不需要 SID
  - 默认值：`915`

- **url（API 地址）**：自定义 API 地址（可选）
  - 默认值：`https://face.cdyqsh.com`
  - 系统会根据 `sid` 自动判断：
    - `sid <= 5000`: `https://face.cdyqsh.com`
    - `sid > 5000`: `https://twface.cdyqsh.com`

- **charge_secret_key（充值密钥）**：特殊权限需要（可选）
  - 默认值：`63b8afabc6dc4dedb986a4de7b36445901edb468b81e410fbc7aa353024dc7be`

### 自定义 URL

你也可以在初始化时指定自定义 URL：

```php
$client = new Campus('your_sp_number', null, 'https://custom-url.com');
```

## 接口列表

### 已实现的接口

- ✅ **获取关联组织（校区）信息** - `Campus::getCampus()`
- ✅ **获取组织部门信息** - `Department::getDepartments()`
- ✅ **添加部门信息** - `Department::addDept()`
- ✅ **修改部门信息** - `Department::updateDept()`
- ✅ **删除部门信息** - `Department::deleteDept()`

### 其他接口

项目还包含以下接口类（可参考现有代码实现）：

- `Person` - 人员信息相关接口
- `Device` - 设备信息相关接口
- `Door` - 门禁相关接口

## 错误处理

所有接口都会抛出异常，请使用 try-catch 进行错误处理：

```php
try {
    $client = new Campus('your_sp_number');
    $result = $client->getCampus();
} catch (\Exception $e) {
    echo "错误: " . $e->getMessage();
}
```

## 接口返回格式

所有接口统一返回格式：

```json
{
    "code": 200,
    "message": null,
    "value": []
}
```

- `code`: 状态码（200=成功，403=错误，500=服务错误）
- `message`: 提示消息
- `value`: 结果内容

## 访问频率限制

- **获取组织部门信息**: 50次/分
- 其他接口请参考官方文档

## 注意事项

1. 所有请求都使用 HTTPS 协议
2. 接口返回字段如果值为空或 null，将不返回该字段
3. 带查询时间的接口：时间参数必须大于组织机构启用日期，且不能跨月查询
4. 第三方系统不应耦合平台接口，应该查询自身数据库提供用户信息

## 更多示例

查看 `sample/` 目录下的示例文件：

- `campus_example.php` - 获取校区信息示例
- `department_example.php` - 获取部门信息示例
- `complete_example.php` - 完整使用示例
- `config_usage_example.php` - 配置使用示例
- `config.php` - 配置文件示例

## 相关文档

- [易科士开放平台开发指南 V2.0.1-BETA](开发指南文档)
- 如有疑问，请联系易科士对接商务或致电：028-87624455

## License

MIT

