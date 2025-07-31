<?php
/**
 * Voice Cloning Website Installation Script
 * 
 * This script helps users set up the voice cloning website
 * by checking requirements and configuring the system automatically.
 */

// Start session for installation progress
session_start();

// Define installation steps
$steps = [
    'requirements' => 'بررسی پیش‌نیازها / Check Requirements',
    'database' => 'پیکربندی پایگاه داده / Database Configuration',
    'directories' => 'ایجاد پوشه‌ها / Create Directories',
    'permissions' => 'تنظیم دسترسی‌ها / Set Permissions',
    'complete' => 'تکمیل نصب / Installation Complete'
];

$currentStep = $_GET['step'] ?? 'requirements';

// Check if installation is already complete
if (file_exists('install.lock') && $currentStep !== 'complete') {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نصب کلون صدا - Voice Cloning Installation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .installer {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 800px;
            width: 100%;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .progress {
            background: #f8fafc;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .step.active {
            background: #6366f1;
            color: white;
        }
        
        .step.completed {
            background: #10b981;
            color: white;
        }
        
        .content {
            padding: 40px;
        }
        
        .requirement {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            background: #f8fafc;
        }
        
        .requirement.pass {
            background: #d1fae5;
            border-left: 4px solid #10b981;
        }
        
        .requirement.fail {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
        }
        
        .status {
            font-weight: bold;
            padding: 5px 15px;
            border-radius: 20px;
            color: white;
        }
        
        .status.pass {
            background: #10b981;
        }
        
        .status.fail {
            background: #ef4444;
        }
        
        .form-group {
            margin: 20px 0;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #6366f1;
        }
        
        .btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 10px 5px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
        }
        
        .btn.secondary {
            background: #6b7280;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .alert.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        
        .alert.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }
        
        .alert.info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #3b82f6;
        }
        
        @media (max-width: 768px) {
            .progress {
                flex-direction: column;
            }
            
            .step {
                width: 100%;
                margin: 5px 0;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="installer">
        <div class="header">
            <h1>🎤 کلون صدا</h1>
            <p>Voice Cloning Website Installation</p>
        </div>
        
        <div class="progress">
            <?php foreach ($steps as $stepKey => $stepName): ?>
                <div class="step <?= $stepKey === $currentStep ? 'active' : '' ?> <?= array_search($stepKey, array_keys($steps)) < array_search($currentStep, array_keys($steps)) ? 'completed' : '' ?>">
                    <?= $stepName ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="content">
            <?php
            switch ($currentStep) {
                case 'requirements':
                    echo renderRequirementsStep();
                    break;
                case 'database':
                    echo renderDatabaseStep();
                    break;
                case 'directories':
                    echo renderDirectoriesStep();
                    break;
                case 'permissions':
                    echo renderPermissionsStep();
                    break;
                case 'complete':
                    echo renderCompleteStep();
                    break;
                default:
                    header('Location: ?step=requirements');
                    exit;
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php

function renderRequirementsStep() {
    $requirements = [
        'PHP Version >= 7.4' => version_compare(PHP_VERSION, '7.4.0', '>='),
        'MySQL Extension' => extension_loaded('pdo_mysql'),
        'FileInfo Extension' => extension_loaded('fileinfo'),
        'JSON Extension' => extension_loaded('json'),
        'MBString Extension' => extension_loaded('mbstring'),
        'OpenSSL Extension' => extension_loaded('openssl'),
        'Writable uploads/ directory' => is_writable('uploads') || !file_exists('uploads'),
        'Writable output/ directory' => is_writable('output') || !file_exists('output'),
        'Writable logs/ directory' => is_writable('logs') || !file_exists('logs'),
    ];
    
    $allPassed = true;
    
    ob_start();
    ?>
    <h2>بررسی پیش‌نیازها / System Requirements Check</h2>
    <p>لطفاً اطمینان حاصل کنید که تمام پیش‌نیازها برآورده شده‌اند.</p>
    <p>Please ensure all requirements are met before proceeding.</p>
    
    <?php foreach ($requirements as $requirement => $passed): ?>
        <?php if (!$passed) $allPassed = false; ?>
        <div class="requirement <?= $passed ? 'pass' : 'fail' ?>">
            <span><?= $requirement ?></span>
            <span class="status <?= $passed ? 'pass' : 'fail' ?>">
                <?= $passed ? '✓ PASS' : '✗ FAIL' ?>
            </span>
        </div>
    <?php endforeach; ?>
    
    <?php if ($allPassed): ?>
        <div class="alert success">
            <strong>عالی!</strong> تمام پیش‌نیازها برآورده شده‌اند. می‌توانید به مرحله بعد بروید.
            <br><strong>Great!</strong> All requirements are met. You can proceed to the next step.
        </div>
        <a href="?step=database" class="btn">ادامه / Continue</a>
    <?php else: ?>
        <div class="alert error">
            <strong>خطا!</strong> برخی پیش‌نیازها برآورده نشده‌اند. لطفاً آنها را برطرف کنید.
            <br><strong>Error!</strong> Some requirements are not met. Please fix them before continuing.
        </div>
        <a href="?step=requirements" class="btn">بررسی مجدد / Check Again</a>
    <?php endif; ?>
    
    <?php
    return ob_get_clean();
}

function renderDatabaseStep() {
    if ($_POST) {
        return handleDatabaseConfiguration();
    }
    
    ob_start();
    ?>
    <h2>پیکربندی پایگاه داده / Database Configuration</h2>
    <p>لطفاً اطلاعات پایگاه داده خود را وارد کنید.</p>
    <p>Please enter your database information.</p>
    
    <form method="POST">
        <div class="form-group">
            <label>Database Host / آدرس پایگاه داده:</label>
            <input type="text" name="db_host" value="localhost" required>
        </div>
        
        <div class="form-group">
            <label>Database Name / نام پایگاه داده:</label>
            <input type="text" name="db_name" value="voice_cloning" required>
        </div>
        
        <div class="form-group">
            <label>Database User / کاربر پایگاه داده:</label>
            <input type="text" name="db_user" value="root" required>
        </div>
        
        <div class="form-group">
            <label>Database Password / رمز عبور پایگاه داده:</label>
            <input type="password" name="db_pass" placeholder="Leave empty for XAMPP/WAMP default">
        </div>
        
        <button type="submit" class="btn">تست و ادامه / Test & Continue</button>
        <a href="?step=requirements" class="btn secondary">بازگشت / Back</a>
    </form>
    
    <?php
    return ob_get_clean();
}

function handleDatabaseConfiguration() {
    $host = $_POST['db_host'];
    $name = $_POST['db_name'];
    $user = $_POST['db_user'];
    $pass = $_POST['db_pass'];
    
    try {
        // Test connection
        $dsn = "mysql:host=$host;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        
        // Update config file
        $configContent = file_get_contents('config/database.php');
        $configContent = preg_replace("/private const DB_HOST = '.*?';/", "private const DB_HOST = '$host';", $configContent);
        $configContent = preg_replace("/private const DB_NAME = '.*?';/", "private const DB_NAME = '$name';", $configContent);
        $configContent = preg_replace("/private const DB_USER = '.*?';/", "private const DB_USER = '$user';", $configContent);
        $configContent = preg_replace("/private const DB_PASS = '.*?';/", "private const DB_PASS = '$pass';", $configContent);
        
        file_put_contents('config/database.php', $configContent);
        
        $_SESSION['db_configured'] = true;
        
        ob_start();
        ?>
        <div class="alert success">
            <strong>موفق!</strong> اتصال به پایگاه داده برقرار شد و پیکربندی ذخیره شد.
            <br><strong>Success!</strong> Database connection established and configuration saved.
        </div>
        <a href="?step=directories" class="btn">ادامه / Continue</a>
        <?php
        return ob_get_clean();
        
    } catch (Exception $e) {
        ob_start();
        ?>
        <div class="alert error">
            <strong>خطا!</strong> امکان اتصال به پایگاه داده وجود ندارد: <?= htmlspecialchars($e->getMessage()) ?>
            <br><strong>Error!</strong> Cannot connect to database: <?= htmlspecialchars($e->getMessage()) ?>
        </div>
        <?= renderDatabaseStep() ?>
        <?php
        return ob_get_clean();
    }
}

function renderDirectoriesStep() {
    $directories = ['uploads', 'output', 'logs', 'config'];
    $created = [];
    $errors = [];
    
    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            if (mkdir($dir, 0755, true)) {
                $created[] = $dir;
            } else {
                $errors[] = $dir;
            }
        }
    }
    
    ob_start();
    ?>
    <h2>ایجاد پوشه‌ها / Create Directories</h2>
    
    <?php if (!empty($created)): ?>
        <div class="alert success">
            <strong>موفق!</strong> پوشه‌های زیر ایجاد شدند:
            <br><strong>Success!</strong> The following directories were created:
            <ul>
                <?php foreach ($created as $dir): ?>
                    <li><?= $dir ?>/</li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <strong>خطا!</strong> امکان ایجاد پوشه‌های زیر وجود ندارد:
            <br><strong>Error!</strong> Could not create the following directories:
            <ul>
                <?php foreach ($errors as $dir): ?>
                    <li><?= $dir ?>/</li>
                <?php endforeach; ?>
            </ul>
            <p>لطفاً این پوشه‌ها را به صورت دستی ایجاد کنید.</p>
            <p>Please create these directories manually.</p>
        </div>
    <?php endif; ?>
    
    <div class="alert info">
        <strong>ساختار پوشه‌ها / Directory Structure:</strong>
        <ul>
            <li><strong>uploads/</strong> - فایل‌های آپلود شده / Uploaded files</li>
            <li><strong>output/</strong> - فایل‌های خروجی / Generated audio files</li>
            <li><strong>logs/</strong> - فایل‌های لاگ / Log files</li>
            <li><strong>config/</strong> - فایل‌های پیکربندی / Configuration files</li>
        </ul>
    </div>
    
    <a href="?step=permissions" class="btn">ادامه / Continue</a>
    <a href="?step=database" class="btn secondary">بازگشت / Back</a>
    
    <?php
    return ob_get_clean();
}

function renderPermissionsStep() {
    $directories = ['uploads', 'output', 'logs'];
    $permissions = [];
    
    foreach ($directories as $dir) {
        if (file_exists($dir)) {
            $perms = fileperms($dir);
            $permissions[$dir] = [
                'readable' => is_readable($dir),
                'writable' => is_writable($dir),
                'permissions' => substr(sprintf('%o', $perms), -4)
            ];
        }
    }
    
    ob_start();
    ?>
    <h2>بررسی دسترسی‌ها / Check Permissions</h2>
    
    <?php foreach ($permissions as $dir => $perm): ?>
        <div class="requirement <?= $perm['readable'] && $perm['writable'] ? 'pass' : 'fail' ?>">
            <span><?= $dir ?>/ (<?= $perm['permissions'] ?>)</span>
            <span class="status <?= $perm['readable'] && $perm['writable'] ? 'pass' : 'fail' ?>">
                <?= $perm['readable'] && $perm['writable'] ? '✓ OK' : '✗ FAIL' ?>
            </span>
        </div>
    <?php endforeach; ?>
    
    <div class="alert info">
        <strong>توجه / Note:</strong>
        <p>اگر دسترسی‌ها مناسب نیستند، دستورات زیر را در ترمینال اجرا کنید:</p>
        <p>If permissions are not correct, run these commands in terminal:</p>
        <code>
            chmod 755 uploads/ output/ logs/<br>
            chown -R www-data:www-data uploads/ output/ logs/
        </code>
    </div>
    
    <a href="?step=complete" class="btn">تکمیل نصب / Complete Installation</a>
    <a href="?step=directories" class="btn secondary">بازگشت / Back</a>
    
    <?php
    return ob_get_clean();
}

function renderCompleteStep() {
    // Create installation lock file
    file_put_contents('install.lock', date('Y-m-d H:i:s'));
    
    ob_start();
    ?>
    <h2>🎉 نصب تکمیل شد / Installation Complete!</h2>
    
    <div class="alert success">
        <strong>تبریک!</strong> وب‌سایت کلون صدا با موفقیت نصب شد.
        <br><strong>Congratulations!</strong> Voice Cloning website has been successfully installed.
    </div>
    
    <div class="alert info">
        <h3>مراحل بعدی / Next Steps:</h3>
        <ol>
            <li>فایل <code>install.php</code> را برای امنیت حذف کنید / Delete <code>install.php</code> for security</li>
            <li>تنظیمات امنیتی را در <code>.htaccess</code> بررسی کنید / Review security settings in <code>.htaccess</code></li>
            <li>برای استفاده واقعی، سرویس‌های AI را پیکربندی کنید / Configure AI services for production use</li>
            <li>فایل‌های آیکون PWA را در <code>assets/images/</code> قرار دهید / Add PWA icons to <code>assets/images/</code></li>
        </ol>
    </div>
    
    <div class="alert info">
        <h3>ویژگی‌های نصب شده / Installed Features:</h3>
        <ul>
            <li>✅ رابط کاربری فارسی و چندزبانه / Persian & Multi-language UI</li>
            <li>✅ سیستم آپلود و ضبط صدا / Audio upload & recording system</li>
            <li>✅ پشتیبانی از 99% زبان‌های دنیا / Support for 99% of world languages</li>
            <li>✅ تم تاریک و روشن / Dark & light themes</li>
            <li>✅ طراحی ریسپانسیو / Responsive design</li>
            <li>✅ PWA و Service Worker / PWA & Service Worker</li>
            <li>✅ پایگاه داده و مدیریت جلسات / Database & session management</li>
            <li>✅ امنیت و CORS / Security & CORS</li>
        </ul>
    </div>
    
    <a href="index.php" class="btn" style="font-size: 1.2rem; padding: 20px 40px;">
        🚀 شروع استفاده / Start Using
    </a>
    
    <p style="margin-top: 30px; text-align: center; color: #6b7280;">
        <strong>نکته:</strong> این نسخه برای نمایش طراحی شده است. برای کلون واقعی صدا، سرویس‌های AI را پیکربندی کنید.
        <br><strong>Note:</strong> This version is for demonstration. For actual voice cloning, configure AI services.
    </p>
    
    <?php
    return ob_get_clean();
}
?>