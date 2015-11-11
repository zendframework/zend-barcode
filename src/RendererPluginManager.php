<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Barcode;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * Plugin manager implementation for barcode renderers.
 *
 * Enforces that barcode parsers retrieved are instances of
 * Renderer\AbstractRenderer. Additionally, it registers a number of default
 * barcode renderers.
 */
class RendererPluginManager extends AbstractPluginManager
{
    /**
     * @var bool Ensure services are not shared
     */
    protected $sharedByDefault = false;

    /**
     * Default set of barcode renderers
     *
     * @var array
     */
    protected $aliases = [
        'image' => Renderer\Image::class,
        'pdf'   => Renderer\Pdf::class,
        'svg'   => Renderer\Svg::class
    ];

    protected $factories = [
        Renderer\Image::class => InvokableFactory::class,
        Renderer\Pdf::class   => InvokableFactory::class,
        Renderer\Svg::class   => InvokableFactory::class,
    ];

    protected $instanceOf = Renderer\AbstractRenderer::class;
}
