#!/usr/bin/env python3
import re

css_file = "/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/themes/medicross/assets/css/style.css"

print("Reading CSS file...")
with open(css_file, 'r', encoding='utf-8', errors='ignore') as f:
    content = f.read()

# Split into lines for processing
lines = content.split('\n')
print(f"Total lines: {len(lines)}")

# Track problematic lines
problematic_lines = []
for i, line in enumerate(lines):
    if len(line) > 5000:
        problematic_lines.append((i+1, len(line)))
        print(f"Line {i+1}: {len(line)} characters")

# Process the file
fixed_lines = []
for i, line in enumerate(lines):
    line_num = i + 1
    
    # Fix gradient issues
    if ', , )' in line:
        line = line.replace(', , )', ', transparent, transparent)')
        print(f"Fixed line {line_num}: Replaced empty gradient colors")
    
    if "startColorStr='', endColorStr=''" in line:
        line = line.replace("startColorStr='', endColorStr=''", 
                          "startColorStr='#00000000', endColorStr='#00000000'")
        print(f"Fixed line {line_num}: Fixed empty filter colors")
    
    # Handle extremely long lines (potential selector issues)
    if len(line) > 5000:
        # Check if this looks like a CSS selector line
        if ('.' in line or '#' in line or '[' in line) and '{' not in line and '}' not in line:
            # This is likely a malformed mega-selector
            # Try to find where it should end
            if i+1 < len(lines) and lines[i+1].strip().startswith('font-family:'):
                # This selector is missing its opening brace
                line = line + ' {'
                print(f"Fixed line {line_num}: Added missing opening brace to long selector")
            else:
                # This line is too corrupted, comment it out
                print(f"Commenting out corrupted line {line_num} ({len(line)} chars)")
                line = f"/* Line {line_num} commented out due to corruption - {len(line)} characters */\n/* Original content was a malformed selector list */"
    
    fixed_lines.append(line)

# Join lines back
fixed_content = '\n'.join(fixed_lines)

# Write the fixed file
print("\nWriting fixed CSS file...")
with open(css_file, 'w', encoding='utf-8') as f:
    f.write(fixed_content)

print("CSS file has been fixed!")
print("\nSummary of extremely long lines that were processed:")
for line_num, length in problematic_lines:
    print(f"  Line {line_num}: {length:,} characters")