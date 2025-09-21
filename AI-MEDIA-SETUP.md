# AI-Powered Media Description Generator

> **Part of**: [Main Street Health Project Documentation](./PROJECT-DOCUMENTATION-INDEX.md)  
> **Related**: [Manual Import Guide](./MANUAL-IMPORT-GUIDE.md)

## Overview

Automated SEO metadata generation system for healthcare images using multiple AI APIs with intelligent fallback. Processes 1,700+ images with healthcare-specific context enhancement.

## Quick Start (No API - Intelligent Fallback)

```bash
# Run immediately without any API setup
wp eval-file ai-media-descriptions.php
```

This will use intelligent filename parsing and healthcare context mapping.

## Setup with AI APIs (Recommended)

### Option 1: OpenAI Vision (Best Quality)

1. Get API key from https://platform.openai.com/api-keys
2. Add to `wp-config.php`:
```php
define('OPENAI_API_KEY', 'sk-...');
```

**Cost:** ~$0.01 per image
**Quality:** Excellent - understands medical context

### Option 2: Google Cloud Vision

1. Enable Vision API at https://console.cloud.google.com
2. Create API key
3. Add to `wp-config.php`:
```php
define('GOOGLE_VISION_KEY', 'your-key-here');
```

**Cost:** First 1000/month free, then $1.50 per 1000
**Quality:** Good - detects text, logos, labels

### Option 3: Azure Computer Vision

1. Create Azure account
2. Create Computer Vision resource
3. Add to `wp-config.php`:
```php
define('AZURE_VISION_KEY', 'your-key-here');
```

## Usage

### Process All Images
```bash
wp eval-file ai-media-descriptions.php
```

### Test Single Image
```php
$generator = new AI_Media_Descriptor();
$result = $generator->process_attachment(123); // attachment ID
```

### Custom Batch Size
Edit line in script:
```php
$batch_size = 5; // Change to process more at once
```

## Features

- **Multi-API Support:** Falls back if one fails
- **Healthcare Context:** Medical terminology enhancement
- **SEO Optimized:** Generates title, alt text, and description
- **Rate Limiting:** Prevents API throttling
- **Progress Tracking:** Shows batch progress
- **Resume Support:** Skips already processed images

## What It Generates

For each image:
- **Title:** 5-8 words, SEO-friendly
- **Alt Text:** 10-15 words for accessibility
- **Description:** 20-30 words for SEO
- **Meta Data:** Tracks generation method and date

## Example Output

**Original:** `doctor-consultation-room-2.jpg`

**Generated:**
- Title: "Medical Professional Consultation Room"
- Alt: "Healthcare provider consultation room with modern medical equipment"
- Description: "Professional medical consultation room at Main Street Health facility featuring modern equipment for patient care"

## Cost Estimate

For your 1,711 images:
- OpenAI: ~$17 (highest quality)
- Google: Free (first 1000) + $1.05 
- Fallback: Free (decent quality)

## Monitoring

Check progress in WordPress:
```sql
-- See processed images
SELECT COUNT(*) FROM wp_postmeta 
WHERE meta_key = '_ai_generated_method';

-- See methods used
SELECT meta_value, COUNT(*) 
FROM wp_postmeta 
WHERE meta_key = '_ai_generated_method'
GROUP BY meta_value;
```

## Troubleshooting

### API Errors
- Check API key is correct
- Verify billing is active
- Check rate limits

### Memory Issues
- Reduce batch size
- Run via WP-CLI instead of browser
- Increase PHP memory limit

### Resume After Interrupt
Script automatically skips processed images, just run again:
```bash
wp eval-file ai-media-descriptions.php
```