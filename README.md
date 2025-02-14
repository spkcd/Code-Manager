# Code Manager ![WordPress Plugin Version](https://img.shields.io/badge/Version-1.3.0-blue) ![License](https://img.shields.io/badge/License-GPL--3.0-green)

![Code Manager Interface](assets/banner-1544x500.png)

Professional code snippet management for WordPress, developed by [SPARKWEB Studio](https://sparkwebstudio.com/).

## Features

### Core Functionality
-  **Default Snippets Package**  
  Pre-installed curated snippets for common use cases (Safari fixes, CSS resets, etc)
-  **Protected Defaults**  
  Default snippets can't be deleted, only enabled/disabled
-  **Multi-Type Support**  
  Manage CSS and JavaScript snippets separately
-  **Version Control**  
  Track default snippet package versions

### Security
-  **Input Hardening**  
  Code sanitization with WPCS standards
-  **Role-Based Access**  
  Requires `manage_options` capability
-  **Nonce Verification**  
  All AJAX actions protected
-  **Audit Logging**  
  Error logging for suspicious activities

### Performance
-  **Selective Loading**  
  Only active snippets load on frontend
-  **Cache Optimization**  
  Version-busted asset loading
-  **Lightweight Core**  
  <50KB base plugin footprint
-  **Efficient Storage**  
  Single option record in database

## Installation

1. **Upload Plugin**  
   Through WordPress admin → Plugins → Add New

2. **Activate Plugin**  
   Default snippets will auto-install on activation

3. **Access Interface**  
   Navigate to **Code Manager** in admin sidebar

## Usage

### Managing Snippets
-  **Toggle Defaults**  
  Enable/disable pre-installed snippets
-  **Add Custom Code**  
  Create new CSS/JS snippets
-  **Bulk Actions**  
  Toggle multiple snippets at once

![Admin Interface](assets/screenshot-1.png)

### Best Practices
1. Test snippets in staging first
2. Use descriptive names
3. Keep CSS under 500 lines
4. Use IIFE for JavaScript
5. Audit active snippets monthly

## Changelog

### [1.3.0] - 2025-02-15
**Added**
-  Default snippets package
-  Version tracking for defaults
-  Protected snippet system

**Security**
-  Input sanitization layers
-  Regular expression filters
-  Strict capability checks

**Performance**
-  Optimized option storage
-  Reduced DOM interactions
-  Lazy-loaded code editor

### [1.2.0] - 2025-02-14
-  Initial public release

## Support
-  [Documentation](https://sparkwebstudio.com/docs/code-manager)  
-  [Support Portal](https://sparkwebstudio.com/support)  
-  [GitHub Issues](https://github.com/sparkwebstudio/code-manager/issues)

## Contribution
```bash
# Clone repo
git clone https://github.com/sparkwebstudio/code-manager.git

# Install dev dependencies
composer install

# Build production zip
composer build
