<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Barcode;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Zend\Barcode;
use Zend\Barcode\Exception\InvalidArgumentException;
use Zend\Barcode\Object\Code25;
use Zend\Barcode\Object\Code39;
use Zend\Barcode\Object\Error;
use Zend\Barcode\Renderer;
use Zend\Barcode\Renderer\Image;
use Zend\Barcode\Renderer\Pdf;
use Zend\Config\Config;
use Zend\ServiceManager\Exception\ExceptionInterface;
use Zend\ServiceManager\Exception\InvalidServiceException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Barcode\Renderer\RendererInterface;
use ZendPdf\PdfDocument;

/**
 * @group      Zend_Barcode
 */
class FactoryTest extends TestCase
{
    /**
     * Stores the original set timezone
     * @var string
     */
    private $originaltimezone;

    public function setUp()
    {
        $this->originaltimezone = date_default_timezone_get();

        // Set timezone to avoid "It is not safe to rely on the system's timezone settings."
        // message if timezone is not set within php.ini
        date_default_timezone_set('GMT');

        // Reset plugin managers
        $r = new ReflectionClass(Barcode\Barcode::class);

        $rObjectPlugins = $r->getProperty('objectPlugins');
        $rObjectPlugins->setAccessible(true);
        $rObjectPlugins->setValue(null);

        $rRendererPlugins = $r->getProperty('rendererPlugins');
        $rRendererPlugins->setAccessible(true);
        $rRendererPlugins->setValue(null);
    }

    public function tearDown()
    {
        date_default_timezone_set($this->originaltimezone);
    }

    public function testMinimalFactory()
    {
        $this->checkGDRequirement();
        $renderer = Barcode\Barcode::factory('code39');
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertInstanceOf(Code39::class, $renderer->getBarcode());
    }

    /**
     * @group fml
     */
    public function testMinimalFactoryWithRenderer()
    {
        $renderer = Barcode\Barcode::factory('code39', 'pdf');
        $this->assertInstanceOf(Pdf::class, $renderer);
        $this->assertInstanceOf(Code39::class, $renderer->getBarcode());
    }

    public function testFactoryWithOptions()
    {
        $this->checkGDRequirement();
        $options = ['barHeight' => 123];
        $renderer = Barcode\Barcode::factory('code25', 'image', $options);
        $this->assertEquals(123, $renderer->getBarcode()->getBarHeight());
    }

    public function testFactoryWithAutomaticExceptionRendering()
    {
        $this->checkGDRequirement();
        $options = ['barHeight' => - 1];
        $renderer = Barcode\Barcode::factory('code39', 'image', $options);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertInstanceOf(Error::class, $renderer->getBarcode());
    }

    public function testFactoryWithoutAutomaticObjectExceptionRendering()
    {
        $this->expectException(ExceptionInterface::class);
        $options = ['barHeight' => - 1];
        $renderer = Barcode\Barcode::factory('code39', 'image', $options, [], false);
    }

    public function testFactoryWithoutAutomaticRendererExceptionRendering()
    {
        $this->expectException(ExceptionInterface::class);
        $this->checkGDRequirement();
        $options = ['imageType' => 'my'];
        $renderer = Barcode\Barcode::factory('code39', 'image', [], $options, false);
        $this->markTestIncomplete('Need to throw a configuration exception in renderer');
    }

    public function testFactoryWithZendConfig()
    {
        $this->checkGDRequirement();
        $config = new Config([
            'barcode'  => 'code39',
            'renderer' => 'image',
        ]);
        $renderer = Barcode\Barcode::factory($config);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertInstanceOf(Code39::class, $renderer->getBarcode());
    }

    public function testFactoryWithZendConfigAndObjectOptions()
    {
        $this->checkGDRequirement();
        $config = new Config([
            'barcode'       => 'code25',
            'barcodeParams' => [
                'barHeight' => 123,
            ],
        ]);
        $renderer = Barcode\Barcode::factory($config);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertInstanceOf(Code25::class, $renderer->getBarcode());
        $this->assertEquals(123, $renderer->getBarcode()->getBarHeight());
    }

    public function testFactoryWithZendConfigAndRendererOptions()
    {
        $this->checkGDRequirement();
        $config = new Config(['barcode'        => 'code25',
                                   'rendererParams' => [
                                   'imageType'      => 'gif']]);
        $renderer = Barcode\Barcode::factory($config);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertInstanceOf(Code25::class, $renderer->getBarcode());
        $this->assertSame('gif', $renderer->getImageType());
    }

    public function testFactoryWithoutBarcodeWithAutomaticExceptionRender()
    {
        $this->checkGDRequirement();
        $renderer = Barcode\Barcode::factory(null);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertInstanceOf(Error::class, $renderer->getBarcode());
    }

    public function testFactoryWithoutBarcodeWithAutomaticExceptionRenderWithZendConfig()
    {
        $this->checkGDRequirement();
        $config = new Config(['barcode' => null]);
        $renderer = Barcode\Barcode::factory($config);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertInstanceOf(Error::class, $renderer->getBarcode());
    }

    public function testFactoryWithExistingBarcodeObject()
    {
        $this->checkGDRequirement();
        $barcode = new Code25();
        $renderer = Barcode\Barcode::factory($barcode);
        $this->assertSame($barcode, $renderer->getBarcode());
    }

    public function testBarcodeObjectFactoryWithExistingBarcodeObject()
    {
        $barcode = new Code25();
        $generatedBarcode = Barcode\Barcode::makeBarcode($barcode);
        $this->assertSame($barcode, $generatedBarcode);
    }

    public function testBarcodeObjectFactoryWithBarcodeAsString()
    {
        $barcode = Barcode\Barcode::makeBarcode('code25');
        $this->assertInstanceOf(Code25::class, $barcode);

        // ensure makeBarcode creates unique instances
        $this->assertNotSame($barcode, Barcode\Barcode::makeBarcode('code25'));
    }

    public function testBarcodeObjectFactoryWithBarcodeAsStringAndConfigAsArray()
    {
        $barcode = Barcode\Barcode::makeBarcode('code25', ['barHeight' => 123]);
        $this->assertInstanceOf(Code25::class, $barcode);
        $this->assertSame(123, $barcode->getBarHeight());
    }

    public function testBarcodeObjectFactoryWithBarcodeAsStringAndConfigAsZendConfig()
    {
        $config = new Config(['barHeight' => 123]);
        $barcode = Barcode\Barcode::makeBarcode('code25', $config);
        $this->assertInstanceOf(Code25::class, $barcode);
        $this->assertSame(123, $barcode->getBarHeight());
    }

    public function testBarcodeObjectFactoryWithBarcodeAsZendConfig()
    {
        $config = new Config([
            'barcode'       => 'code25',
            'barcodeParams' => [
                'barHeight' => 123,
            ],
        ]);
        $barcode = Barcode\Barcode::makeBarcode($config);
        $this->assertInstanceOf(Code25::class, $barcode);
        $this->assertSame(123, $barcode->getBarHeight());
    }

    public function testBarcodeObjectFactoryWithBarcodeAsZendConfigButNoBarcodeParameter()
    {
        $this->expectException(\Zend\Barcode\Exception\ExceptionInterface::class);
        $config = new Config(['barcodeParams' => ['barHeight' => 123] ]);
        $barcode = Barcode\Barcode::makeBarcode($config);
    }

    public function testBarcodeObjectFactoryWithBarcodeAsZendConfigAndBadBarcodeParameters()
    {
        $this->expectException(\Zend\Barcode\Exception\ExceptionInterface::class);
        $barcode = Barcode\Barcode::makeBarcode('code25', null);
    }

    public function testBarcodeObjectFactoryWithNamespace()
    {
        $plugins = Barcode\Barcode::getObjectPluginManager();
        $plugins->setInvokableClass('barcodeNamespace', Object\TestAsset\BarcodeNamespace::class);
        $barcode = Barcode\Barcode::makeBarcode('barcodeNamespace');
        $this->assertInstanceOf(Object\TestAsset\BarcodeNamespace::class, $barcode);
    }

    public function testBarcodeObjectFactoryWithNamespaceExtendStandardLibray()
    {
        $plugins = Barcode\Barcode::getObjectPluginManager();
        $plugins->setInvokableClass('error', \ZendTest\Barcode\Object\TestAsset\Error::class);
        $barcode = Barcode\Barcode::makeBarcode('error');
        $this->assertInstanceOf(\ZendTest\Barcode\Object\TestAsset\Error::class, $barcode);
    }

    public function testBarcodeObjectFactoryWithNamespaceButWithoutExtendingObjectAbstract()
    {
        $plugins = Barcode\Barcode::getObjectPluginManager();
        $plugins->setInvokableClass(
            'barcodeNamespaceWithoutExtendingObjectAbstract',
            Object\TestAsset\BarcodeNamespaceWithoutExtendingObjectAbstract::class
        );

        try {
            Barcode\Barcode::makeBarcode('barcodeNamespaceWithoutExtendingObjectAbstract');
            $this->fail('Invalid barcode object should raise an exception');
        } catch (InvalidServiceException $e) {
            // V3 exception
            $this->assertInstanceOf(InvalidServiceException::class, $e);
        } catch (InvalidArgumentException $e) {
            // V2 exception
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testBarcodeObjectFactoryWithUnexistentBarcode()
    {
        $this->expectException(ServiceNotFoundException::class);
        $barcode = Barcode\Barcode::makeBarcode('zf123', []);
    }

    public function testBarcodeRendererFactoryWithExistingBarcodeRenderer()
    {
        $this->checkGDRequirement();
        $renderer = new Renderer\Image();
        $generatedBarcode = Barcode\Barcode::makeRenderer($renderer);
        $this->assertSame($renderer, $generatedBarcode);
    }

    public function testBarcodeRendererFactoryWithBarcodeAsString()
    {
        $this->checkGDRequirement();
        $renderer = Barcode\Barcode::makeRenderer('image');
        $this->assertInstanceOf(Image::class, $renderer);

        // ensure unique instance is created
        $this->assertNotSame($renderer, Barcode\Barcode::makeRenderer('image'));
    }

    public function testBarcodeRendererFactoryWithBarcodeAsStringAndConfigAsArray()
    {
        $this->checkGDRequirement();

        $renderer = Barcode\Barcode::makeRenderer('image', ['imageType' => 'gif']);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertSame('gif', $renderer->getImageType());
    }

    public function testBarcodeRendererFactoryWithBarcodeAsStringAndConfigAsZendConfig()
    {
        $this->checkGDRequirement();
        $config = new Config(['imageType' => 'gif']);
        $renderer = Barcode\Barcode::makeRenderer('image', $config);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertSame('gif', $renderer->getimageType());
    }

    public function testBarcodeRendererFactoryWithBarcodeAsZendConfig()
    {
        $this->checkGDRequirement();
        $config = new Config([
            'renderer'       => 'image',
            'rendererParams' => ['imageType' => 'gif'],
        ]);
        $renderer = Barcode\Barcode::makeRenderer($config);
        $this->assertInstanceOf(Image::class, $renderer);
        $this->assertSame('gif', $renderer->getimageType());
    }

    public function testBarcodeRendererFactoryWithBarcodeAsZendConfigButNoBarcodeParameter()
    {
        $this->expectException(\Zend\Barcode\Exception\ExceptionInterface::class);
        $config = new Config(['rendererParams' => ['imageType' => 'gif'] ]);
        $renderer = Barcode\Barcode::makeRenderer($config);
    }

    public function testBarcodeRendererFactoryWithBarcodeAsZendConfigAndBadBarcodeParameters()
    {
        $this->expectException(\Zend\Barcode\Exception\ExceptionInterface::class);
        $renderer = Barcode\Barcode::makeRenderer('image', null);
    }

    public function testBarcodeRendererFactoryWithNamespace()
    {
        $this->checkGDRequirement();
        $plugins = Barcode\Barcode::getRendererPluginManager();
        $plugins->setInvokableClass('rendererNamespace', 'ZendTest\Barcode\Renderer\TestAsset\RendererNamespace');
        $renderer = Barcode\Barcode::makeRenderer('rendererNamespace');
        $this->assertInstanceOf(RendererInterface::class, $renderer);
    }

    public function testBarcodeFactoryWithNamespaceButWithoutExtendingRendererAbstract()
    {
        $plugins = Barcode\Barcode::getRendererPluginManager();
        $plugins->setInvokableClass(
            'rendererNamespaceWithoutExtendingRendererAbstract',
            'ZendTest\Barcode\Renderer\TestAsset\RendererNamespaceWithoutExtendingRendererAbstract'
        );

        try {
            Barcode\Barcode::makeRenderer('rendererNamespaceWithoutExtendingRendererAbstract');
            $this->fail('Invalid barcode renderer should raise an exception');
        } catch (InvalidServiceException $e) {
            // V3 exception
            $this->assertInstanceOf(InvalidServiceException::class, $e);
        } catch (InvalidArgumentException $e) {
            // V2 exception
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
        }
    }

    public function testBarcodeRendererFactoryWithUnexistentRenderer()
    {
        $this->expectException(ServiceNotFoundException::class);
        $renderer = Barcode\Barcode::makeRenderer('zend', []);
    }

    public function testProxyBarcodeRendererDrawAsImage()
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is required to run this test');
        }
        $resource = Barcode\Barcode::draw('code25', 'image', ['text' => '012345']);
        $this->assertInternalType('resource', $resource, 'Image must be a resource');
        $this->assertEquals('gd', get_resource_type($resource), 'Image must be a GD resource');
    }

    public function testProxyBarcodeRendererDrawAsImageAutomaticallyRenderImageIfException()
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('GD extension is required to run this test');
        }
        $resource = Barcode\Barcode::draw('code25', 'image');
        $this->assertInternalType('resource', $resource, 'Image must be a resource');
        $this->assertEquals('gd', get_resource_type($resource), 'Image must be a GD resource');
    }

    public function testProxyBarcodeRendererDrawAsPdf()
    {
        if (! getenv('TESTS_ZEND_BARCODE_PDF_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_ZEND_BARCODE_PDF_SUPPORT to test PDF render');
        }

        Barcode\Barcode::setBarcodeFont(__DIR__ . '/Object/_fonts/Vera.ttf');
        $resource = Barcode\Barcode::draw('code25', 'pdf', ['text' => '012345']);
        $this->assertInstanceOf('ZendPdf\PdfDocument', $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testProxyBarcodeRendererDrawAsPdfAutomaticallyRenderPdfIfException()
    {
        if (! getenv('TESTS_ZEND_BARCODE_PDF_SUPPORT')) {
            $this->markTestSkipped('Enable TESTS_ZEND_BARCODE_PDF_SUPPORT to test PDF render');
        }

        Barcode\Barcode::setBarcodeFont(__DIR__ . '/Object/_fonts/Vera.ttf');
        $resource = Barcode\Barcode::draw('code25', 'pdf');
        $this->assertInstanceOf(PdfDocument::class, $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testProxyBarcodeRendererDrawAsSvg()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/Object/_fonts/Vera.ttf');
        $resource = Barcode\Barcode::draw('code25', 'svg', ['text' => '012345']);
        $this->assertInstanceOf(DOMDocument::class, $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testProxyBarcodeRendererDrawAsSvgAutomaticallyRenderSvgIfException()
    {
        Barcode\Barcode::setBarcodeFont(__DIR__ . '/Object/_fonts/Vera.ttf');
        $resource = Barcode\Barcode::draw('code25', 'svg');
        $this->assertInstanceOf(DOMDocument::class, $resource);
        Barcode\Barcode::setBarcodeFont('');
    }

    public function testProxyBarcodeObjectFont()
    {
        Barcode\Barcode::setBarcodeFont('my_font.ttf');
        $barcode = new Code25();
        $this->assertSame('my_font.ttf', $barcode->getFont());
        Barcode\Barcode::setBarcodeFont('');
    }

    protected function checkGDRequirement()
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('This test requires the GD extension');
        }
    }
}
