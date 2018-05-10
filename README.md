# default-theme
The default theme as a boilerplate for the butterfly effect.

## Config 

### Theme-Config

```json
{
    "extra": {
        "butterfly-effect": {
            "theme": {
                "asset-folder": "In which folder are your public files? optional, filled with 'public' by default.",
                "css": [
                    "Array of your optional css files. You can use absolute URLs oder a relative path to your asset folder starting with ./."
                ],
                "js": [
                    "Array of your optional css files. You can use absolute URLs oder a relative path to your asset folder starting with ./."
                ]
            }
        }
    }
}
```

### Laravel Service Provider

Create a Service provider and extend 

`namespace ButterflyEffect\DefaultTheme\ThemeProvider`.

If you changed the standard path for our composer file, please change the provider property $composerFile to your required path.

```php
 /**
  * @var string Where to find the composer file for this theme.
  */
protected $composerFile = __DIR__ . '/../../composer.json';
     
```
  
### Activate the theme in your butterfly effect

Please define the env var **BUTTERFLY_EFFECT_THEME_DEFAULT** with the name of your theme, so that it gets used.