=== Lazy Cache ===
Contributors: Lugat
Tags: cache, pagecache, fragmentcache, filecache, dynamic, dynamiccache
Requires at least: 4.0
Tested up to: 5.2.2
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin adds a very simple but efficient caching into wordpress.

== Description ==

The plugin adds a very simple but effective caching into wordpress. It also supports dynamic rendering.

== Installation ==

1. Unzip the downloaded package
2. Upload `lazy-cache` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. You will find the settings page of the plugin /wp-admin/options-general.php?page=lazy-cache

== Usage ==

The plugin will automatically generate the cache-folder.

By activating the cache, all requests will be cached. You may exclude specific paths in the settings page.

=== Dynamic rendering ===

To avoid the cache for specific fragments, you may use dynamic rendering. This is helpfull to show user specific content or even simple things like date or time.

```php
// show correct date and time
render_dynamic('echo (new DateTime)->format("d.m.Y H:i:s");');

// render templates dynamically
render_dynamic('get_template_part("username");');

// or even better
get_template_part_dynamic('username');
```