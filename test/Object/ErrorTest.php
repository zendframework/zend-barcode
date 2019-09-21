<?php
/**
 * @see       https://github.com/zendframework/zend-barcode for the canonical source repository
 * @copyright Copyright (c) 2005-2019 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-barcode/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Barcode\Object;

use Zend\Barcode;

/**
 * @group      Zend_Barcode
 */
class ErrorTest extends TestCommon
{
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Error($options);
    }

    public function testType()
    {
        $this->assertSame('error', $this->object->getType());
    }

    public function testSetText()
    {
        $this->object->setText('This is an error text');
        $this->assertSame('This is an error text', $this->object->getRawText());
        $this->assertSame('This is an error text', $this->object->getText());
        $this->assertSame('This is an error text', $this->object->getTextToDisplay());
    }

    public function testCheckGoodParams()
    {
        $this->object->setText('This is an error text');
        $this->assertTrue($this->object->checkParams());
    }

    public function testGetDefaultHeight()
    {
        $this->assertEquals(40, $this->object->getHeight());
    }

    public function testGetDefaultWidth()
    {
        $this->assertEquals(400, $this->object->getWidth());
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('This is an error text');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Error_errortext_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }
}
