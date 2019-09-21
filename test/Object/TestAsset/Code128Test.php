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
class Code128Test extends \Zend\Barcode\Object\Code128
{
    public function convertToBarcodeChars($string)
    {
        return parent::convertToBarcodeChars($string);
    }
}
