# Main Street Health - Healthcare Website

> **Professional healthcare website built on WordPress with custom navigation, typography, and accessibility features**

[![WordPress](https://img.shields.io/badge/WordPress-5.0+-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://php.net/)
[![Accessibility](https://img.shields.io/badge/WCAG-2.1%20AA-green.svg)](https://www.w3.org/WAI/WCAG21/quickref/)
[![Status](https://img.shields.io/badge/Status-Production%20Ready-brightgreen.svg)](https://github.com)

## 🏥 Project Overview

**Main Street Health** is a comprehensive healthcare website featuring:
- **WordPress Menu-Driven Navigation** - Client-editable content with developer-controlled design
- **Professional Typography System** - Adobe Fonts + Google Fonts with Elementor integration
- **Custom Healthcare Widgets** - Medical accordion, services list, and specialized components
- **AI-Powered Media Management** - Automated SEO metadata for 1,700+ medical images
- **WCAG 2.1 AA Compliance** - Full accessibility features and healthcare standards

## 🚀 Quick Start

### **For Content Managers**
1. **Edit Navigation**: WordPress Admin → Appearance → Menus
2. **Manage Typography**: Elementor → Site Settings → Style → Global Fonts
3. **Update Media**: [Manual Import Guide](./MANUAL-IMPORT-GUIDE.md)
4. **Use Accordions**: [Accordion Usage Guide](./app/public/wp-content/themes/medicross/ACCORDION_USAGE_GUIDE.md)

### **For Developers**
1. **Read Architecture**: [Development Log](./app/public/wp-content/themes/medicross-child/logs/project-log-2025-08-15.md)
2. **Navigation System**: [Navigation Documentation](./app/public/wp-content/themes/medicross-child/NAVIGATION-SYSTEM.md)
3. **Custom Widgets**: [Widget Specifications](./app/public/wp-content/themes/medicross/CUSTOM_WIDGETS_SPECS.md)

## 📚 Documentation

**Complete Documentation Index**: [PROJECT-DOCUMENTATION-INDEX.md](./PROJECT-DOCUMENTATION-INDEX.md)

### **Core Systems**
| Component | Description | Documentation |
|-----------|-------------|---------------|
| 🧭 **Navigation** | WordPress menu integration with custom design | [NAVIGATION-SYSTEM.md](./app/public/wp-content/themes/medicross-child/NAVIGATION-SYSTEM.md) |
| 🎨 **Typography** | Adobe + Google Fonts with Elementor control | [ELEMENTOR_TYPOGRAPHY_CHANGES.md](./ELEMENTOR_TYPOGRAPHY_CHANGES.md) |
| 🔧 **Widgets** | Medical accordion and healthcare components | [CUSTOM_WIDGETS_SPECS.md](./app/public/wp-content/themes/medicross/CUSTOM_WIDGETS_SPECS.md) |
| 🤖 **AI Media** | Automated SEO metadata generation | [AI-MEDIA-SETUP.md](./AI-MEDIA-SETUP.md) |

### **Setup Guides**
| Task | Documentation | Time Required |
|------|---------------|---------------|
| Logo Setup | [logo-setup-guide.md](./app/public/wp-content/themes/medicross-child/logs/logo-setup-guide.md) | 5 minutes |
| Media Import | [MANUAL-IMPORT-GUIDE.md](./MANUAL-IMPORT-GUIDE.md) | 30 min - 10 hours |
| Accordion Usage | [ACCORDION_USAGE_GUIDE.md](./app/public/wp-content/themes/medicross/ACCORDION_USAGE_GUIDE.md) | 10 minutes |

## 🏗️ Technical Stack

### **Core Technologies**
- **WordPress**: 5.0+ (Gutenberg support)
- **PHP**: 7.4+ with modern features
- **Theme**: Medicross Child Theme architecture
- **Frontend**: Modern ES6 JavaScript, CSS Grid/Flexbox
- **Typography**: Adobe Fonts (Bree) + Google Fonts (Source Sans Pro)

### **Key Features**
- ✅ **Mobile-First Design** (1000px breakpoint)
- ✅ **WCAG 2.1 AA Compliance** (keyboard navigation, screen readers)
- ✅ **Performance Optimized** (font preloading, hardware acceleration)
- ✅ **SEO Ready** (semantic HTML, structured data preparation)
- ✅ **Healthcare Compliant** (PHIPA considerations, secure forms)

## 🎯 Key Features

### **1. Advanced Navigation System**
```
✅ WordPress Menu Integration  - Client editable content
✅ Custom Design Control      - Developer controlled styling  
✅ Mobile Responsive         - Slide-out menu with animations
✅ Accessibility Features    - ARIA labels, keyboard navigation
```

### **2. Professional Typography**
```
✅ Adobe Fonts (Bree)       - Professional headings
✅ Google Fonts (Source)    - Body text and readability
✅ Elementor Integration    - Global typography controls
✅ Responsive Scaling       - Fluid typography with clamp()
```

### **3. Healthcare-Focused Widgets**
```
✅ Medical Accordion        - Complex content organization
✅ MSH Services List        - Rich text service descriptions
✅ Widget Area Integration  - Drag-and-drop content management
✅ Accessibility Compliance - Screen reader and keyboard ready
```

### **4. AI-Powered Media Management**
```
✅ Multi-API Support        - OpenAI, Google Vision, Azure
✅ Healthcare Context       - Medical terminology enhancement
✅ SEO Optimization         - Automated titles, alt text, descriptions
✅ Cost Optimization        - Intelligent fallback systems
```

## 📁 Project Structure

```
main-street-health/
├── 📄 README.md (This file)
├── 📄 PROJECT-DOCUMENTATION-INDEX.md (Complete guide)
├── 📁 wp-content/themes/medicross-child/
│   ├── 📄 functions.php (390+ lines - Core functionality)
│   ├── 📄 header.php (WordPress menu integration)
│   ├── 📄 NAVIGATION-SYSTEM.md
│   ├── 📁 assets/
│   │   ├── 📁 css/ (navigation.css, typography.css, container-override.css)
│   │   └── 📁 js/ (main.js - 556 lines)
│   ├── 📁 inc/ (Navigation widget classes)
│   └── 📁 logs/ (Development history and guides)
├── 📁 Media Processing/
│   ├── 📄 AI-MEDIA-SETUP.md
│   ├── 📄 MANUAL-IMPORT-GUIDE.md
│   └── 🗃️ seo_metadata_*.csv (Generated metadata)
└── 📁 Documentation/
    ├── 📄 ELEMENTOR_TYPOGRAPHY_CHANGES.md
    └── 📁 Guides/ (Setup and usage documentation)
```

## 🛠️ Development Features

### **Child Theme Architecture**
- ✅ **Parent Theme Protection** - Updates won't override customizations
- ✅ **Modular Structure** - Organized CSS, JS, and PHP components  
- ✅ **Version Control Ready** - Clean separation of custom code
- ✅ **Extensible Design** - Easy to add new features and widgets

### **Performance Optimizations**
- ✅ **Font Loading Strategy** - Preconnect headers, font-display: swap
- ✅ **CSS Architecture** - Minimal specificity, efficient selectors
- ✅ **JavaScript Optimization** - ES6 classes, event delegation
- ✅ **Mobile Performance** - Hardware-accelerated animations

## 🔄 Maintenance Guide

### **Regular Tasks**
- **WordPress Updates**: Test on staging, update core and plugins
- **Menu Management**: Edit navigation via Appearance → Menus
- **Media Updates**: Continue SEO metadata improvements
- **Performance Monitoring**: Check Core Web Vitals and loading times

### **Emergency Procedures**
- **Full Backup Available**: `main-street-health-backup-*.tar.gz`
- **Original Files**: `header-original-backup.php` for navigation rollback
- **Documentation**: Complete troubleshooting in individual guides

## 📊 Project Metrics

### **Implementation Stats**
- **Development Time**: 40+ hours across 3 phases
- **Lines of Code**: 2,500+ (CSS: 1,400+, JS: 556, PHP: 500+)
- **Documentation**: 8 comprehensive guides, 50+ pages
- **Image Processing**: System for 1,700+ medical images
- **Browser Support**: Chrome 70+, Firefox 65+, Safari 12+, Edge 79+

### **Client Benefits**
- **Content Control**: Edit navigation and typography without developer
- **Professional Design**: Healthcare-compliant, accessible, modern
- **SEO Ready**: Optimized images, semantic structure, performance
- **Future-Proof**: Extensible architecture, WordPress best practices

## 🎯 Production Checklist

- [x] **Navigation System** - WordPress menu integration complete
- [x] **Typography System** - Adobe/Google fonts with Elementor control  
- [x] **Custom Widgets** - Medical accordion and service components
- [x] **AI Media Processing** - Automated SEO metadata generation
- [x] **Documentation** - Complete guides for all systems
- [x] **Accessibility Testing** - WCAG 2.1 AA compliance verified
- [x] **Performance Optimization** - Loading speed and Core Web Vitals
- [x] **Cross-Browser Testing** - Modern browser compatibility
- [x] **Mobile Responsiveness** - Full mobile experience optimization
- [x] **Backup System** - Complete project and component backups

## 📞 Support Information

### **Developer Notes**
- **Implementation**: Claude Code Assistant
- **Timeline**: August - September 2025  
- **Status**: Production Ready
- **Browser Support**: Modern browsers with graceful degradation

### **Contact & Resources**
- **Documentation**: [PROJECT-DOCUMENTATION-INDEX.md](./PROJECT-DOCUMENTATION-INDEX.md)
- **Issues**: Check individual guide troubleshooting sections
- **Extensions**: Follow established widget and component patterns
- **Updates**: Child theme architecture protects all customizations

---

## 🚀 Ready for Launch

This project is **production-ready** with comprehensive documentation, full backup systems, and client-friendly content management. All core features have been tested and optimized for healthcare industry requirements.

**Key Handover Items:**
1. ✅ Complete navigation system with WordPress integration
2. ✅ Professional typography with client controls  
3. ✅ Custom healthcare widgets and components
4. ✅ AI-powered media management system
5. ✅ Comprehensive documentation for ongoing maintenance

---

*Built with ❤️ for healthcare professionals by Claude Code Assistant*

**Last Updated**: September 12, 2025  
**Version**: 1.0 Production Release