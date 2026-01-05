<?php

namespace Sxqibo\FastYks;

use Dotenv\Dotenv;

/**
 * 配置类
 * 
 * 管理易科士 API 的配置信息
 */
class Config
{
    /**
     * 默认配置
     * 
     * @var array
     */
    private static $defaults = [];

    /**
     * 当前配置
     * 
     * @var array
     */
    private static $config = [];

    /**
     * 设置配置
     * 
     * @param array $config 配置数组
     * @return void
     */
    public static function set(array $config)
    {
        // 合并配置时，先合并默认值，再合并用户配置
        self::$config = array_merge(self::$defaults, self::$config, $config);
    }

    /**
     * 获取配置
     * 
     * @param string|null $key 配置键名，为 null 时返回所有配置
     * @param mixed $default 默认值
     * @return mixed
     */
    public static function get($key = null, $default = null)
    {
        $config = array_merge(self::$defaults, self::$config);
        
        if ($key === null) {
            return $config;
        }

        return $config[$key] ?? $default;
    }

    /**
     * 从文件加载配置
     * 
     * @param string $filePath 配置文件路径
     * @return void
     */
    public static function loadFromFile($filePath)
    {
        if (file_exists($filePath)) {
            $config = require $filePath;
            if (is_array($config)) {
                self::set($config);
            }
        }
    }

    /**
     * 从环境变量加载配置
     * 
     * @param string|null $envPath .env 文件所在目录，默认为项目根目录
     * @param string $envFile .env 文件名，默认为 '.env'
     * @return void
     */
    public static function loadFromEnv($envPath = null, $envFile = '.env')
    {
        // 如果未指定路径，尝试自动检测
        if ($envPath === null) {
            // 尝试从调用位置向上查找项目根目录
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
            if (!empty($trace[0]['file'])) {
                $envPath = dirname($trace[0]['file']);
                // 向上查找直到找到包含 .env 的目录或项目根目录
                while ($envPath !== '/' && $envPath !== '') {
                    if (file_exists($envPath . '/' . $envFile)) {
                        break;
                    }
                    $parentPath = dirname($envPath);
                    if ($parentPath === $envPath) {
                        break;
                    }
                    $envPath = $parentPath;
                }
            } else {
                // 如果无法获取调用位置，使用当前工作目录
                $envPath = getcwd();
            }
        }

        // 加载 .env 文件
        $envFilePath = $envPath . '/' . $envFile;
        if (file_exists($envFilePath)) {
            if (class_exists(Dotenv::class)) {
                // 使用 phpdotenv 库加载
                $dotenv = Dotenv::createImmutable($envPath, $envFile);
                $dotenv->load();
            } else {
                // 手动解析 .env 文件（作为后备方案）
                self::parseEnvFile($envFilePath);
            }
        }

        // 从环境变量读取配置
        $envConfig = [];
        
        if (getenv('YKS_SP') !== false) {
            $envConfig['sp'] = getenv('YKS_SP');
        }
        
        if (getenv('YKS_SID') !== false) {
            $envConfig['sid'] = (int)getenv('YKS_SID');
        }
        
        if (getenv('YKS_URL') !== false) {
            $envConfig['url'] = getenv('YKS_URL');
        }
        
        if (getenv('YKS_CHARGE_SECRET_KEY') !== false) {
            $envConfig['charge_secret_key'] = getenv('YKS_CHARGE_SECRET_KEY');
        }

        if (!empty($envConfig)) {
            self::set($envConfig);
        }
    }

    /**
     * 手动解析 .env 文件（当 phpdotenv 不可用时使用）
     * 
     * @param string $filePath .env 文件路径
     * @return void
     */
    private static function parseEnvFile($filePath)
    {
        if (!file_exists($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            // 跳过注释
            $line = trim($line);
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            // 解析 KEY=VALUE 格式
            if (strpos($line, '=') === false) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // 移除引号（如果有）
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }

            // 如果值不为空，设置到环境变量
            if (!empty($key) && $value !== '') {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

