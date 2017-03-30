# Zend\\Barcode Renderers

Renderers have some common options. These options can be set in three ways:

- As an array or a [Traversable](http://php.net/traversable) object passed to the constructor.
- As an array passed to the `setOptions()` method.
- As discrete values passed to individual setters.

**Different ways to parameterize a renderer object**

```php
<?php
use Zend\Barcode\Renderer;

$options = array('topOffset' => 10);

// Case 1
$renderer = new Renderer\Pdf($options);

// Case 2
$renderer = new Renderer\Pdf();
$renderer->setOptions($options);

// Case 3
$renderer = new Renderer\Pdf();
$renderer->setTopOffset(10);

```

## Common Options

In the following list, the values have no unit; we will use the term "unit." For example, the
default value of the "thin bar" is "1 unit." The real units depend on the rendering support. The
individual setters are obtained by uppercasing the initial letter of the option and prefixing the
name with "set" (e.g. "barHeight" =\> "setBarHeight"). All options have a correspondent getter
prefixed with "get" (e.g. "getBarHeight"). Available options are:

An additional getter exists: `getType()`. It returns the name of the renderer class without the
namespace (e.g. `Zend\Barcode\Renderer\Image` returns "image").

## Zend\\Barcode\\Renderer\\Image

The Image renderer will draw the instruction list of the barcode object in an image resource. The
component requires the GD extension. The default width of a module is 1 pixel.

Available options are:

## Zend\\Barcode\\Renderer\\Pdf

The *PDF* renderer will draw the instruction list of the barcode object in a *PDF* document. The
default width of a module is 0.5 point.

There are no particular options for this renderer.

