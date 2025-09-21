# Manual WordPress Media Import Guide

> **Part of**: [Main Street Health Project Documentation](./PROJECT-DOCUMENTATION-INDEX.md)  
> **Related**: [AI Media Setup](./AI-MEDIA-SETUP.md)

## Overview

Step-by-step guide for manually updating WordPress media metadata with SEO-optimized titles, alt text, and descriptions. Includes priority-based approach for 606 images with time estimates.

## Priority Images to Update First

Based on your CSV file, here are the most important images to update manually in WordPress:

### HIGH PRIORITY - Staff & Key Images
1. **Prakash-Patel-1.png**
   - Title: "Prakash Patel"
   - Alt Text: "Prakash Patel" 
   - Description: "Prakash Patel in modern medical environment at Main Street Health"

2. **Emerito-Reyes-1.png**
   - Title: "Emerito Reyes"
   - Alt Text: "Emerito Reyes"
   - Description: "Professional Emerito Reyes at Main Street Health medical facility"

3. **Nicole-Jenkins-1.png**
   - Title: "Nicole Jenkins"
   - Alt Text: "Nicole Jenkins"
   - Description: "Nicole Jenkins providing quality healthcare services at Main Street Health"

4. **Raphael-Keelan-1.png**
   - Title: "Raphael Keelan"
   - Alt Text: "Raphael Keelan"
   - Description: "Raphael Keelan in modern medical environment at Main Street Health"

### MEDIUM PRIORITY - Treatment Photos
5. **chronic-pain-photo-1.png**
   - Title: "Chronic Pain Photo"
   - Alt Text: "Chronic Pain Photo"
   - Description: "Professional Chronic Pain Photo at Main Street Health medical facility"

6. **sport-injuries-photo.png**
   - Title: "Sport Injuries Photo"
   - Alt Text: "Sport Injuries Photo"
   - Description: "Sport Injuries Photo providing quality healthcare services at Main Street Health"

7. **work-related-injuries-photo.png**
   - Title: "Work Related Injuries Photo"
   - Alt Text: "Work Related Injuries Photo"
   - Description: "Main Street Health Work Related Injuries Photo for comprehensive patient care"

### LOWER PRIORITY - Logos & Icons
8. **GreenShield_Logo_Green_RGB.png**
   - Title: "Greenshield Logo Green Rgb"
   - Alt Text: "Greenshield Logo Green Rgb"
   - Description: "Greenshield Logo Green Rgb in modern medical environment at Main Street Health"

## How to Update in WordPress

### Step 1: Access Media Library
1. Login to WordPress Admin
2. Go to **Media → Library**
3. Switch to **List View** (easier to search)

### Step 2: Find & Update Each Image
1. **Search** for the image filename (e.g., "Prakash-Patel-1")
2. **Click** on the image name to open the attachment details
3. **Update** the following fields:
   - **Title**: Copy from CSV
   - **Alt Text**: Copy from CSV  
   - **Description**: Copy from CSV
4. **Click "Update"**

### Step 3: Verify Changes
- Check that the title appears in Media Library
- View the image on your website to confirm alt text works
- Use browser dev tools to verify the description is included

## Pro Tips

### Search Tips
- Use partial filenames (e.g., "Prakash" instead of full name)
- Remove file extensions when searching
- Use Chrome's Find function (Ctrl/Cmd+F) on the media page

### Batch Approach
- Update 10-15 images per session
- Start with staff photos (highest SEO impact)
- Move to treatment photos next
- Finish with icons and logos

### Quality Check
After updating, verify:
- ✅ Title shows in media library
- ✅ Alt text appears on hover  
- ✅ Description is populated
- ✅ Images display correctly on site

## CSV File Reference

Your complete metadata is in:
`seo_metadata_2025-09-09_20-39-36.csv`

Open this file in Excel/Google Sheets to:
- Sort by priority
- Copy/paste metadata easily
- Track your progress

## Time Estimate

- **High Priority (8 images)**: ~15 minutes
- **All Staff Photos**: ~30 minutes  
- **Top 50 Images**: ~2 hours
- **All 606 Images**: ~8-10 hours (over multiple sessions)

## Recommended Schedule

**Week 1**: High priority images (staff, key treatments)
**Week 2**: Treatment and service photos
**Week 3**: Logos, icons, and remaining images

This approach gives you immediate SEO benefits while spreading the work over manageable sessions.