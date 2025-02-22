# Changelog

## 1.6.0 (Upcoming)

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
