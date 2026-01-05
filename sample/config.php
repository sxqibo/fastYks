<?php

/**
 * 配置文件示例 - 从环境变量加载
 * 
 * 使用方式：
 * 1. 复制 .env.example 为 .env：
 *    cp .env.example .env
 * 2. 编辑 .env 文件，填入你的实际配置信息
 * 3. 在代码中加载配置：
 *    use Sxqibo\FastYks\Config;
 *    Config::loadFromEnv(__DIR__); // 从当前目录加载 .env 文件
 * 4. 创建客户端时可以不传参数，将自动使用配置中的值：
 *    $client = new Campus(); // 自动使用配置中的 sp
 * 
 * 环境变量说明：
 * YKS_SP - 服务商编号（SP号），请联系易科士对接人获取
 * YKS_SID - 学校编号 ID（SID），通过 getCampus 接口获取
 * YKS_URL - 自定义 API 地址（可选）
 * YKS_CHARGE_SECRET_KEY - 充值密钥（特殊权限需要，可选）
 */

use Sxqibo\FastYks\Config;

// 从环境变量加载配置（.env 文件应该在项目根目录）
// 这里从 sample 目录向上查找 .env 文件
Config::loadFromEnv(dirname(__DIR__));