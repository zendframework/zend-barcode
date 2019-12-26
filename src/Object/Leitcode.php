<?php
/**
 * @see       https://github.com/zendframework/zend-barcode for the canonical source repository
 * @copyright Copyright (c) 2005-2019 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Barcode\Object;

/**
 * Class for generate Identcode barcode
 */
class Leitcode extends Identcode
{
    /**
     * Default options for Leitcode barcode
     * @return void
     */
    protected function getDefaultOptions()
    {
        $this->barcodeLength = 14;
        $this->mandatoryChecksum = true;
    }

    /**
     * Retrieve text to display
     * @return string
     */
    public function getTextToDisplay()
    {
        $this->checkText($this->text);

        return preg_replace('/([0-9]{5})([0-9]{3})([0-9]{3})([0-9]{2})([0-9])/', '$1.$2.$3.$4 $5', $this->getText());
    }
}
