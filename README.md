# Code Manager ![WordPress Plugin Version](https://img.shields.io/badge/Version-1.7.0-blue) ![License](https://img.shields.io/badge/License-GPL--3.0-green)

Professional code snippet management for WordPress, developed by [SPARKWEB Studio](https://sparkwebstudio.com/).

## Features

### Core Functionality
-   **Code Editor:** Integrated Ace Editor for enhanced code editing with syntax highlighting.
-   **Snippet Types**: Supports CSS, JavaScript, and PHP snippets.
-   **Default Snippets Package**: Pre-installed curated snippets for common use cases (can be extended via import/export).
-   **Real-time Updates**: Toggle snippets on/off without page reloads.
-   **Page-Specific JS**: Assign JavaScript snippets to specific pages.
-   **Import/Export**: Import and export default snippets as JSON files.
- **Theme Selection**: Choose between different editor themes (currently GitHub and Monokai).

### Security
-   **Input Sanitization**: WPCS-compliant code cleaning for CSS and JavaScript. Basic sanitization for PHP code (removal of PHP tags and dangerous functions).
-   **Role-Based Access**: Requires `manage_options` capability (typically administrators).
-   **Nonce Verification**: All AJAX actions are protected with nonces.
-   **Audit Logging**: Error tracking for security events (future enhancement).

### Performance
-   **Selective Loading**: Only active snippets are loaded on the frontend.
-   **Caching**: Leverages WordPress's object cache (when available) and versioned assets.
-   **Lightweight**: Minimal core footprint.
-   **Efficient Storage**: Uses a single database option to store all snippets.

## Installation
1.  **Upload Plugin**: In the WordPress admin, go to Plugins → Add New and upload the plugin zip file.
2.  **Activate**: Activate the "Code Manager" plugin.  Default snippets will be installed automatically on activation.
3.  **Access**: A new menu item, "**Code Manager**", will appear in the WordPress admin sidebar.

## Usage

### Managing Snippets

1.  **Adding Snippets:**
    *   Go to **Code Manager** in the admin sidebar.
    *   Click the "Add New Snippet" heading.
    *   Enter a descriptive name for your snippet.
    *   Select the code type (CSS, JavaScript, or PHP).
    *   If you choose JavaScript, you can select a specific page to apply the snippet to, or choose "All Pages".
    *   Paste or write your code in the code editor.
    *   Click "Save Snippet".

2.  **Editing Snippets:**
    *   Go to **Code Manager**.
    *   Find the snippet you want to edit in the list.
    *   Click the "Edit" button.
    *   Make your changes in the code editor.
    *   Click "Update Snippet".

3.  **Toggling Snippets:**
    *   Go to **Code Manager**.
    *   Find the snippet you want to activate or deactivate.
    *   Use the toggle switch in the "Status" column.  The change takes effect immediately.

4.  **Deleting Snippets:**
    *   Go to **Code Manager**.
    *   Find the snippet you want to delete.
    *   Click the "Delete" button.  **Note:** Default snippets cannot be deleted.

### Tools

*   **Install Default Snippets:** Adds pre-configured snippets. This will preserve any existing snippets you've created.
*   **Export Default Snippets:** Downloads a JSON file containing all snippets marked as "default". This is useful for backups or transferring snippets to another site.
*   **Import Default Snippets:** Uploads a JSON file (previously exported) to import default snippets. Existing snippets with the same ID will be skipped to prevent duplicates.

### Theme Selection
* Go to **Code Manager**
* You will find a select dropdown to switch between the available themes.

## Changelog
See [CHANGELOG.md](CHANGELOG.md) for a complete history of changes.

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
