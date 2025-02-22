# Changelog

## 1.7.0 (2025-02-23)
*   **Improvement:** Switched to Ace Editor for improved code editing experience (syntax highlighting, line numbers, theme selection).
*   **Bugfix:** Fixed an issue where the code editor was not loading or saving snippets correctly.
*   **Bugfix:** Fixed a fatal error caused by incorrect class referencing and execution timing.
*   **Bugfix:** Fixed an issue where the snippet code was not loading when clicking "Edit".
*   **Bugfix:** Fixed an issue where editing a snippet would toggle its active status.
*   **Bugfix:** Fixed an issue where the page selector was not showing up.
*   **Bugfix:** Fixed PSR-4 autoloading issue by renaming `includes/class-cm-loader.php` to `includes/CM_Loader.php`.
*   **Bugfix:** Changed visibility of `CM_Admin::$snippets_option` to `protected` to fix a linter error.
*   **New Feature:** Added a theme selector to the admin interface (GitHub and Monokai themes included).
*   **New Feature:** Added support for PHP snippets, allowing users to manage PHP code snippets similar to `functions.php`.
*   **New Feature:** Implemented import/export functionality for default snippets.
*   **New Feature:** Added a page selector for JS snippets, allowing users to specify which page a JS snippet should be applied to.
*   **Security:** Improved security by making `CM_Loader::load_default_snippets()` private and adding `CM_Loader::install_default_snippets()` for controlled default snippet loading.
*   **Improvement:** Updated `CM_Admin::save_snippet()` to handle edits correctly, preserving the `created` timestamp and adding a `modified` timestamp.
*   **Improvement:** CSS snippets are now enqueued similarly to the Customizer function from the theme.
*   **New Feature:** Added `CM_Admin::get_snippet()` to retrieve a snippet by ID for editing.

**Note:** Autocompletion features in the Ace Editor are currently not fully functional due to unresolved console warnings. This will be addressed in a future update.

## 1.6.0 (2025-02-22)

* **Security:** Made `CM_Loader::load_default_snippets()` private and added a new public method `CM_Loader::install_default_snippets()` to handle default snippet loading more securely.
* **Bugfix:** Renamed `includes/class-cm-loader.php` to `includes/CM_Loader.php` to fix PSR-4 autoloading issue.
* **Improvement:** Updated `CM_Admin::save_snippet()` to handle edits correctly, preserving the `created` timestamp and adding a `modified` timestamp.
* **New Feature:** Added `CM_Admin::get_snippet()` to retrieve a snippet by ID for editing.
* **Bugfix:** Changed visibility of `CM_Admin::$snippets_option` to `protected` to fix linter error.
* **Improvement:** CSS snippets are now enqueued similar to the Customizer function from the theme.
* **New Feature:** JS snippets now have a page selector to assign the JS to that specific page.
* **New Feature:** Added support for PHP snippets.

## [1.3.0] - 2025-02-15
### Added
-   Default code snippets package
-   Default snippet identification badges
-   Version tracking for default snippets
-   Protection for default snippets from deletion

## [1.2.0] - 2025-02-15
### Added
-  Complete snippet management system
-  CodeMirror integration with syntax highlighting
-  Toggle/Delete functionality
-  Frontend code output
-  Comprehensive security checks

## [1.1.0] - 2025-02-14
### Added
-  Code syntax highlighting using WordPress CodeMirror integration
-  Language-aware editing (CSS/JavaScript)
-  Auto-completion and linting features
-  Improved editor UI with line numbers

## [1.0.0] - 2025-02-10
### Initial Release
-  Snippet management system for CSS/JS
-  Toggle activation system
-  Admin dashboard interface
-  AJAX-powered controls
-  Security hardening