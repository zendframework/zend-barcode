<?php
/**
 * @see       https://github.com/zendframework/zend-barcode for the canonical source repository
 * @copyright Copyright (c) 2005-2019 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Barcode\Object;

/**
 * Class for generate Ean2 barcode
 */
class Ean2 extends Ean5
{
    protected $parities = [
        0 => ['A','A'],
        1 => ['A','B'],
        2 => ['B','A'],
        3 => ['B','B']
    ];

    /**
     * Default options for Ean2 barcode
     * @return void
     */
    protected function getDefaultOptions()
    {
        $this->barcodeLength = 2;
    }

    protected function getParity($i)
    {
        $modulo = $this->getText() % 4;
        return $this->parities[$modulo][$i];
    }
}
