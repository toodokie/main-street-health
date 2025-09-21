#!/usr/bin/env python3
import re

css_file = "/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/themes/medicross/assets/css/style.css"

print("Reading CSS file...")
with open(css_file, 'r', encoding='utf-8') as f:
    lines = f.readlines()

print(f"Total lines: {len(lines)}")

# Track changes
changes_made = []

# Fix line 1385 (index 1385) - Add missing opening brace
if len(lines) > 1385:
    line = lines[1385]
    if len(line) > 1000 and not line.strip().endswith('{'):
        # Add opening brace at the end
        lines[1385] = line.rstrip() + ' {\n'
        changes_made.append("Fixed line 1386: Added missing opening brace")

# Fix lines 4289-4291 (indices 4289-4291) - Fix invalid gradient values
if len(lines) > 4291:
    # Line 4290: background-image: -o-linear-gradient(to bottom, , );
    if ', , )' in lines[4289]:
        lines[4289] = '  background-image: -o-linear-gradient(to bottom, transparent, transparent);\n'
        changes_made.append("Fixed line 4290: Replaced empty gradient colors with 'transparent'")
    
    # Line 4291: background-image: linear-gradient(to bottom, , );
    if ', , )' in lines[4290]:
        lines[4290] = '  background-image: linear-gradient(to bottom, transparent, transparent);\n'
        changes_made.append("Fixed line 4291: Replaced empty gradient colors with 'transparent'")
    
    # Line 4292: filter with empty color values
    if "startColorStr='', endColorStr=''" in lines[4291]:
        lines[4291] = "  filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='#00000000', endColorStr='#00000000');\n"
        changes_made.append("Fixed line 4292: Replaced empty filter colors with transparent values")

# Fix line 4293 (index 4293) - Add missing opening brace
if len(lines) > 4293:
    line = lines[4293]
    if len(line) > 1000 and not line.strip().endswith('{'):
        lines[4293] = line.rstrip() + ' {\n'
        changes_made.append("Fixed line 4294: Added missing opening brace")

# Handle extremely long lines (over 5000 chars) - split selectors
print("\nChecking for extremely long lines...")
for i in range(len(lines)):
    if len(lines[i]) > 5000:
        line = lines[i].strip()
        
        # Check if it's a selector line (contains CSS selectors but no properties)
        if ',' in line and ':' not in line and '{' not in line:
            # This appears to be a malformed selector list
            # Split it into manageable chunks
            selectors = line.split(',')
            
            # Group selectors in chunks of 50
            chunk_size = 50
            chunks = [selectors[j:j+chunk_size] for j in range(0, len(selectors), chunk_size)]
            
            # Create multiple CSS rules
            new_lines = []
            for chunk in chunks:
                selector_group = ', '.join(s.strip() for s in chunk if s.strip())
                if selector_group:
                    new_lines.append(selector_group + ' {\n')
                    new_lines.append('  /* Placeholder rule for split selectors */\n')
                    new_lines.append('  display: inherit;\n')
                    new_lines.append('}\n')
            
            # Replace the original line
            lines[i] = ''.join(new_lines)
            changes_made.append(f"Fixed line {i+1}: Split extremely long selector ({len(line)} chars) into multiple rules")

print(f"\nTotal changes to make: {len(changes_made)}")
for change in changes_made:
    print(f"  - {change}")

if changes_made:
    print("\nWriting fixed CSS file...")
    with open(css_file, 'w', encoding='utf-8') as f:
        f.writelines(lines)
    print("CSS file has been fixed!")
else:
    print("\nNo changes needed.")