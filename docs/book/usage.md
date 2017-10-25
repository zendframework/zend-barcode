# Usage

`Zend\Barcode\Barcode` uses a factory method, `factory()`, to create both an
instance of the barcode object to represent, and the renderer you will use to
draw it. (Barcode objects extend `Zend\Barcode\Object\AbstractObject`, and
renderers extend `Zend\Barcode\Renderer\AbstractRenderer`.) The `factory()`
method accepts five arguments:

- The name of the barcode format (e.g., "code39") or a
  [Traversable](http://php.net/traversable) object (required).
- The name of the renderer (e.g., "image") (required).
- Options to pass to the barcode object (an array or a
  [Traversable](http://php.net/traversable) object) (optional).
- Options to pass to the renderer object (an array or a
  [Traversable](http://php.net/traversable) object) (optional).
- A boolean to indicate whether or not to automatically render errors. If an
  exception occurs, the provided barcode object will be replaced with an error
  representation (optional default `TRUE`).

On success, the factory method returns an instance of the renderer.

### Getting a Renderer with Zend\\Barcode\\Barcode::factory()

`Zend\Barcode\Barcode::factory()` instantiates both the barcode and renderer
instance, and binds them.

In this first example, we will use the **Code39** barcode type together with the
**Image** renderer.

```php
use Zend\Barcode\Barcode;

// Only the text to draw is required.
$barcodeOptions = ['text' => 'ZEND-FRAMEWORK'];

// No required options.
$rendererOptions = [];
$renderer = Barcode::factory(
    'code39',
    'image',
    $barcodeOptions,
    $rendererOptions
);
```

### Using zend-config

You may pass a `Zend\Config\Config` instance to the factory in order to create
the necessary objects. The following example is functionally equivalent to the
previous.

```php
use Zend\Config\Config;
use Zend\Barcode\Barcode;

// Using a single Zend\Config\Config object:
$config = new Config([
    'barcode'        => 'code39',
    'barcodeParams'  => ['text' => 'ZEND-FRAMEWORK'],
    'renderer'       => 'image',
    'rendererParams' => ['imageType' => 'gif'],
]);

$renderer = Barcode::factory($config);

```

## Drawing a barcode

When you **draw** the barcode, you retrieve the resource in which the barcode is
drawn. To draw a barcode, call the `draw()` of the renderer, or use the proxy
method provided by `Zend\Barcode\Barcode`.

### Drawing a barcode with the renderer object

```php
use Zend\Barcode\Barcode;

// Only the text to draw is required.
$barcodeOptions = ['text' => 'ZEND-FRAMEWORK'];

// No required options.
$rendererOptions = [];

// Draw the barcode, capturing the resource:
$renderer = Barcode::factory(
    'code39',
    'image',
    $barcodeOptions,
    $rendererOptions
);
$imageResource = $renderer->draw();

```

### Drawing a barcode with Zend\\Barcode\\Barcode::draw()

The static `draw()` method is a shortcut for calling `factory()` + `draw()`:

```php
use Zend\Barcode\Barcode;

// Only the text to draw is required.
$barcodeOptions = ['text' => 'ZEND-FRAMEWORK'];

// No required options.
$rendererOptions = [];

// Draw the barcode, capturing the resource:
$imageResource = Barcode::draw(
    'code39',
    'image',
    $barcodeOptions,
    $rendererOptions
);

```

## Rendering a barcode

In the previous section, we were *drawing*, which *returns* the resource
representing the barcode, but does not actually *emit* it (e.g., to the
browser).

To emit the barcode, call the `render()` method of the renderer, or use
the proxy method provided by `Zend\Barcode\Barcode`.

### Rendering a barcode with the renderer object

```php
use Zend\Barcode\Barcode;

// Only the text to draw is required
$barcodeOptions = ['text' => 'ZEND-FRAMEWORK'];

// No required options
$rendererOptions = [];

// Draw the barcode, // send the headers, and emit the image:
Barcode::factory(
    'code39',
    'image',
    $barcodeOptions,
    $rendererOptions
)->render();

```

This will generate the following barcode:

![image](images/zend.barcode.introduction.example-1.png)

### Rendering a barcode with Zend\\Barcode\\Barcode::render()

```php
use Zend\Barcode\Barcode;

// Only the text to draw is required
$barcodeOptions = ['text' => 'ZEND-FRAMEWORK'];

// No required options
$rendererOptions = [];

// Draw the barcode, // send the headers, and emit the image:
Barcode::render(
    'code39',
    'image',
    $barcodeOptions,
    $rendererOptions
);

```

This will generate the same barcode as the previous example.
