# Dynamic Additional CSS

Writes Customizer Additional CSS to a dynamic stylesheet so that it can be cached

## About

By default, styles added to the Additional CSS section in the WordPress Customizer are added inline:

```html
<style type="text/css" id="wp-custom-css">
body {
  background: pink !important;
}
</style>
```

This is OK for small amounts of CSS but is not ideal for large amounts because it cannot be cached by the browser.

This plugin removes the inline styles and writes them to a dynamic stylesheet which is then loaded externally and can therefor be cached.

When the plugin is activated, the inline styles above will be removed on the front end of your site and will be replaced with a link to the dynamic CSS:

```html
<link rel="stylesheet" id="dynamic-additional-css" href="https://example.com/wp-admin/admin-ajax.php?action=load_css_ajax&amp;wpnonce=cc89b6d57e&amp;ver=1.0.0" type="text/css" media="all">
```

A dynamically generated stylesheet has all the benefits of a standard stylesheet, plus it means the plugin doesn't need to write to a physical CSS file on the server.

## Installation

1. Download the zip file from this repository.
2. Navigate to Plugins > Add New.
3. Click the Upload Plugin button at the top of the screen.
4. Select the zip file from your local filesystem.
5. Click the Install Now button.
6. When installation is complete, you’ll see “Plugin installed successfully.” Click the Activate Plugin button at the bottom of the page.

## Usage

There are no options provided by this plugin.

