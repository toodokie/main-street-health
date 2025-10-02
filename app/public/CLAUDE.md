# Main Street Health - Image Optimization System

## Project Goal
Create a comprehensive WordPress image optimization and duplicate cleanup tool for Main Street Health chiropractic and physiotherapy clinic in Hamilton, Ontario.

## Business Context
- **Client**: Main Street Health - chiropractic and physiotherapy practice
- **Location**: Hamilton, Ontario
- **Website**: WordPress site with Medicross parent theme + child theme
- **Image Library**: 748 total images, ~47 published images (found by optimized system)

## System Requirements

### Step 1: Image Optimization
Full specification, UX flow, and validation notes now live in `MSH_IMAGE_OPTIMIZER_DOCUMENTATION.md`. Use that doc for feature matrices, workflow details, and upgrade history.

### Step 2: Duplicate Cleanup
Operational run-books, UI states, and dependency notes are documented in `docs/batch-3-4-review-report.md` and the dedicated optimizer manual. Refer there for cleanup heuristics and usage-detection logic.

## Current Status (UPDATED)

### ✅ Status Notes
Key performance fixes, analyzer regressions, and release history are tracked in `MSH_IMAGE_OPTIMIZER_DOCUMENTATION.md` (Step 1) and `docs/batch-3-4-review-report.md` (Step 2 cleanup sprint). This file now only points to the authoritative sources.

## File Structure
```
/wp-content/themes/medicross-child/
├── admin/image-optimizer-admin.php
├── inc/
│   ├── class-msh-image-optimizer.php
│   ├── class-msh-media-cleanup.php
│   └── class-msh-webp-delivery.php
├── assets/
│   ├── css/image-optimizer-admin.css
│   └── js/image-optimizer-admin.js
└── functions.php
```

## Technical Notes
- WordPress child theme approach
- AJAX-powered batch processing
- Custom color palette throughout UI
- Browser WebP detection with cookies
- Healthcare-specific image prioritization
- Nonce security + capability checks

## WebP Verification System
Design goals, coverage metrics, and troubleshooting steps reside in `MSH_IMAGE_OPTIMIZER_DOCUMENTATION.md` under the verification tools section.

## Success Metrics & Next Steps
Active KPIs, optimization backlog, and follow-up instructions are maintained in the optimizer manual (`MSH_IMAGE_OPTIMIZER_DOCUMENTATION.md`) and the batch review report for the duplicate-cleanup sprint. Reference those documents for the canonical task list before picking up new work.

## 🚨 CRITICAL DEVELOPER MEMO - IMAGE OPTIMIZER WORK

**ALWAYS REFERENCE `MSH_IMAGE_OPTIMIZER_DOCUMENTATION.md` BEFORE WORKING ON THE OPTIMIZER**

### Why This Memo Exists
The MSH Image Optimizer is a complex system with numerous edge cases, performance gotchas, and architectural dependencies that have been discovered and documented through months of development. **Failing to consult the documentation leads to repeating solved problems.**

### Before Any Optimizer Work:
1. **READ THE TROUBLESHOOTING SECTION FIRST** - `MSH_IMAGE_OPTIMIZER_DOCUMENTATION.md` contains critical issue patterns
2. **Check the Current Action Plan** - Understand what's in progress vs completed
3. **Review Recent Incident Reports** - Learn from previous debugging sessions

### Key Lessons Documented:
- **AJAX Handler Registration** - Missing `wp_ajax_` action registration causes silent failures
- **Debug Logging Performance** - Excessive `error_log()` calls create massive slowdowns
- **Batch Processing Limits** - Server resource constraints require careful batch sizing
- **Usage Index Dependencies** - Complex database relationships need proper setup
- **Safe Rename System** - URL replacement requires precise sequencing

### Critical Patterns to Avoid:
❌ **Adding AJAX endpoints without registering handlers**
❌ **Using debug logging in production-level code**
❌ **Processing large batches without timeout handling**
❌ **Modifying core indexing logic without understanding dependencies**

### Development Protocol:
1. **Consult documentation** before making changes
2. **Test with small datasets** before processing full media library
3. **Monitor server resources** during intensive operations
4. **Document new issues** discovered during development
5. **Update troubleshooting guide** with solutions found

### Quick Reference Locations:
- **Troubleshooting Guide**: Line ~910 in `MSH_IMAGE_OPTIMIZER_DOCUMENTATION.md`
- **Architecture Overview**: Lines 1-100 in the documentation
- **Performance Benchmarks**: Throughout troubleshooting sections
- **Code Patterns**: Implementation examples in each section
- **📊 Research & Development**: `MSH_IMAGE_OPTIMIZER_RND.md` - Experimental approaches, failed experiments, and optimization research

### Documentation Structure:
- **`MSH_IMAGE_OPTIMIZER_DOCUMENTATION.md`** - Complete implementation guide, troubleshooting, and operational documentation
- **`MSH_IMAGE_OPTIMIZER_RND.md`** - Research findings, experimental code, performance analysis, and failed approaches
- **`CLAUDE.md`** - This file - Project context and development guidelines

**Remember: The documentation exists because these problems were painful to solve the first time. Use it.**
