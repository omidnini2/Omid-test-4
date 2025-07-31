<?php
/**
 * Database Configuration for Voice Cloning Website
 * 
 * This file contains database configuration settings for WAMP64/XAMPP
 * Compatible with MySQL/MariaDB
 */

class DatabaseConfig {
    // Database connection settings
    private const DB_HOST = 'localhost';
    private const DB_PORT = 3306;
    private const DB_NAME = 'voice_cloning';
    private const DB_USER = 'root';
    private const DB_PASS = ''; // Default XAMPP/WAMP password is empty
    private const DB_CHARSET = 'utf8mb4';
    
    // Connection options
    private const DB_OPTIONS = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];
    
    private static $instance = null;
    private $connection = null;
    
    private function __construct() {
        $this->connect();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect() {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                self::DB_HOST,
                self::DB_PORT,
                self::DB_NAME,
                self::DB_CHARSET
            );
            
            $this->connection = new PDO($dsn, self::DB_USER, self::DB_PASS, self::DB_OPTIONS);
            
            // Set timezone
            $this->connection->exec("SET time_zone = '+00:00'");
            
        } catch (PDOException $e) {
            // If database doesn't exist, try to create it
            if ($e->getCode() == 1049) { // Unknown database
                $this->createDatabase();
                $this->connect(); // Retry connection
            } else {
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }
    }
    
    private function createDatabase() {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;charset=%s',
                self::DB_HOST,
                self::DB_PORT,
                self::DB_CHARSET
            );
            
            $pdo = new PDO($dsn, self::DB_USER, self::DB_PASS, self::DB_OPTIONS);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . self::DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
        } catch (PDOException $e) {
            error_log("Failed to create database: " . $e->getMessage());
            throw new Exception("Failed to create database: " . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function initializeTables() {
        $queries = [
            // User sessions table
            "CREATE TABLE IF NOT EXISTS `user_sessions` (
                `id` varchar(128) NOT NULL PRIMARY KEY,
                `user_ip` varchar(45) NOT NULL,
                `user_agent` text,
                `language` varchar(10) DEFAULT 'fa',
                `theme` varchar(10) DEFAULT 'light',
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `expires_at` timestamp NULL,
                INDEX `idx_user_ip` (`user_ip`),
                INDEX `idx_expires_at` (`expires_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Voice cloning requests table
            "CREATE TABLE IF NOT EXISTS `voice_requests` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `session_id` varchar(128) NOT NULL,
                `original_filename` varchar(255) NOT NULL,
                `file_size` bigint(20) NOT NULL,
                `file_type` varchar(50) NOT NULL,
                `text_content` longtext NOT NULL,
                `text_length` int(11) NOT NULL,
                `language` varchar(10) NOT NULL DEFAULT 'fa',
                `quality` enum('low','medium','high') NOT NULL DEFAULT 'high',
                `speed` decimal(3,1) NOT NULL DEFAULT 1.0,
                `pitch` decimal(3,1) NOT NULL DEFAULT 1.0,
                `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
                `output_filename` varchar(255) NULL,
                `output_size` bigint(20) NULL,
                `processing_time` decimal(8,2) NULL,
                `error_message` text NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `completed_at` timestamp NULL,
                INDEX `idx_session_id` (`session_id`),
                INDEX `idx_status` (`status`),
                INDEX `idx_language` (`language`),
                INDEX `idx_created_at` (`created_at`),
                FOREIGN KEY (`session_id`) REFERENCES `user_sessions`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // Usage statistics table
            "CREATE TABLE IF NOT EXISTS `usage_stats` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `date` date NOT NULL,
                `language` varchar(10) NOT NULL,
                `total_requests` int(11) NOT NULL DEFAULT 0,
                `successful_requests` int(11) NOT NULL DEFAULT 0,
                `failed_requests` int(11) NOT NULL DEFAULT 0,
                `total_text_length` bigint(20) NOT NULL DEFAULT 0,
                `total_processing_time` decimal(12,2) NOT NULL DEFAULT 0.00,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY `unique_date_language` (`date`, `language`),
                INDEX `idx_date` (`date`),
                INDEX `idx_language` (`language`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
            
            // System settings table
            "CREATE TABLE IF NOT EXISTS `system_settings` (
                `setting_key` varchar(100) NOT NULL PRIMARY KEY,
                `setting_value` longtext NOT NULL,
                `setting_type` enum('string','integer','float','boolean','json') NOT NULL DEFAULT 'string',
                `description` text NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        ];
        
        foreach ($queries as $query) {
            try {
                $this->connection->exec($query);
            } catch (PDOException $e) {
                error_log("Failed to create table: " . $e->getMessage());
                throw new Exception("Failed to initialize database tables: " . $e->getMessage());
            }
        }
        
        // Insert default settings
        $this->insertDefaultSettings();
    }
    
    private function insertDefaultSettings() {
        $defaultSettings = [
            ['max_file_size', '104857600', 'integer', 'Maximum upload file size in bytes (100MB)'],
            ['max_text_length', '100000', 'integer', 'Maximum text length in characters'],
            ['session_lifetime', '3600', 'integer', 'Session lifetime in seconds (1 hour)'],
            ['cleanup_interval', '86400', 'integer', 'File cleanup interval in seconds (24 hours)'],
            ['supported_languages', '["fa","en","ar","es","fr","de","it","pt","ru","zh","ja","ko","hi","tr","nl","sv","da","no","fi","pl","cs","sk","hu","ro","bg","hr","sr","sl","et","lv","lt","mt","ga","cy","eu","ca","gl","is","mk","sq","he","ur","bn","ta","te","ml","kn","gu","pa","or","as","ne","si","my","th","vi","id","ms","tl","sw","am","yo","ig","ha","zu","af"]', 'json', 'List of supported language codes'],
            ['default_language', 'fa', 'string', 'Default language for the application'],
            ['enable_analytics', '1', 'boolean', 'Enable usage analytics'],
            ['maintenance_mode', '0', 'boolean', 'Enable maintenance mode']
        ];
        
        $stmt = $this->connection->prepare(
            "INSERT IGNORE INTO system_settings (setting_key, setting_value, setting_type, description) VALUES (?, ?, ?, ?)"
        );
        
        foreach ($defaultSettings as $setting) {
            $stmt->execute($setting);
        }
    }
    
    public function getSetting($key, $default = null) {
        try {
            $stmt = $this->connection->prepare("SELECT setting_value, setting_type FROM system_settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch();
            
            if (!$result) {
                return $default;
            }
            
            $value = $result['setting_value'];
            $type = $result['setting_type'];
            
            switch ($type) {
                case 'integer':
                    return (int) $value;
                case 'float':
                    return (float) $value;
                case 'boolean':
                    return (bool) $value;
                case 'json':
                    return json_decode($value, true);
                default:
                    return $value;
            }
        } catch (PDOException $e) {
            error_log("Failed to get setting: " . $e->getMessage());
            return $default;
        }
    }
    
    public function setSetting($key, $value, $type = 'string') {
        try {
            if ($type === 'json') {
                $value = json_encode($value);
            }
            
            $stmt = $this->connection->prepare(
                "INSERT INTO system_settings (setting_key, setting_value, setting_type) VALUES (?, ?, ?) 
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), setting_type = VALUES(setting_type)"
            );
            
            return $stmt->execute([$key, $value, $type]);
        } catch (PDOException $e) {
            error_log("Failed to set setting: " . $e->getMessage());
            return false;
        }
    }
    
    public function cleanup() {
        try {
            // Clean up expired sessions
            $this->connection->exec("DELETE FROM user_sessions WHERE expires_at < NOW()");
            
            // Clean up old completed/failed requests (older than 24 hours)
            $this->connection->exec("DELETE FROM voice_requests WHERE status IN ('completed', 'failed') AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
            
            // Clean up orphaned sessions (no recent activity)
            $this->connection->exec("DELETE FROM user_sessions WHERE updated_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
            
        } catch (PDOException $e) {
            error_log("Database cleanup failed: " . $e->getMessage());
        }
    }
    
    public function __destruct() {
        $this->connection = null;
    }
}

// Initialize database on first load
try {
    $db = DatabaseConfig::getInstance();
    $db->initializeTables();
} catch (Exception $e) {
    error_log("Database initialization failed: " . $e->getMessage());
    // Continue without database if initialization fails
}
?>