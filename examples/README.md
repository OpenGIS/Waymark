# Waymark Examples

This directory contains practical examples and code snippets for extending and customizing Waymark functionality.

## Available Examples

### [Separating Export/Download from Map](separate-export-download.md)
Learn how to separate the export and download options from the Waymark map and place them elsewhere on your page (below, next to, or in a sidebar).

**Topics covered:**
- Using the `waymark_loaded_callback` function
- Cloning export controls
- Custom positioning
- Multiple maps handling
- Custom styling

**Implementation files:**
- [Complete Documentation](separate-export-download.md) - Comprehensive guide with multiple examples
- [JavaScript File](waymark-export-separation.js) - Ready-to-use JavaScript file for theme integration
- [PHP Snippet](functions-php-snippet.php) - Code to add to your theme's functions.php
- [HTML Example](export-separation-example.html) - Standalone HTML example for testing

## Using the Callback Function

Many of these examples use the global `waymark_loaded_callback` function, which allows you to extend Waymark's functionality after a map has loaded.

For more information about the callback function, see:
- [Waymark Callback Function Documentation](https://www.waymark.dev/docs/callback-function/)
- [Waymark JS Documentation](https://www.waymark.dev/js)

## Contributing Examples

If you have a useful customization or example that you'd like to share, please consider contributing:

1. Fork the repository
2. Add your example to this directory
3. Update this README with a link to your example
4. Submit a pull request

## Support

For questions or issues:
- [Waymark Documentation](https://www.waymark.dev/docs/)
- [Support Forum](https://wordpress.org/support/plugin/waymark/)
- [GitHub Issues](https://github.com/OpenGIS/Waymark/issues)
