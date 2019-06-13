# Lazy-Cache
Cache plugin for wordpress (wip)

This plugin may not look special. It uses the filesystem to store the cached requests BUT it supports dynamic rendering!

```php
// show correct date and time
render_dynamic('echo (new DateTime)->format("d.m.Y H:i:s");');

// render templates dynamically
render_dynamic('get_template_part("username");');

// or even better
get_template_part_dynamic('username');
```