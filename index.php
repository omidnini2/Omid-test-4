<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>کلون صدا - Voice Cloning</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Voice Clone">
    <link rel="apple-touch-icon" href="assets/images/icon-192x192.png">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="یک وب‌سایت کاملاً رایگان برای کلون کردن صدا با هوش مصنوعی - پشتیبانی از زبان فارسی و 99% زبان‌های دنیا">
    <meta name="keywords" content="کلون صدا, voice cloning, AI, هوش مصنوعی, فارسی, Persian, رایگان, free">
    <meta name="author" content="Voice Cloning Team">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://yourwebsite.com/">
    <meta property="og:title" content="کلون صدا - Voice Cloning">
    <meta property="og:description" content="یک وب‌سایت کاملاً رایگان برای کلون کردن صدا با هوش مصنوعی">
    <meta property="og:image" content="https://yourwebsite.com/assets/images/og-image.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://yourwebsite.com/">
    <meta property="twitter:title" content="کلون صدا - Voice Cloning">
    <meta property="twitter:description" content="یک وب‌سایت کاملاً رایگان برای کلون کردن صدا با هوش مصنوعی">
    <meta property="twitter:image" content="https://yourwebsite.com/assets/images/og-image.png">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <h1><i class="fas fa-microphone-alt"></i> کلون صدا</h1>
                <p>هوش مصنوعی پیشرفته برای کلون کردن صدا با دقت بالا</p>
                <div class="header-controls">
                    <button id="themeToggle" class="theme-toggle">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div class="language-selector">
                        <select id="languageSelect">
                            <option value="fa">فارسی</option>
                            <option value="en">English</option>
                            <option value="ar">العربية</option>
                            <option value="es">Español</option>
                            <option value="fr">Français</option>
                            <option value="de">Deutsch</option>
                            <option value="it">Italiano</option>
                            <option value="pt">Português</option>
                            <option value="ru">Русский</option>
                            <option value="zh">中文</option>
                            <option value="ja">日本語</option>
                            <option value="ko">한국어</option>
                            <option value="hi">हिन्दी</option>
                            <option value="tr">Türkçe</option>
                            <option value="nl">Nederlands</option>
                            <option value="sv">Svenska</option>
                            <option value="da">Dansk</option>
                            <option value="no">Norsk</option>
                            <option value="fi">Suomi</option>
                            <option value="pl">Polski</option>
                            <option value="cs">Čeština</option>
                            <option value="sk">Slovenčina</option>
                            <option value="hu">Magyar</option>
                            <option value="ro">Română</option>
                            <option value="bg">Български</option>
                            <option value="hr">Hrvatski</option>
                            <option value="sr">Српски</option>
                            <option value="sl">Slovenščina</option>
                            <option value="et">Eesti</option>
                            <option value="lv">Latviešu</option>
                            <option value="lt">Lietuvių</option>
                            <option value="mt">Malti</option>
                            <option value="ga">Gaeilge</option>
                            <option value="cy">Cymraeg</option>
                            <option value="eu">Euskera</option>
                            <option value="ca">Català</option>
                            <option value="gl">Galego</option>
                            <option value="is">Íslenska</option>
                            <option value="mk">Македонски</option>
                            <option value="sq">Shqip</option>
                            <option value="he">עברית</option>
                            <option value="ur">اردو</option>
                            <option value="bn">বাংলা</option>
                            <option value="ta">தமிழ்</option>
                            <option value="te">తెలుగు</option>
                            <option value="ml">മലയാളം</option>
                            <option value="kn">ಕನ್ನಡ</option>
                            <option value="gu">ગુજરાતી</option>
                            <option value="pa">ਪੰਜਾਬੀ</option>
                            <option value="or">ଓଡ଼ିଆ</option>
                            <option value="as">অসমীয়া</option>
                            <option value="ne">नेपाली</option>
                            <option value="si">සිංහල</option>
                            <option value="my">မြန်မာ</option>
                            <option value="th">ไทย</option>
                            <option value="vi">Tiếng Việt</option>
                            <option value="id">Bahasa Indonesia</option>
                            <option value="ms">Bahasa Melayu</option>
                            <option value="tl">Filipino</option>
                            <option value="sw">Kiswahili</option>
                            <option value="am">አማርኛ</option>
                            <option value="yo">Yorùbá</option>
                            <option value="ig">Igbo</option>
                            <option value="ha">Hausa</option>
                            <option value="zu">isiZulu</option>
                            <option value="af">Afrikaans</option>
                        </select>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Step 1: Voice Upload -->
            <section class="step-section" id="step1">
                <div class="step-header">
                    <h2><span class="step-number">1</span> آپلود یا ضبط صدای مرجع</h2>
                    <p>فایل صوتی خود را آپلود کنید یا مستقیماً ضبط کنید (حداقل 20 دقیقه توصیه می‌شود)</p>
                </div>
                
                <div class="upload-section">
                    <div class="upload-options">
                        <div class="upload-option">
                            <input type="file" id="audioFile" accept="audio/*" hidden>
                            <label for="audioFile" class="upload-btn">
                                <i class="fas fa-upload"></i>
                                آپلود فایل صوتی
                            </label>
                        </div>
                        
                        <div class="upload-option">
                            <button id="recordBtn" class="record-btn">
                                <i class="fas fa-microphone"></i>
                                شروع ضبط
                            </button>
                        </div>
                    </div>
                    
                    <div class="audio-preview" id="audioPreview" style="display: none;">
                        <audio controls id="audioPlayer"></audio>
                        <div class="audio-info">
                            <span id="audioName"></span>
                            <span id="audioDuration"></span>
                        </div>
                    </div>
                    
                    <div class="recording-controls" id="recordingControls" style="display: none;">
                        <div class="recording-indicator">
                            <div class="pulse"></div>
                            <span>در حال ضبط...</span>
                            <span id="recordingTime">00:00</span>
                        </div>
                        <button id="stopRecordBtn" class="stop-btn">
                            <i class="fas fa-stop"></i>
                            توقف ضبط
                        </button>
                    </div>
                </div>
            </section>

            <!-- Step 2: Text Input -->
            <section class="step-section" id="step2">
                <div class="step-header">
                    <h2><span class="step-number">2</span> متن مورد نظر</h2>
                    <p>متنی که می‌خواهید با صدای کلون شده خوانده شود را وارد کنید (تا 100,000 کاراکتر)</p>
                </div>
                
                <div class="text-input-section">
                    <textarea id="inputText" placeholder="متن خود را اینجا وارد کنید..." maxlength="100000"></textarea>
                    <div class="text-info">
                        <span id="charCount">0</span> / 100,000 کاراکتر
                        <button id="clearText" class="clear-btn">
                            <i class="fas fa-trash"></i>
                            پاک کردن
                        </button>
                    </div>
                </div>
            </section>

            <!-- Step 3: Voice Settings -->
            <section class="step-section" id="step3">
                <div class="step-header">
                    <h2><span class="step-number">3</span> تنظیمات صدا</h2>
                    <p>تنظیمات کیفیت و سرعت صدای تولیدی را انتخاب کنید</p>
                </div>
                
                <div class="voice-settings">
                    <div class="setting-group">
                        <label>کیفیت صدا:</label>
                        <select id="voiceQuality">
                            <option value="high">بالا (توصیه می‌شود)</option>
                            <option value="medium">متوسط</option>
                            <option value="low">پایین (سریع‌تر)</option>
                        </select>
                    </div>
                    
                    <div class="setting-group">
                        <label>سرعت گفتار:</label>
                        <input type="range" id="speechSpeed" min="0.5" max="2" step="0.1" value="1">
                        <span id="speedValue">1.0x</span>
                    </div>
                    
                    <div class="setting-group">
                        <label>تن صدا:</label>
                        <input type="range" id="voicePitch" min="0.5" max="1.5" step="0.1" value="1">
                        <span id="pitchValue">عادی</span>
                    </div>
                </div>
            </section>

            <!-- Step 4: Generate -->
            <section class="step-section" id="step4">
                <div class="generate-section">
                    <button id="generateBtn" class="generate-btn" disabled>
                        <i class="fas fa-magic"></i>
                        تولید صدای کلون شده
                    </button>
                    
                    <div class="progress-section" id="progressSection" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                        <div class="progress-text">
                            <span id="progressText">در حال تولید صدا...</span>
                            <span id="progressPercent">0%</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Step 5: Result -->
            <section class="step-section" id="step5" style="display: none;">
                <div class="step-header">
                    <h2><span class="step-number">5</span> نتیجه</h2>
                    <p>صدای کلون شده آماده است!</p>
                </div>
                
                <div class="result-section">
                    <div class="result-audio">
                        <audio controls id="resultPlayer"></audio>
                        <div class="result-actions">
                            <button id="downloadBtn" class="download-btn">
                                <i class="fas fa-download"></i>
                                دانلود فایل صوتی
                            </button>
                            <button id="regenerateBtn" class="regenerate-btn">
                                <i class="fas fa-redo"></i>
                                تولید مجدد
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <p>&copy; 2024 کلون صدا - رایگان و بدون محدودیت</p>
            <p>پشتیبانی از 99% زبان‌های دنیا با تمرکز بر زبان فارسی</p>
        </footer>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>