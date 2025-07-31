// Voice Cloning Application JavaScript
class VoiceCloningApp {
    constructor() {
        this.mediaRecorder = null;
        this.audioChunks = [];
        this.recordingStartTime = null;
        this.recordingInterval = null;
        this.currentAudioBlob = null;
        this.currentLanguage = 'fa';
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadTheme();
        this.initializeLanguage();
        this.setupValidation();
    }

    setupEventListeners() {
        // Theme toggle
        document.getElementById('themeToggle').addEventListener('click', () => this.toggleTheme());
        
        // Language selector
        document.getElementById('languageSelect').addEventListener('change', (e) => this.changeLanguage(e.target.value));
        
        // Audio upload
        document.getElementById('audioFile').addEventListener('change', (e) => this.handleAudioUpload(e));
        
        // Recording controls
        document.getElementById('recordBtn').addEventListener('click', () => this.startRecording());
        document.getElementById('stopRecordBtn').addEventListener('click', () => this.stopRecording());
        
        // Text input
        document.getElementById('inputText').addEventListener('input', (e) => this.updateCharCount(e.target.value));
        document.getElementById('clearText').addEventListener('click', () => this.clearText());
        
        // Voice settings
        document.getElementById('speechSpeed').addEventListener('input', (e) => this.updateSpeedValue(e.target.value));
        document.getElementById('voicePitch').addEventListener('input', (e) => this.updatePitchValue(e.target.value));
        
        // Generate button
        document.getElementById('generateBtn').addEventListener('click', () => this.generateVoice());
        
        // Result actions
        document.getElementById('downloadBtn').addEventListener('click', () => this.downloadAudio());
        document.getElementById('regenerateBtn').addEventListener('click', () => this.regenerateVoice());
    }

    // Theme Management
    toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        const themeIcon = document.querySelector('#themeToggle i');
        themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    loadTheme() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        const themeIcon = document.querySelector('#themeToggle i');
        themeIcon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    // Language Management
    changeLanguage(language) {
        this.currentLanguage = language;
        this.updateUILanguage(language);
        localStorage.setItem('language', language);
    }

    initializeLanguage() {
        const savedLanguage = localStorage.getItem('language') || 'fa';
        document.getElementById('languageSelect').value = savedLanguage;
        this.currentLanguage = savedLanguage;
        this.updateUILanguage(savedLanguage);
    }

    updateUILanguage(language) {
        // Update direction for RTL languages
        const rtlLanguages = ['fa', 'ar', 'he', 'ur'];
        document.documentElement.dir = rtlLanguages.includes(language) ? 'rtl' : 'ltr';
        document.documentElement.lang = language;
    }

    // Audio Upload Handling
    handleAudioUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (!this.isValidAudioFile(file)) {
            this.showNotification('فرمت فایل صوتی پشتیبانی نمی‌شود. لطفاً فایل MP3، WAV یا M4A انتخاب کنید.', 'error');
            return;
        }

        if (file.size > 100 * 1024 * 1024) { // 100MB limit
            this.showNotification('حجم فایل بیش از حد مجاز است. حداکثر 100 مگابایت مجاز است.', 'error');
            return;
        }

        this.displayAudioPreview(file);
        this.currentAudioBlob = file;
        this.checkFormValidity();
    }

    isValidAudioFile(file) {
        const validTypes = ['audio/mp3', 'audio/mpeg', 'audio/wav', 'audio/m4a', 'audio/aac', 'audio/ogg'];
        return validTypes.includes(file.type);
    }

    displayAudioPreview(file) {
        const preview = document.getElementById('audioPreview');
        const player = document.getElementById('audioPlayer');
        const nameSpan = document.getElementById('audioName');
        const durationSpan = document.getElementById('audioDuration');

        const url = URL.createObjectURL(file);
        player.src = url;
        nameSpan.textContent = file.name;
        
        player.addEventListener('loadedmetadata', () => {
            const duration = this.formatDuration(player.duration);
            durationSpan.textContent = duration;
        });

        preview.style.display = 'block';
        this.showNotification('فایل صوتی با موفقیت آپلود شد!', 'success');
    }

    // Recording Functionality
    async startRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                audio: {
                    echoCancellation: true,
                    noiseSuppression: true,
                    sampleRate: 44100
                } 
            });
            
            this.mediaRecorder = new MediaRecorder(stream, {
                mimeType: 'audio/webm;codecs=opus'
            });
            
            this.audioChunks = [];
            this.recordingStartTime = Date.now();
            
            this.mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    this.audioChunks.push(event.data);
                }
            };
            
            this.mediaRecorder.onstop = () => {
                const audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                this.handleRecordedAudio(audioBlob);
                stream.getTracks().forEach(track => track.stop());
            };
            
            this.mediaRecorder.start(1000); // Record in 1-second chunks
            this.showRecordingControls();
            this.startRecordingTimer();
            
        } catch (error) {
            console.error('Error starting recording:', error);
            this.showNotification('خطا در دسترسی به میکروفون. لطفاً اجازه دسترسی را بدهید.', 'error');
        }
    }

    stopRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.stop();
            this.hideRecordingControls();
            this.stopRecordingTimer();
        }
    }

    handleRecordedAudio(audioBlob) {
        this.currentAudioBlob = audioBlob;
        
        const preview = document.getElementById('audioPreview');
        const player = document.getElementById('audioPlayer');
        const nameSpan = document.getElementById('audioName');
        const durationSpan = document.getElementById('audioDuration');

        const url = URL.createObjectURL(audioBlob);
        player.src = url;
        nameSpan.textContent = 'ضبط شده';
        
        const recordingDuration = (Date.now() - this.recordingStartTime) / 1000;
        durationSpan.textContent = this.formatDuration(recordingDuration);

        preview.style.display = 'block';
        this.checkFormValidity();
        this.showNotification('ضبط صدا با موفقیت تکمیل شد!', 'success');
    }

    showRecordingControls() {
        document.getElementById('recordingControls').style.display = 'block';
        document.querySelector('.upload-options').style.display = 'none';
    }

    hideRecordingControls() {
        document.getElementById('recordingControls').style.display = 'none';
        document.querySelector('.upload-options').style.display = 'flex';
    }

    startRecordingTimer() {
        this.recordingInterval = setInterval(() => {
            const elapsed = (Date.now() - this.recordingStartTime) / 1000;
            document.getElementById('recordingTime').textContent = this.formatDuration(elapsed);
        }, 1000);
    }

    stopRecordingTimer() {
        if (this.recordingInterval) {
            clearInterval(this.recordingInterval);
            this.recordingInterval = null;
        }
    }

    // Text Input Management
    updateCharCount(text) {
        const charCount = document.getElementById('charCount');
        charCount.textContent = text.length.toLocaleString();
        
        if (text.length > 90000) {
            charCount.style.color = 'var(--warning-color)';
        } else if (text.length > 95000) {
            charCount.style.color = 'var(--danger-color)';
        } else {
            charCount.style.color = 'var(--text-secondary)';
        }
        
        this.checkFormValidity();
    }

    clearText() {
        document.getElementById('inputText').value = '';
        this.updateCharCount('');
    }

    // Voice Settings
    updateSpeedValue(value) {
        document.getElementById('speedValue').textContent = parseFloat(value).toFixed(1) + 'x';
    }

    updatePitchValue(value) {
        const pitchLabels = {
            '0.5': 'بسیار پایین',
            '0.6': 'پایین',
            '0.7': 'کمی پایین',
            '0.8': 'نسبتاً پایین',
            '0.9': 'کمی زیر عادی',
            '1.0': 'عادی',
            '1.1': 'کمی بالا',
            '1.2': 'نسبتاً بالا',
            '1.3': 'کمی بالا',
            '1.4': 'بالا',
            '1.5': 'بسیار بالا'
        };
        
        const label = pitchLabels[parseFloat(value).toFixed(1)] || 'عادی';
        document.getElementById('pitchValue').textContent = label;
    }

    // Form Validation
    setupValidation() {
        this.checkFormValidity();
    }

    checkFormValidity() {
        const hasAudio = this.currentAudioBlob !== null;
        const hasText = document.getElementById('inputText').value.trim().length > 0;
        const generateBtn = document.getElementById('generateBtn');
        
        generateBtn.disabled = !(hasAudio && hasText);
    }

    // Voice Generation
    async generateVoice() {
        if (!this.currentAudioBlob || !document.getElementById('inputText').value.trim()) {
            this.showNotification('لطفاً فایل صوتی و متن را وارد کنید.', 'error');
            return;
        }

        this.showProgress();
        
        try {
            const formData = new FormData();
            formData.append('audio', this.currentAudioBlob);
            formData.append('text', document.getElementById('inputText').value);
            formData.append('language', this.currentLanguage);
            formData.append('quality', document.getElementById('voiceQuality').value);
            formData.append('speed', document.getElementById('speechSpeed').value);
            formData.append('pitch', document.getElementById('voicePitch').value);

            const response = await fetch('api/generate_voice.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('خطا در تولید صدا');
            }

            const result = await response.json();
            
            if (result.success) {
                this.displayResult(result.audio_url);
            } else {
                throw new Error(result.message || 'خطا در تولید صدا');
            }
            
        } catch (error) {
            console.error('Error generating voice:', error);
            this.showNotification('خطا در تولید صدا: ' + error.message, 'error');
        } finally {
            this.hideProgress();
        }
    }

    showProgress() {
        document.getElementById('progressSection').style.display = 'block';
        document.getElementById('generateBtn').disabled = true;
        
        let progress = 0;
        const progressFill = document.getElementById('progressFill');
        const progressPercent = document.getElementById('progressPercent');
        const progressText = document.getElementById('progressText');
        
        const messages = [
            'در حال تجزیه و تحلیل صدای مرجع...',
            'استخراج ویژگی‌های صوتی...',
            'آموزش مدل کلون صدا...',
            'تولید صدای کلون شده...',
            'بهینه‌سازی کیفیت صدا...',
            'تکمیل فرآیند...'
        ];
        
        let messageIndex = 0;
        
        const interval = setInterval(() => {
            progress += Math.random() * 15 + 5;
            if (progress > 100) progress = 100;
            
            progressFill.style.width = progress + '%';
            progressPercent.textContent = Math.round(progress) + '%';
            
            if (progress > messageIndex * 16.67 && messageIndex < messages.length - 1) {
                messageIndex++;
                progressText.textContent = messages[messageIndex];
            }
            
            if (progress >= 100) {
                clearInterval(interval);
            }
        }, 300);
        
        this.progressInterval = interval;
    }

    hideProgress() {
        document.getElementById('progressSection').style.display = 'none';
        document.getElementById('generateBtn').disabled = false;
        
        if (this.progressInterval) {
            clearInterval(this.progressInterval);
        }
    }

    displayResult(audioUrl) {
        const resultSection = document.getElementById('step5');
        const resultPlayer = document.getElementById('resultPlayer');
        
        resultPlayer.src = audioUrl;
        resultSection.style.display = 'block';
        
        // Scroll to result
        resultSection.scrollIntoView({ behavior: 'smooth' });
        
        this.showNotification('صدای کلون شده با موفقیت تولید شد!', 'success');
    }

    regenerateVoice() {
        document.getElementById('step5').style.display = 'none';
        this.generateVoice();
    }

    // Download Functionality
    downloadAudio() {
        const resultPlayer = document.getElementById('resultPlayer');
        if (!resultPlayer.src) return;
        
        const link = document.createElement('a');
        link.href = resultPlayer.src;
        link.download = `cloned_voice_${Date.now()}.wav`;
        link.click();
        
        this.showNotification('دانلود فایل صوتی آغاز شد!', 'success');
    }

    // Utility Functions
    formatDuration(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Add styles
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '15px 20px',
            borderRadius: '10px',
            color: 'white',
            fontWeight: '600',
            zIndex: '1000',
            maxWidth: '400px',
            boxShadow: '0 10px 30px rgba(0,0,0,0.3)',
            transform: 'translateX(400px)',
            transition: 'transform 0.3s ease'
        });
        
        // Set background color based on type
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#6366f1'
        };
        notification.style.background = colors[type] || colors.info;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new VoiceCloningApp();
    
    // Add some demo functionality for testing
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        console.log('Voice Cloning App initialized in development mode');
        
        // Add demo text button for testing
        const demoBtn = document.createElement('button');
        demoBtn.textContent = 'متن نمونه';
        demoBtn.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 10px 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        `;
        
        demoBtn.onclick = () => {
            document.getElementById('inputText').value = 'سلام، این یک متن نمونه برای تست سیستم کلون صدا است. این سیستم قادر است صدای شما را با دقت بالا کلون کند و متن‌های مختلف را با لحن و گویش شما بخواند. امیدواریم که از این سرویس رایگان لذت ببرید.';
            document.getElementById('charCount').textContent = document.getElementById('inputText').value.length.toLocaleString();
        };
        
        document.body.appendChild(demoBtn);
    }
});

// Service Worker Registration for PWA capabilities
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => console.log('SW registered'))
            .catch(registrationError => console.log('SW registration failed'));
    });
}