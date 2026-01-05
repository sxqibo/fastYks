<?php

/**
 * Bootstrap 文件
 * 
 * 用于在没有 composer autoload 的情况下手动加载类文件
 * 如果已安装 composer，建议使用 vendor/autoload.php 而不是此文件
 */

// 自动加载 Sxqibo\FastYks 命名空间下的类
spl_autoload_register(function ($className) {
    // 只处理本项目命名空间
    if (strpos($className, 'Sxqibo\\FastYks\\') !== 0) {
        return false;
    }
    
    // 将命名空间转换为文件路径
    $relativeClass = substr($className, strlen('Sxqibo\\FastYks\\'));
    $file = __DIR__ . '/../src/' . str_replace('\\', '/', $relativeClass) . '.php';
    
    // 如果文件存在则加载
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});

// 如果没有安装 composer，尝试加载 vlucas/phpdotenv（如果存在）
if (!class_exists('Dotenv\\Dotenv')) {
    $dotenvPath = __DIR__ . '/../vendor/vlucas/phpdotenv/src/Dotenv.php';
    if (file_exists($dotenvPath)) {
        require_once $dotenvPath;
    }
}

