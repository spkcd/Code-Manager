# Code Manager ![WordPress Plugin Version](https://img.shields.io/badge/Version-1.4.0-blue) ![License](https://img.shields.io/badge/License-GPL--3.0-green)

![Code Manager Interface](assets/banner-1544x500.png)

Professional code snippet management for WordPress, developed by [SPARKWEB Studio](https://sparkwebstudio.com/).

## Features

### Core Functionality
-  **Inline Editing**: Edit snippets directly in the interface   
-  **Default Snippets Package**: Pre-installed curated snippets for common use cases  
-  **Real-time Updates**: Toggle snippets without page reloads  
-  **Version Control**: Track default snippet versions  

### Security
-  **Input Sanitization**: WPCS-compliant code cleaning  
-  **Role-Based Access**: `manage_options` capability required  
-  **Nonce Verification**: All AJAX actions protected  
-  **Audit Logging**: Error tracking for security events  

### Performance
-  **Selective Loading**: Only active snippets on frontend  
-  **Caching**: Versioned asset loading  
-  **Lightweight**: 55KB core footprint  
-  **Efficient Storage**: Single database option  

## Installation
1. **Upload Plugin**: WordPress admin → Plugins → Add New  
2. **Activate**: Default snippets auto-install  
3. **Access**: **Code Manager** in admin sidebar  

## Usage
### Key Actions
-  🛠️ **Edit**: Click any snippet's Edit button  
-  ✅ **Toggle**: Switches update in real-time  
-  🗑️ **Delete**: Removes custom snippets (defaults protected)  
-  🔄 **Restore**: Use *Install Defaults* for system snippets  

![Admin Interface](assets/screenshot-1.png)

### Best Practices
1. **Test First**: Use staging for new snippets  
2. **Name Clearly**: e.g., "Header CSS Optimization"  
3. **Size Limits**: CSS ≤500 lines, JS ≤1000 lines  
4. **Modern JS**: Use IIFE/scoped variables  

## Changelog

### [1.4.0] - 2025-02-17
**Added**  
-  ✨ Inline Snippet Editing  
-  ⏳ UI loading states  
-  🌍 7 translation-ready strings  

**Fixed**  
-  🐛 Toggle button errors  
-  💥 Editor initialization crashes  
-  🚷 Concurrent AJAX conflicts  

**Security**  
-  🔒 Nonce validation hardening  
-  🧼 Enhanced input sanitization  

### [1.3.0] - 2025-02-15
**Added**: Default snippets, version tracking  
**Security**: Input filters, capability checks  
**Perf**: Lazy editor, optimized storage  

### [1.2.0] - 2025-02-14
Initial public release  

## Support  
-  📚 [Documentation](https://sparkwebstudio.com/docs/code-manager)  
-  🛠️ [Support Portal](https://sparkwebstudio.com/support)  
-  📝 [GitHub Issues](https://github.com/sparkwebstudio/code-manager/issues)  

## Contribution  
```bash
# Clone & setup
git clone https://github.com/sparkwebstudio/code-manager.git && cd code-manager
npm install && composer install

# Development
npm run watch  # Live CSS/JS updates

# Build
composer build  # Creates production zip
