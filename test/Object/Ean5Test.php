<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Barcode\Object;

use Zend\Barcode;

/**
 * @group      Zend_Barcode
 */
class Ean5Test extends TestCommon
{
    protected function getBarcodeObject($options = null)
    {
        return new Barcode\Object\Ean5($options);
    }

    public function testType()
    {
        $this->assertSame('ean5', $this->object->getType());
    }

    public function checksum()
    {
        yield ['45678', 2];
        yield ['5678', 0];
        yield ['05678', 0];
    }

    /**
     * @dataProvider checksum
     * @var string $text
     * @var int $checksum
     */
    public function testChecksum($text, $checksum)
    {
        $this->assertSame($checksum, $this->object->getChecksum($text));
    }

    public function testSetText()
    {
        $this->object->setText('45678');
        $this->assertSame('45678', $this->object->getRawText());
        $this->assertSame('45678', $this->object->getText());
        $this->assertSame('45678', $this->object->getTextToDisplay());
    }

    public function testSetTextWithout13Characters()
    {
        $this->object->setText('4567');
        $this->assertSame('4567', $this->object->getRawText());
        $this->assertSame('04567', $this->object->getText());
        $this->assertSame('04567', $this->object->getTextToDisplay());
    }

    public function testSetTextWithoutChecksumHasNoEffect()
    {
        $this->object->setText('45678');
        $this->object->setWithChecksum(false);
        $this->assertSame('45678', $this->object->getRawText());
        $this->assertSame('45678', $this->object->getText());
        $this->assertSame('45678', $this->object->getTextToDisplay());
    }

    public function testSetTextWithSpaces()
    {
        $this->object->setText(' 45678 ');
        $this->assertSame('45678', $this->object->getRawText());
        $this->assertSame('45678', $this->object->getText());
        $this->assertSame('45678', $this->object->getTextToDisplay());
    }

    public function testSetTextWithChecksumNotDisplayed()
    {
        $this->object->setText('45678');
        $this->object->setWithChecksumInText(false);
        $this->assertSame('45678', $this->object->getRawText());
        $this->assertSame('45678', $this->object->getText());
        $this->assertSame('45678', $this->object->getTextToDisplay());
    }

    public function testCheckGoodParams()
    {
        $this->object->setText('45678');
        $this->assertTrue($this->object->checkParams());
    }


    public function testGetKnownWidthWithoutOrientation()
    {
        $this->object->setText('45678');
        $this->assertEquals(68, $this->object->getWidth());
        $this->object->setWithQuietZones(false);
        $this->assertEquals(48, $this->object->getWidth(true));
    }

    public function testCompleteGeneration()
    {
        $this->object->setText('45678');
        $this->object->draw();
        $instructions = $this->loadInstructionsFile('Ean5_45678_instructions');
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorder()
    {
        $this->object->setText('45678');
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Ean5_45678_border_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithOrientation()
    {
        $this->object->setText('45678');
        $this->object->setOrientation(60);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Ean5_45678_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testCompleteGenerationWithBorderWithOrientation()
    {
        $this->object->setText('45678');
        $this->object->setOrientation(60);
        $this->object->setWithBorder(true);
        $this->object->draw();
        $instructions = $this->loadInstructionsFile(
            'Ean5_45678_border_oriented_instructions'
        );
        $this->assertEquals($instructions, $this->object->getInstructions());
    }

    public function testGetDefaultHeight()
    {
        // Checksum activated => text needed
        $this->object->setText('45678');
        $this->assertEquals(62, $this->object->getHeight(true));
    }

    public function testTextToDisplayWithChecksum()
    {
        $this->object->setText('123');
        $this->object->setWithChecksum(true);

        $this->assertSame('123', $this->object->getRawText());
        $this->assertSame('00123', $this->object->getText());
        $this->assertSame('00123', $this->object->getTextToDisplay());
    }

    public function testTextToDisplayWithoutChecksum()
    {
        $this->object->setText('123');
        $this->object->setWithChecksum(false);

        $this->assertSame('123', $this->object->getRawText());
        $this->assertSame('00123', $this->object->getText());
        $this->assertSame('00123', $this->object->getTextToDisplay());
    }
}
