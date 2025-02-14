# Code Manager

![WordPress Plugin Version](https://img.shields.io/badge/Version-1.1.0-blue) 
![WordPress Compatibility](https://img.shields.io/badge/WordPress-6.5%2B-brightgreen)

A professional code snippet management solution for WordPress developers by [SPARKWEB Studio](https://sparkwebstudio.com/).

![Code Manager Interface]([screenshot-url])

## Features

🛠 **Core Functionality**
-  Manage CSS/JavaScript code snippets from admin dashboard
-  Toggle activation with switch controls
-  Syntax highlighting with CodeMirror integration
-  Automatic code validation
-  Multi-language support (CSS/JS)
-  Non-destructive activation system

🔒 **Security**
-  Role-based access control (Admin only)
-  Input sanitization/output escaping
-  Nonce verification for all actions
-  Secure AJAX handling
-  Strict capability checks

⚡ **Performance**
-  Conditional code loading
-  Minified assets delivery
-  Zero database bloat
-  Automatic cache busting
-  Lightweight architecture (<100KB)

## Installation

1. Clone repository:
```bash
git clone [repository-url] wp-content/plugins/code-manager

2.	Activate plugin:

WordPress Admin → Plugins → Code Manager → Activate

3.	Access interface:

WordPress Admin → Code Manager → Add New Snippet

Usage
Adding Snippets
	1.	Navigate to Code Manager
	2.	Enter snippet name
	3.	Select code type (CSS/JS)
	4.	Write/paste code
	5.	Save snippet
Managing Snippets
	•	Toggle active state with switches
	•	Delete unwanted snippets
	•	Real-time code validation
	•	Automatic browser reload on changes
Best Practices
	1.	Test snippets in staging first
	2.	Use descriptive names
	3.	Keep CSS snippets under 500 lines
	4.	Use IIFE for JavaScript snippets
	5.	Regularly audit active snippets
Contributing
We welcome contributions! Please follow these steps:
	1.	Fork repository
	2.	Create feature branch:
git checkout -b feature/amazing-feature
	3.	Commit changes
	4.	Push to branch
	5.	Open Pull Request
Support
Professional support available through:
	•	SPARKWEB Studio Website
	•	GitHub Issues
	•	Email: support@sparkwebstudio.com
License
GPL-3.0 © SPARKWEB Studio