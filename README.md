# Code Manager ![WordPress Plugin Version](https://img.shields.io/badge/Version-1.6.0-blue) ![License](https://img.shields.io/badge/License-GPL--3.0-green)

![Code Manager Interface](assets/banner-1544x500.png)

Professional code snippet management for WordPress, developed by [SPARKWEB Studio](https://sparkwebstudio.com/).

## Features

### Core Functionality
-   **Inline Editing**: Edit snippets directly in the interface
-   **Default Snippets Package**: Pre-installed curated snippets for common use cases
-   **Real-time Updates**: Toggle snippets without page reloads
-   **Version Control**: Track default snippet versions
-   **Snippet Types**: Supports CSS, JavaScript, and PHP snippets.
-   **Page Selector**: Assign JavaScript snippets to specific pages.
-   **Import/Export**: Import and export default snippets.

### Security
-   **Input Sanitization**: WPCS-compliant code cleaning
-   **Role-Based Access**: `manage_options` capability required
-   **Nonce Verification**: All AJAX actions protected
-   **Audit Logging**: Error tracking for security events
- **PHP Code Sanitization**: Basic sanitization to remove potentially dangerous PHP constructs.

### Performance
-   **Selective Loading**: Only active snippets on frontend
-   **Caching**: Versioned asset loading
-   **Lightweight**: 55KB core footprint
-   **Efficient Storage**: Single database option

## Installation
1.  **Upload Plugin**: WordPress admin → Plugins → Add New
2.  **Activate**: Default snippets auto-install
3.  **Access**: **Code Manager** in admin sidebar

## Usage
### Key Actions
-   🛠️ **Edit**: Click any snippet's Edit button
-   ✅ **Toggle**: Switches update in real-time
-   🗑️ **Delete**: Removes custom snippets (defaults protected)
-   🔄 **Restore**: Use *Install Defaults* for system snippets
-   **Export Default Snippets**: Click to download a JSON file of your default snippets.
-   **Import Default Snippets**: Click to upload a JSON file and import default snippets.

![Admin Interface](assets/screenshot-1.png)

### Best Practices
1.  **Test First**: Use staging for new snippets
2.  **Name Clearly**: e.g., "Header CSS Optimization"
3.  **Size Limits**: CSS ≤500 lines, JS ≤1000 lines, PHP - be mindful of complexity.
4.  **Modern JS**: Use IIFE/scoped variables
5.  **PHP Snippets**: Use for functionality similar to `functions.php`. Avoid complex logic.

## Changelog

### [1.6.0] - 2025-02-22
*   **Security:** Improved security by making `CM_Loader::load_default_snippets()` private and adding `CM_Loader::install_default_snippets()` for controlled default snippet loading.
*   **Bugfix:** Fixed PSR-4 autoloading issue by renaming `includes/class-cm-loader.php` to `includes/CM_Loader.php`.
*   **Bugfix:** Fixed an issue where editing a snippet would toggle its active status.
*   **Bugfix:** Fixed an issue where the page selector was not showing up.
*   **Bugfix:** Fixed a fatal error caused by incorrect class referencing and execution timing of PHP snippets.
*   **Bugfix:** Changed visibility of `CM_Admin::$snippets_option` to `protected` to fix a linter error.
*   **Improvement:** Updated `CM_Admin::save_snippet()` to handle edits correctly, preserving the `created` timestamp and adding a `modified` timestamp.
*   **Improvement:** CSS snippets are now enqueued similarly to the Customizer function from the theme.
*   **New Feature:** Added `CM_Admin::get_snippet()` to retrieve a snippet by ID for editing.
*   **New Feature:** Added support for PHP snippets, allowing users to manage PHP code snippets similar to `functions.php`.
*   **New Feature:** Implemented import/export functionality for default snippets.
*   **New Feature:** Added a page selector for JS snippets, allowing users to specify which page a JS snippet should be applied to.

### [1.4.0] - 2025-02-17
**Added**
-   ✨ Inline Snippet Editing
-   ⏳ UI loading states
-   🌍 7 translation-ready strings

**Fixed**
-   🐛 Toggle button errors
-   💥 Editor initialization crashes
-   🚷 Concurrent AJAX conflicts

**Security**
-   🔒 Nonce validation hardening
-   🧼 Enhanced input sanitization

### [1.3.0] - 2025-02-15
**Added**: Default snippets, version tracking
**Security**: Input filters, capability checks
**Perf**: Lazy editor, optimized storage

### [1.2.0] - 2025-02-14
Initial public release

## Support
-   📚 [Documentation](https://sparkwebstudio.com/docs/code-manager)
-   🛠️ [Support Portal](https://sparkwebstudio.com/support)
-   📝 [GitHub Issues](https://github.com/sparkwebstudio/code-manager/issues)

## Contribution
```bash
# Clone & setup
git clone https://github.com/sparkwebstudio/code-manager.git && cd code-manager
npm install && composer install

# Development
npm run watch  # Live CSS/JS updates

# Build
composer build  # Creates production zip
