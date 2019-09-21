<?php
/**
 * @see       https://github.com/zendframework/zend-barcode for the canonical source repository
 * @copyright Copyright (c) 2005-2019 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-barcode/blob/master/LICENSE.md New BSD License
 */

namespace Zend\Barcode\Object;

/**
 * Class for generate Planet barcode
 */
class Planet extends Postnet
{
    /**
     * Coding map
     * - 0 = half bar
     * - 1 = complete bar
     * @var array
     */
    protected $codingMap = [
        0 => "00111",
        1 => "11100",
        2 => "11010",
        3 => "11001",
        4 => "10110",
        5 => "10101",
        6 => "10011",
        7 => "01110",
        8 => "01101",
        9 => "01011"
    ];
}
