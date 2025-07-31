<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Configuration
define('UPLOAD_DIR', '../uploads/');
define('OUTPUT_DIR', '../output/');
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // 100MB
define('MAX_TEXT_LENGTH', 100000);

// Create directories if they don't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
if (!file_exists(OUTPUT_DIR)) {
    mkdir(OUTPUT_DIR, 0755, true);
}

class VoiceCloningAPI {
    private $supportedLanguages;
    private $supportedFormats;
    
    public function __construct() {
        $this->supportedLanguages = [
            'fa' => 'Persian/Farsi',
            'en' => 'English',
            'ar' => 'Arabic',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'zh' => 'Chinese',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'hi' => 'Hindi',
            'tr' => 'Turkish',
            'nl' => 'Dutch',
            'sv' => 'Swedish',
            'da' => 'Danish',
            'no' => 'Norwegian',
            'fi' => 'Finnish',
            'pl' => 'Polish',
            'cs' => 'Czech',
            'sk' => 'Slovak',
            'hu' => 'Hungarian',
            'ro' => 'Romanian',
            'bg' => 'Bulgarian',
            'hr' => 'Croatian',
            'sr' => 'Serbian',
            'sl' => 'Slovenian',
            'et' => 'Estonian',
            'lv' => 'Latvian',
            'lt' => 'Lithuanian',
            'mt' => 'Maltese',
            'ga' => 'Irish',
            'cy' => 'Welsh',
            'eu' => 'Basque',
            'ca' => 'Catalan',
            'gl' => 'Galician',
            'is' => 'Icelandic',
            'mk' => 'Macedonian',
            'sq' => 'Albanian',
            'he' => 'Hebrew',
            'ur' => 'Urdu',
            'bn' => 'Bengali',
            'ta' => 'Tamil',
            'te' => 'Telugu',
            'ml' => 'Malayalam',
            'kn' => 'Kannada',
            'gu' => 'Gujarati',
            'pa' => 'Punjabi',
            'or' => 'Odia',
            'as' => 'Assamese',
            'ne' => 'Nepali',
            'si' => 'Sinhala',
            'my' => 'Myanmar',
            'th' => 'Thai',
            'vi' => 'Vietnamese',
            'id' => 'Indonesian',
            'ms' => 'Malay',
            'tl' => 'Filipino',
            'sw' => 'Swahili',
            'am' => 'Amharic',
            'yo' => 'Yoruba',
            'ig' => 'Igbo',
            'ha' => 'Hausa',
            'zu' => 'Zulu',
            'af' => 'Afrikaans'
        ];
        
        $this->supportedFormats = ['mp3', 'wav', 'm4a', 'aac', 'ogg', 'webm'];
    }
    
    public function processRequest() {
        try {
            // Validate request method
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Only POST method is allowed');
            }
            
            // Validate and process input
            $audioFile = $this->validateAudioFile();
            $text = $this->validateText();
            $language = $this->validateLanguage();
            $settings = $this->validateSettings();
            
            // Process the voice cloning
            $result = $this->cloneVoice($audioFile, $text, $language, $settings);
            
            $this->sendResponse([
                'success' => true,
                'message' => 'Voice cloned successfully',
                'audio_url' => $result['audio_url'],
                'duration' => $result['duration'],
                'file_size' => $result['file_size'],
                'language' => $language,
                'processing_time' => $result['processing_time']
            ]);
            
        } catch (Exception $e) {
            $this->sendResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    private function validateAudioFile() {
        if (!isset($_FILES['audio']) || $_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('No audio file uploaded or upload error');
        }
        
        $file = $_FILES['audio'];
        
        // Check file size
        if ($file['size'] > MAX_FILE_SIZE) {
            throw new Exception('File size exceeds maximum limit of 100MB');
        }
        
        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowedMimes = [
            'audio/mpeg',
            'audio/mp3',
            'audio/wav',
            'audio/x-wav',
            'audio/m4a',
            'audio/aac',
            'audio/ogg',
            'audio/webm'
        ];
        
        if (!in_array($mimeType, $allowedMimes)) {
            throw new Exception('Unsupported audio format. Please use MP3, WAV, M4A, AAC, OGG, or WebM');
        }
        
        // Move uploaded file
        $filename = uniqid('audio_') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filepath = UPLOAD_DIR . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            throw new Exception('Failed to save uploaded file');
        }
        
        return [
            'path' => $filepath,
            'filename' => $filename,
            'size' => $file['size'],
            'mime_type' => $mimeType
        ];
    }
    
    private function validateText() {
        $text = $_POST['text'] ?? '';
        $text = trim($text);
        
        if (empty($text)) {
            throw new Exception('Text is required');
        }
        
        if (strlen($text) > MAX_TEXT_LENGTH) {
            throw new Exception('Text exceeds maximum length of ' . number_format(MAX_TEXT_LENGTH) . ' characters');
        }
        
        return $text;
    }
    
    private function validateLanguage() {
        $language = $_POST['language'] ?? 'fa';
        
        if (!isset($this->supportedLanguages[$language])) {
            throw new Exception('Unsupported language');
        }
        
        return $language;
    }
    
    private function validateSettings() {
        $quality = $_POST['quality'] ?? 'high';
        $speed = floatval($_POST['speed'] ?? 1.0);
        $pitch = floatval($_POST['pitch'] ?? 1.0);
        
        // Validate quality
        if (!in_array($quality, ['low', 'medium', 'high'])) {
            $quality = 'high';
        }
        
        // Validate speed (0.5x to 2.0x)
        if ($speed < 0.5 || $speed > 2.0) {
            $speed = 1.0;
        }
        
        // Validate pitch (0.5 to 1.5)
        if ($pitch < 0.5 || $pitch > 1.5) {
            $pitch = 1.0;
        }
        
        return [
            'quality' => $quality,
            'speed' => $speed,
            'pitch' => $pitch
        ];
    }
    
    private function cloneVoice($audioFile, $text, $language, $settings) {
        $startTime = microtime(true);
        
        // Simulate processing time based on text length and quality
        $baseProcessingTime = strlen($text) / 1000; // 1 second per 1000 characters
        $qualityMultiplier = ['low' => 0.5, 'medium' => 1.0, 'high' => 1.5][$settings['quality']];
        $processingTime = $baseProcessingTime * $qualityMultiplier;
        
        // Add some randomness to make it more realistic
        $processingTime += rand(2, 8);
        
        // For demo purposes, we'll simulate the processing
        // In a real implementation, this would integrate with actual AI voice cloning services
        $this->simulateProcessing($processingTime);
        
        // Generate output filename
        $outputFilename = uniqid('cloned_voice_') . '.wav';
        $outputPath = OUTPUT_DIR . $outputFilename;
        
        // For demonstration, create a simple audio file or copy a sample
        // In production, this would be the actual cloned voice output
        $this->generateDemoAudio($outputPath, $text, $language, $settings);
        
        $endTime = microtime(true);
        $actualProcessingTime = round($endTime - $startTime, 2);
        
        // Get file info
        $fileSize = file_exists($outputPath) ? filesize($outputPath) : 0;
        $duration = $this->estimateAudioDuration($text, $settings['speed']);
        
        // Clean up input file
        if (file_exists($audioFile['path'])) {
            unlink($audioFile['path']);
        }
        
        return [
            'audio_url' => 'output/' . $outputFilename,
            'duration' => $duration,
            'file_size' => $fileSize,
            'processing_time' => $actualProcessingTime
        ];
    }
    
    private function simulateProcessing($seconds) {
        // Simulate processing by sleeping
        // In production, this would be replaced with actual AI processing
        usleep($seconds * 1000000);
    }
    
    private function generateDemoAudio($outputPath, $text, $language, $settings) {
        // For demonstration purposes, we'll create a simple sine wave audio file
        // In production, this would be replaced with actual voice cloning
        
        $duration = $this->estimateAudioDuration($text, $settings['speed']);
        $sampleRate = 44100;
        $channels = 1;
        $bitsPerSample = 16;
        
        $samples = $duration * $sampleRate;
        $frequency = 440; // A4 note as demo
        
        // Adjust frequency based on pitch setting
        $frequency *= $settings['pitch'];
        
        // Create WAV header
        $header = pack('V', 0x46464952); // "RIFF"
        $header .= pack('V', 36 + $samples * $channels * $bitsPerSample / 8); // File size
        $header .= pack('V', 0x45564157); // "WAVE"
        $header .= pack('V', 0x20746d66); // "fmt "
        $header .= pack('V', 16); // Subchunk1Size
        $header .= pack('v', 1); // AudioFormat (PCM)
        $header .= pack('v', $channels); // NumChannels
        $header .= pack('V', $sampleRate); // SampleRate
        $header .= pack('V', $sampleRate * $channels * $bitsPerSample / 8); // ByteRate
        $header .= pack('v', $channels * $bitsPerSample / 8); // BlockAlign
        $header .= pack('v', $bitsPerSample); // BitsPerSample
        $header .= pack('V', 0x61746164); // "data"
        $header .= pack('V', $samples * $channels * $bitsPerSample / 8); // Subchunk2Size
        
        // Generate audio data (simple sine wave for demo)
        $audioData = '';
        for ($i = 0; $i < $samples; $i++) {
            $sample = sin(2 * M_PI * $frequency * $i / $sampleRate) * 32767 * 0.1; // Low volume
            $audioData .= pack('s', $sample);
        }
        
        // Write to file
        file_put_contents($outputPath, $header . $audioData);
        
        // Add metadata comment for languages (especially Persian)
        $this->addLanguageMetadata($outputPath, $language, $text);
    }
    
    private function addLanguageMetadata($filePath, $language, $text) {
        // Add language-specific processing notes
        // This is where you would add special handling for Persian and other languages
        
        $metadata = [
            'language' => $language,
            'text_sample' => substr($text, 0, 100),
            'processing_notes' => $this->getLanguageProcessingNotes($language),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Save metadata alongside audio file
        $metadataFile = $filePath . '.meta.json';
        file_put_contents($metadataFile, json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    
    private function getLanguageProcessingNotes($language) {
        $notes = [
            'fa' => 'Persian language processing with RTL text support and Persian phonetics',
            'ar' => 'Arabic language processing with RTL text support and Arabic phonetics',
            'he' => 'Hebrew language processing with RTL text support and Hebrew phonetics',
            'ur' => 'Urdu language processing with RTL text support and Urdu phonetics',
            'en' => 'English language processing with advanced phonetic analysis',
            'default' => 'Multi-language processing with automatic language detection'
        ];
        
        return $notes[$language] ?? $notes['default'];
    }
    
    private function estimateAudioDuration($text, $speed) {
        // Estimate duration based on text length and speaking speed
        // Average speaking rate: 150-200 words per minute
        $words = str_word_count($text);
        $baseWPM = 175; // words per minute
        $adjustedWPM = $baseWPM * $speed;
        
        $durationMinutes = $words / $adjustedWPM;
        $durationSeconds = $durationMinutes * 60;
        
        return max(1, round($durationSeconds)); // Minimum 1 second
    }
    
    private function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    public function cleanup() {
        // Clean up old files (older than 1 hour)
        $this->cleanupDirectory(UPLOAD_DIR, 3600);
        $this->cleanupDirectory(OUTPUT_DIR, 3600);
    }
    
    private function cleanupDirectory($dir, $maxAge) {
        if (!is_dir($dir)) return;
        
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $filePath = $dir . $file;
            if (is_file($filePath) && (time() - filemtime($filePath)) > $maxAge) {
                unlink($filePath);
            }
        }
    }
}

// Handle the request
try {
    $api = new VoiceCloningAPI();
    
    // Clean up old files periodically
    if (rand(1, 100) === 1) { // 1% chance to run cleanup
        $api->cleanup();
    }
    
    $api->processRequest();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>