<?php
/**
 * @see       https://github.com/zendframework/zend-barcode for the canonical source repository
 * @copyright Copyright (c) 2005-2019 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-barcode/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Barcode\Object\TestAsset;

/**
 * @group      Zend_Barcode
 */
class BarcodeTest extends \Zend\Barcode\Object\AbstractObject
{

    protected function calculateBarcodeWidth()
    {
        return 1;
    }

    public function validateText($value)
    {
    }

    protected function prepareBarcode()
    {
        return [];
    }

    protected function checkSpecificParams()
    {
    }

    public function addTestInstruction(array $instruction)
    {
        $this->addInstruction($instruction);
    }

    public function addTestPolygon(array $points, $color = null, $filled = true)
    {
        $this->addPolygon($points, $color, $filled);
    }

    public function addTestText($text, $size, $position, $font, $color, $alignment = 'center', $orientation = 0)
    {
        $this->addText($text, $size, $position, $font, $color, $alignment, $orientation);
    }
}
