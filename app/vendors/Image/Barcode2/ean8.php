<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_ean8 class
 *
 * Renders EAN 8 barcodes
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Tobias Frost <tobi@coldtobi.de>
 * @author    Didier Fournout <didier.fournout@nyc.fr>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id: 
 * @link      http://pear.php.net/package/Image_Barcode2
 */

require_once 'vendors/Image/Barcode2/Driver.php';
require_once 'vendors/Image/Barcode2/Common.php';
require_once 'vendors/Image/Barcode2/Exception.php';

/**
 * Image_Barcode2_ean8 class
 *
 * Package which provides a method to create EAN 13 barcode using GD library.
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Tobias Frost <tobi@coldtobi.de>
 * @author    Didier Fournout <didier.fournout@nyc.fr>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Image_Barcode2
 */
class Image_Barcode2_ean8 extends Image_Barcode2_Common implements Image_Barcode2_Driver
{
    /**
     * Number set
     * @var array
     */
    var $_number_set = array(
           '0' => array(
                    'A' => array(0,0,0,1,1,0,1),
                    'C' => array(1,1,1,0,0,1,0)
                        ),
           '1' => array(
                    'A' => array(0,0,1,1,0,0,1),
                    'C' => array(1,1,0,0,1,1,0)
                        ),
           '2' => array(
                    'A' => array(0,0,1,0,0,1,1),
                    'C' => array(1,1,0,1,1,0,0)
                        ),
           '3' => array(
                    'A' => array(0,1,1,1,1,0,1),
                    'C' => array(1,0,0,0,0,1,0)
                        ),
           '4' => array(
                    'A' => array(0,1,0,0,0,1,1),
                    'C' => array(1,0,1,1,1,0,0)
                        ),
           '5' => array(
                    'A' => array(0,1,1,0,0,0,1),
                    'C' => array(1,0,0,1,1,1,0)
                        ),
           '6' => array(
                    'A' => array(0,1,0,1,1,1,1),
                    'C' => array(1,0,1,0,0,0,0)
                        ),
           '7' => array(
                    'A' => array(0,1,1,1,0,1,1),
                    'C' => array(1,0,0,0,1,0,0)
                        ),
           '8' => array(
                    'A' => array(0,1,1,0,1,1,1),
                    'C' => array(1,0,0,1,0,0,0)
                        ),
           '9' => array(
                    'A' => array(0,0,0,1,0,1,1),
                    'C' => array(1,1,1,0,1,0,0)
                        )
        );

    /**
     * Class constructor
     *
     * @param Image_Barcode2_Writer $writer Library to use.
     */
    public function __construct(Image_Barcode2_Writer $writer) 
    {
        parent::__construct($writer);
        $this->setBarcodeHeight(50);
        $this->setBarcodeWidth(1);
    }


    /**
     * Validate barcode
     * 
     * @throws Image_Barcode2_Exception
     */
    public function validate()
    {
        // Check barcode for invalid characters
        if (!preg_match('/^[0-9]{8}$/', $this->getBarcode())) {
            throw new Image_Barcode2_Exception('Invalid barcode');
        }
    }


    /**
     * Draws a EAN 8 image barcode
     *
     * @return image            The corresponding EAN8 image barcode
     *
     * @access public
     *
     * @author     Tobias Frost tobi@coldtobi.de
     * 			   based on the EAN13 code by Didier Fournout <didier.fournout@nyc.fr>
     */
    public function draw()
    {
        $text = $this->getBarcode();

        // Calculate the barcode width
        $barcodewidth = (strlen($text)) * (7 * $this->getBarcodeWidth())
            + 3 * $this->getBarcodeWidth() // left
            + 5 * $this->getBarcodeWidth() // center
            + 3 * $this->getBarcodeWidth() // right
            ;

        $barcodelongheight = (int)($this->getWriter()->imagefontheight($this->getFontSize()) / 2)
             + $this->getBarcodeHeight();

        // Create the image
        $img = $this->getWriter()->imagecreate(
            $barcodewidth,
            $barcodelongheight + $this->getWriter()->imagefontheight($this->getFontSize()) + 1
        );

        // Alocate the black and white colors
        $black = $this->getWriter()->imagecolorallocate($img, 0, 0, 0);
        $white = $this->getWriter()->imagecolorallocate($img, 255, 255, 255);

        // Fill image with white color
        $this->getWriter()->imagefill($img, 0, 0, $white);

        // Initiate x position
        $xpos = 0;

        // Draws the left guard pattern (bar-space-bar)
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight,
            $black
        );
        $xpos += $this->getBarcodeWidth();
        // space
        $xpos += $this->getBarcodeWidth();
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight,
            $black
        );
        $xpos += $this->getBarcodeWidth();

        for ($idx = 0; $idx < 4; $idx ++) {
            $value = substr($text, $idx, 1);
            $this->getWriter()->imagestring(
                $img,
                $this->getFontSize(),
                $xpos + 1,
                $this->getBarcodeHeight(),
                $value,
                $black
            );
            foreach ($this->_number_set[$value]['A'] as $bar) {
                if ($bar) {
                    $this->getWriter()->imagefilledrectangle(
                        $img,
                        $xpos,
                        0,
                        $xpos + $this->getBarcodeWidth() - 1,
                        $barcodelongheight,
                        $black
                    );
                }
                $xpos += $this->getBarcodeWidth();
            }
        }

        // Draws the center pattern (space-bar-space-bar-space)
        // space
        $xpos += $this->getBarcodeWidth();
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight,
            $black
        );

        $xpos += $this->getBarcodeWidth();
        // space
        $xpos += $this->getBarcodeWidth();
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight,
            $black
        );

        $xpos += $this->getBarcodeWidth();
        // space
        $xpos += $this->getBarcodeWidth();


        // Draw right $text contents
        for ($idx = 4; $idx < 8; $idx ++) {
            $value = substr($text, $idx, 1);

            $this->getWriter()->imagestring(
                $img,
                $this->getFontSize(),
                $xpos + 1,
                $this->getBarcodeHeight(),
                $value,
                $black
            );

            foreach ($this->_number_set[$value]['C'] as $bar) {
                if ($bar) {
                    $this->getWriter()->imagefilledrectangle(
                        $img,
                        $xpos,
                        0,
                        $xpos + $this->getBarcodeWidth() - 1,
                        $barcodelongheight,
                        $black
                    );
                }
                $xpos += $this->getBarcodeWidth();
            }
        }

        // Draws the right guard pattern (bar-space-bar)
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight,
            $black
        );
        $xpos += $this->getBarcodeWidth();
        // space
        $xpos += $this->getBarcodeWidth();
        // bar
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0,
            $xpos + $this->getBarcodeWidth() - 1,
            $barcodelongheight,
            $black
        );

        return $img;
    } // function create

} // class

?>
