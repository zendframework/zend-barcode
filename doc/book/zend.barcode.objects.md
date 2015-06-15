# Zend\\Barcode Objects

Barcode objects allow you to generate barcodes independently of the rendering support. After
generation, you can retrieve the barcode as an array of drawing instructions that you can provide to
a renderer.

Objects have a large number of options. Most of them are common to all objects. These options can be
set in three ways:

- As an array or a [Traversable](http://php.net/traversable) object passed to the constructor.
- As an array passed to the `setOptions()` method.
- Via individual setters for each configuration type.

**Different ways to parameterize a barcode object**

```php
<?php
use Zend\Barcode\Object;

$options = array('text' => 'ZEND-FRAMEWORK', 'barHeight' => 40);

// Case 1: constructor
$barcode = new Object\Code39($options);

// Case 2: setOptions()
$barcode = new Object\Code39();
$barcode->setOptions($options);

// Case 3: individual setters
$barcode = new Object\Code39();
$barcode->setText('ZEND-FRAMEWORK')
        ->setBarHeight(40);

```

## Common Options

In the following list, the values have no units; we will use the term "unit." For example, the
default value of the "thin bar" is "1 unit". The real units depend on the rendering support (see the
renderers documentation
\<zend.barcode.renderers\> for more information). Setters are each named by uppercasing the initial
letter of the option and prefixing the name with "set" (e.g. "barHeight" becomes "setBarHeight").
All options have a corresponding getter prefixed with "get" (e.g. "getBarHeight"). Available options
are:

### Particular case of static setBarcodeFont()

You can set a common font for all your objects by using the static method
`Zend\Barcode\Barcode::setBarcodeFont()`. This value can be always be overridden for individual
objects by using the `setFont()` method.

```php
<?php
use Zend\Barcode\Barcode;

// In your bootstrap:
Barcode::setBarcodeFont('my_font.ttf');

// Later in your code:
Barcode::render(
    'code39',
    'pdf',
    array('text' => 'ZEND-FRAMEWORK')
); // will use 'my_font.ttf'

// or:
Barcode::render(
    'code39',
    'image',
    array(
        'text' => 'ZEND-FRAMEWORK',
        'font' => 3
    )
); // will use the 3rd GD internal font

```

## Common Additional Getters
