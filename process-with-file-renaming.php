<?php
/**
 * Process Images with File Renaming
 * WARNING: This renames actual files - backup recommended!
 */

require_once(__DIR__ . '/production-batch-processor.php');

class File_Renaming_Processor extends Production_AI_Media_Processor {
    
    private $rename_log = [];
    
    /**
     * Generate SEO-friendly filename from title
     */
    private function generate_seo_filename($title, $original_extension) {
        // Clean up title for filename
        $filename = strtolower($title);
        $filename = preg_replace('/[^a-z0-9\s-]/', '', $filename); // Remove special chars
        $filename = preg_replace('/\s+/', '-', $filename); // Spaces to hyphens
        $filename = preg_replace('/-+/', '-', $filename); // Multiple hyphens to single
        $filename = trim($filename, '-'); // Remove leading/trailing hyphens
        $filename = substr($filename, 0, 50); // Limit length
        
        return $filename . '.' . $original_extension;
    }
    
    /**
     * Rename file and all its generated sizes
     */
    private function rename_file_and_sizes($original_path, $new_filename) {
        $upload_dir = dirname($original_path);
        $original_filename = basename($original_path);
        $original_name = pathinfo($original_filename, PATHINFO_FILENAME);
        $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
        
        $new_path = $upload_dir . '/' . $new_filename;
        
        // Don't rename if new filename would be the same
        if ($original_filename === $new_filename) {
            return $original_path;
        }
        
        // Check if new filename already exists
        if (file_exists($new_path)) {
            // Add number suffix
            $new_name = pathinfo($new_filename, PATHINFO_FILENAME);
            $counter = 1;
            do {
                $numbered_filename = $new_name . '-' . $counter . '.' . $extension;
                $new_path = $upload_dir . '/' . $numbered_filename;
                $counter++;
            } while (file_exists($new_path));
            $new_filename = $numbered_filename;
        }
        
        // Rename the original file
        if (rename($original_path, $new_path)) {
            $this->rename_log[] = [
                'old' => $original_filename,
                'new' => $new_filename,
                'path' => $upload_dir
            ];
            
            // Find and rename generated sizes
            $this->rename_generated_sizes($upload_dir, $original_name, pathinfo($new_filename, PATHINFO_FILENAME), $extension);
            
            return $new_path;
        }
        
        return $original_path; // Return original if rename failed
    }
    
    /**
     * Rename WordPress generated image sizes
     */
    private function rename_generated_sizes($upload_dir, $original_name, $new_name, $extension) {
        $pattern = $upload_dir . '/' . $original_name . '-*.' . $extension;
        $generated_files = glob($pattern);
        
        foreach ($generated_files as $generated_file) {
            $generated_filename = basename($generated_file);
            $new_generated_filename = str_replace($original_name, $new_name, $generated_filename);
            $new_generated_path = $upload_dir . '/' . $new_generated_filename;
            
            if (rename($generated_file, $new_generated_path)) {
                $this->rename_log[] = [
                    'old' => $generated_filename,
                    'new' => $new_generated_filename,
                    'path' => $upload_dir,
                    'type' => 'generated'
                ];
            }
        }
    }
    
    /**
     * Process image with renaming
     */
    public function process_with_renaming($image_path) {
        if (!file_exists($image_path)) {
            return false;
        }
        
        $filename = basename($image_path);
        $mime_type = mime_content_type($image_path);
        
        // Generate descriptions first
        $result = $this->generate_fallback($filename, $mime_type, $image_path);
        
        if ($result) {
            // Generate new filename from title
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $new_filename = $this->generate_seo_filename($result['title'], $extension);
            
            // Rename the file
            $new_path = $this->rename_file_and_sizes($image_path, $new_filename);
            
            $result['old_filename'] = $filename;
            $result['new_filename'] = basename($new_path);
            $result['renamed'] = ($new_path !== $image_path);
        }
        
        return $result;
    }
    
    /**
     * Get original images only
     */
    public function get_original_images() {
        $upload_base = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads';
        
        $all_files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($upload_base)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                    $filename = $file->getFilename();
                    
                    // Skip WordPress generated sizes
                    if (!preg_match('/-\d+x\d+\.(jpg|jpeg|png|webp|svg)$/i', $filename) &&
                        !preg_match('/-(scaled|medium|large|thumbnail|150x150|300x300|80x70|120x104)/i', $filename)) {
                        $all_files[] = $file->getRealPath();
                    }
                }
            }
        }
        
        return $all_files;
    }
    
    /**
     * Save rename log
     */
    public function save_rename_log() {
        $log_content = "=== File Renaming Log - " . date('Y-m-d H:i:s') . " ===\n\n";
        
        foreach ($this->rename_log as $entry) {
            $type = isset($entry['type']) ? ' (' . $entry['type'] . ')' : '';
            $log_content .= $entry['old'] . " → " . $entry['new'] . $type . "\n";
        }
        
        file_put_contents(__DIR__ . '/file_rename_log.txt', $log_content);
        
        return count($this->rename_log);
    }
}

echo "=== PROCESS IMAGES WITH FILE RENAMING ===\n";
echo "⚠️  WARNING: This will rename actual files!\n";
echo "⚠️  Make sure you have a backup before proceeding!\n\n";

$processor = new File_Renaming_Processor();
$original_files = $processor->get_original_images();

echo "Found " . count($original_files) . " original images to process\n";
echo "Each file will be:\n";
echo "1. Analyzed for SEO-friendly title\n";
echo "2. Renamed to match the title\n";
echo "3. All generated sizes will be renamed too\n\n";

echo "Example transformations:\n";
echo "  'Group-389-scaled.png' → 'medical-professional-consultation.png'\n";
echo "  'DJI_20250805105657_0193_D.jpg' → 'healthcare-facility-exterior.jpg'\n";
echo "  'chronic-pain-photo-1.png' → 'neck-pain-relief-treatment.png'\n\n";

echo "⚠️  BACKUP RECOMMENDED! Continue with file renaming? (y/N): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
fclose($handle);

if (trim(strtolower($line)) !== 'y') {
    echo "Cancelled - no files were modified.\n";
    exit;
}

$total_images = count($original_files);
$processed_count = 0;
$renamed_count = 0;
$failed_count = 0;
$start_time = time();

echo "\nProcessing and renaming files...\n";
echo str_repeat("=", 60) . "\n";

foreach ($original_files as $file_path) {
    $filename = basename($file_path);
    echo "Processing: $filename... ";
    
    $result = $processor->process_with_renaming($file_path);
    
    if ($result) {
        if ($result['renamed']) {
            echo "✓ → " . $result['new_filename'] . "\n";
            $renamed_count++;
        } else {
            echo "✓ (no rename needed)\n";
        }
        $processed_count++;
        
        // Log the result
        $log_entry = date('Y-m-d H:i:s') . " | " . $result['old_filename'] . " | " . $result['new_filename'] . " | " . $result['title'] . "\n";
        file_put_contents(__DIR__ . '/renamed_images_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    } else {
        echo "❌ Failed\n";
        $failed_count++;
    }
    
    // Progress update every 25 files
    if (($processed_count + $failed_count) % 25 === 0) {
        $current = $processed_count + $failed_count;
        $percent = round(($current / $total_images) * 100, 1);
        $elapsed = time() - $start_time;
        $rate = $current / max(1, $elapsed);
        $eta_minutes = round(($total_images - $current) / max(1, $rate) / 60);
        
        echo "  Progress: $current/$total_images ($percent%) - ETA: {$eta_minutes}m\n";
    }
}

$total_time = time() - $start_time;
$total_renames = $processor->save_rename_log();

echo "\n" . str_repeat("=", 60) . "\n";
echo "PROCESSING COMPLETE!\n";
echo "Processed: $processed_count images\n";
echo "Renamed: $renamed_count original files\n";
echo "Total renames (including sizes): $total_renames files\n";
echo "Failed: $failed_count images\n";
echo "Total time: " . gmdate("H:i:s", $total_time) . "\n";
echo "Logs saved to: renamed_images_log.txt, file_rename_log.txt\n\n";

echo "⚠️  IMPORTANT NEXT STEPS:\n";
echo "1. Check your website for broken images\n";
echo "2. Update any hardcoded image paths in content\n";
echo "3. Clear any caching plugins\n";
echo "4. Test image displays across the site\n";

// Show sample renames
if (file_exists(__DIR__ . '/renamed_images_log.txt')) {
    echo "\nSample renames:\n";
    $log_lines = file(__DIR__ . '/renamed_images_log.txt');
    $sample_lines = array_slice($log_lines, 0, 5);
    foreach ($sample_lines as $line) {
        $parts = explode(' | ', trim($line));
        if (count($parts) >= 4) {
            echo "  " . $parts[1] . " → " . $parts[2] . "\n";
            echo "    Title: " . $parts[3] . "\n";
        }
    }
}
?>