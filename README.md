# کلون صدا - Voice Cloning Website

یک وب‌سایت کاملاً رایگان و پیشرفته برای کلون کردن صدا با استفاده از هوش مصنوعی که با WAMP64 و XAMPP سازگار است.

A completely free and advanced voice cloning website using artificial intelligence, compatible with WAMP64 and XAMPP.

## ✨ ویژگی‌ها / Features

### 🎯 ویژگی‌های اصلی / Main Features
- 🎤 **کلون صدا با دقت بالا** - High-precision voice cloning
- 🌍 **پشتیبانی از 99% زبان‌های دنیا** - Support for 99% of world languages
- 🇮🇷 **تمرکز بر زبان فارسی** - Focus on Persian/Farsi language
- 📱 **طراحی ریسپانسیو** - Responsive design
- 🌙 **تم تاریک و روشن** - Dark and light themes
- 📝 **پشتیبانی از 100,000 کاراکتر متن** - Support for 100,000 characters of text
- 🎵 **ضبط مستقیم صدا** - Direct voice recording
- 📁 **آپلود فایل‌های صوتی** - Audio file upload
- ⚡ **پردازش سریع** - Fast processing
- 💾 **دانلود آسان** - Easy download

### 🔧 ویژگی‌های فنی / Technical Features
- 🐘 **PHP Backend** with MySQL database
- 🎨 **Modern CSS** with CSS Variables
- ⚡ **Vanilla JavaScript** (no frameworks required)
- 🔒 **Security Headers** and CORS support
- 📊 **Usage Analytics** and session management
- 🧹 **Automatic Cleanup** system
- 🌐 **Multi-language Interface**
- 📱 **PWA Ready** (Progressive Web App)

## 🚀 نصب و راه‌اندازی / Installation

### پیش‌نیازها / Prerequisites
- WAMP64, XAMPP, یا هر سرور Apache/PHP دیگر
- PHP 7.4 یا بالاتر
- MySQL 5.7 یا MariaDB 10.3 یا بالاتر
- حداقل 512MB RAM
- 1GB فضای خالی

### مراحل نصب / Installation Steps

#### 1. دانلود و استخراج / Download & Extract
```bash
# Clone the repository or download ZIP
git clone https://github.com/your-username/voice-cloning-website.git
# یا فایل ZIP را دانلود و استخراج کنید
```

#### 2. انتقال فایل‌ها / Move Files
```bash
# Copy files to your web server directory
# فایل‌ها را به پوشه وب سرور منتقل کنید
# WAMP: C:\wamp64\www\voice-cloning\
# XAMPP: C:\xampp\htdocs\voice-cloning\
```

#### 3. تنظیمات پایگاه داده / Database Configuration
```php
// config/database.php فایل را ویرایش کنید
private const DB_HOST = 'localhost';
private const DB_NAME = 'voice_cloning';
private const DB_USER = 'root';
private const DB_PASS = ''; // رمز عبور MySQL خود را وارد کنید
```

#### 4. تنظیمات Apache / Apache Configuration
```apache
# .htaccess فایل از قبل پیکربندی شده است
# اطمینان حاصل کنید که mod_rewrite فعال باشد
```

#### 5. دسترسی‌ها / Permissions
```bash
# Set proper permissions for upload directories
# دسترسی‌های مناسب برای پوشه‌های آپلود تنظیم کنید
chmod 755 uploads/
chmod 755 output/
chmod 755 logs/
```

#### 6. اجرا / Run
```
http://localhost/voice-cloning/
```

## 📖 راهنمای استفاده / Usage Guide

### 1. آپلود یا ضبط صدا / Upload or Record Voice
- فایل صوتی خود را آپلود کنید (MP3, WAV, M4A, AAC, OGG, WebM)
- یا مستقیماً با میکروفون ضبط کنید
- حداقل 20 دقیقه صدا توصیه می‌شود

### 2. وارد کردن متن / Enter Text
- متن مورد نظر خود را وارد کنید (تا 100,000 کاراکتر)
- از زبان فارسی و سایر زبان‌ها پشتیبانی می‌شود

### 3. تنظیمات صدا / Voice Settings
- کیفیت: پایین، متوسط، بالا
- سرعت گفتار: 0.5x تا 2.0x
- تن صدا: 0.5 تا 1.5

### 4. تولید و دانلود / Generate & Download
- روی دکمه "تولید صدای کلون شده" کلیک کنید
- منتظر تکمیل پردازش باشید
- فایل صوتی را دانلود کنید

## 🌍 زبان‌های پشتیبانی شده / Supported Languages

### زبان‌های اصلی / Main Languages
- 🇮🇷 **فارسی** (Persian/Farsi) - اولویت اول
- 🇺🇸 English
- 🇸🇦 العربية (Arabic)
- 🇪🇸 Español (Spanish)
- 🇫🇷 Français (French)
- 🇩🇪 Deutsch (German)
- 🇮🇹 Italiano (Italian)
- 🇵🇹 Português (Portuguese)
- 🇷🇺 Русский (Russian)
- 🇨🇳 中文 (Chinese)
- 🇯🇵 日本語 (Japanese)
- 🇰🇷 한국어 (Korean)

### سایر زبان‌ها / Other Languages
و بیش از 50 زبان دیگر از جمله هندی، ترکی، هلندی، سوئدی، دانمارکی، نروژی، فنلاندی، لهستانی، چکی، اسلواکی، مجاری، رومانیایی، بلغاری، کرواتی، صربی، اسلوونیایی، استونیایی، لتونیایی، لیتوانیایی، مالتی، ایرلندی، ولزی، باسکی، کاتالان، گالیسی، ایسلندی، مقدونی، آلبانیایی، عبری، اردو، بنگالی، تامیل، تلوگو، مالایالام، کانادا، گجراتی، پنجابی، اودیا، آسامی، نپالی، سینهالا، میانماری، تایلندی، ویتنامی، اندونزیایی، مالایی، فیلیپینی، سواحیلی، امهری، یوروبا، ایگبو، هوسا، زولو، و آفریکانس.

## 🛠️ ساختار پروژه / Project Structure

```
voice-cloning-website/
├── index.php                 # صفحه اصلی / Main page
├── .htaccess                 # تنظیمات Apache / Apache config
├── README.md                 # راهنما / Documentation
├── assets/                   # فایل‌های استاتیک / Static files
│   ├── css/
│   │   └── style.css         # استایل‌ها / Styles
│   └── js/
│       └── script.js         # جاوا اسکریپت / JavaScript
├── api/                      # API endpoints
│   └── generate_voice.php    # API کلون صدا / Voice cloning API
├── config/                   # تنظیمات / Configuration
│   └── database.php          # پیکربندی دیتابیس / Database config
├── uploads/                  # فایل‌های آپلود شده / Uploaded files
├── output/                   # فایل‌های خروجی / Output files
└── logs/                     # فایل‌های لاگ / Log files
```

## ⚙️ تنظیمات پیشرفته / Advanced Configuration

### تنظیمات PHP / PHP Settings
```ini
; php.ini تنظیمات
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
```

### تنظیمات پایگاه داده / Database Settings
```sql
-- Manual database creation (if needed)
CREATE DATABASE voice_cloning CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### تنظیمات امنیتی / Security Settings
- فایل `.htaccess` شامل تنظیمات امنیتی پیشرفته
- CORS headers برای API
- محدودیت دسترسی به فایل‌های حساس
- Rate limiting (در صورت وجود mod_evasive)

## 🐛 عیب‌یابی / Troubleshooting

### مشکلات رایج / Common Issues

#### 1. خطای دیتابیس / Database Error
```
Solution: Check database credentials in config/database.php
راه‌حل: اطلاعات دیتابیس را در config/database.php بررسی کنید
```

#### 2. خطای آپلود فایل / File Upload Error
```
Solution: Check PHP upload settings and directory permissions
راه‌حل: تنظیمات آپلود PHP و دسترسی‌های پوشه را بررسی کنید
```

#### 3. خطای میکروفون / Microphone Error
```
Solution: Ensure HTTPS is enabled or allow microphone access
راه‌حل: HTTPS را فعال کنید یا دسترسی میکروفون را مجاز کنید
```

#### 4. خطای تولید صدا / Voice Generation Error
```
Solution: Check server resources and processing time limits
راه‌حل: منابع سرور و محدودیت زمان پردازش را بررسی کنید
```

## 🔧 سفارشی‌سازی / Customization

### تغییر تم / Theme Modification
```css
/* assets/css/style.css */
:root {
    --primary-color: #your-color;
    --accent-color: #your-accent;
}
```

### اضافه کردن زبان جدید / Adding New Language
```javascript
// assets/js/script.js
// Add to language selector options
<option value="your-lang">Your Language</option>
```

### تنظیم محدودیت‌ها / Setting Limits
```php
// config/database.php
// Modify default settings
['max_file_size', '104857600', 'integer', 'Max file size'],
['max_text_length', '100000', 'integer', 'Max text length'],
```

## 📊 آمار و تحلیل / Analytics & Statistics

سیستم شامل آمار کاملی از:
- تعداد درخواست‌ها به تفکیک زبان
- زمان پردازش
- نرخ موفقیت
- استفاده از منابع

The system includes comprehensive statistics for:
- Requests by language
- Processing times
- Success rates
- Resource usage

## 🤝 مشارکت / Contributing

### راهنمای مشارکت / Contribution Guide
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

### گزارش باگ / Bug Reports
لطفاً باگ‌ها را با جزئیات کامل گزارش دهید:
- نسخه PHP و MySQL
- پیام خطا
- مراحل تکرار مشکل

Please report bugs with complete details:
- PHP and MySQL versions
- Error messages
- Steps to reproduce

## 📝 مجوز / License

این پروژه تحت مجوز MIT منتشر شده است. برای استفاده تجاری و غیرتجاری آزاد هستید.

This project is released under the MIT License. You are free to use it for commercial and non-commercial purposes.

## 🙏 تشکر / Acknowledgments

- Font Awesome برای آیکون‌ها
- Google Fonts برای فونت Vazirmatn
- جامعه توسعه‌دهندگان PHP

Special thanks to:
- Font Awesome for icons
- Google Fonts for Vazirmatn font
- PHP developer community

## 📞 پشتیبانی / Support

برای پشتیبانی و سوالات:
- GitHub Issues
- Email: support@example.com

For support and questions:
- GitHub Issues
- Email: support@example.com

---

**نکته مهم:** این نسخه برای نمایش و تست طراحی شده است. برای استفاده واقعی از کلون صدا، نیاز به ادغام با سرویس‌های هوش مصنوعی واقعی دارید.

**Important Note:** This version is designed for demonstration and testing. For actual voice cloning functionality, you need to integrate with real AI voice cloning services.

---

<div align="center">
  <strong>ساخته شده با ❤️ برای جامعه متن‌باز</strong><br>
  <strong>Made with ❤️ for the open source community</strong>
</div>