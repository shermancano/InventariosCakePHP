<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
 * Image_Barcode2_int25 class
 *
 * Renders Interleaved 2 of 5 barcodes
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
 * @author    Marcelo Subtil Marcal <msmarcal@php.net>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/Image_Barcode2
 */

require_once 'vendors/Image/Barcode2/Driver.php';
require_once 'vendors/Image/Barcode2/Common.php';
require_once 'vendors/Image/Barcode2/DualWidth.php';
require_once 'vendors/Image/Barcode2/Exception.php';

/**
 * Image_Barcode2_int25 class
 *
 * Package which provides a method to create Interleaved 2 of 5
 * barcode using GD library.
 *
 * @category  Image
 * @package   Image_Barcode2
 * @author    Marcelo Subtil Marcal <msmarcal@php.net>
 * @copyright 2005 The PHP Group
 * @license   http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/Image_Barcode2
 */
class Image_Barcode2_int25 extends Image_Barcode2_Common implements Image_Barcode2_Driver, Image_Barcode2_DualWidth
{
    /**
     * Coding map
     * @var array
     */
    var $_coding_map = array(
           '0' => '00110',
           '1' => '10001',
           '2' => '01001',
           '3' => '11000',
           '4' => '00101',
           '5' => '10100',
           '6' => '01100',
           '7' => '00011',
           '8' => '10010',
           '9' => '01010'
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
        $this->setBarcodeWidthThin(1);
        $this->setBarcodeWidthThick(3);
    }


    /**
     * Validate barcode
     * 
     * @throws Image_Barcode2_Exception
     */
    public function validate()
    {
        // Check barcode for invalid characters
        if (!preg_match('/[0-9]/', $this->getBarcode())) {
            throw new Image_Barcode2_Exception('Invalid barcode');
        }
    }


    /**
     * Draws a Interleaved 2 of 5 image barcode
     *
     * @return image            The corresponding Interleaved 2 of 5 image barcode
     *
     * @access public
     *
     * @author Marcelo Subtil Marcal <msmarcal@php.net>
     * @since  Image_Barcode2 0.3
     */
    public function draw()
    {
        $text = $this->getBarcode();

        // if odd $text lenght adds a '0' at string beginning
        $text = strlen($text) % 2 ? '0' . $text : $text;

        // Calculate the barcode width
        $barcodewidth = (strlen($text)) 
            * (3 * $this->getBarcodeWidthThin() + 2 * $this->getBarcodeWidthThick())
            + (strlen($text))
            * 2.5
            + (7 * $this->getBarcodeWidthThin() + $this->getBarcodeWidthThick()) + 3;

        // Create the image
        $img = $this->getWriter()->imagecreate($barcodewidth, $this->getBarcodeHeight());

        // Alocate the black and white colors
        $black = $this->getWriter()->imagecolorallocate($img, 0, 0, 0);
        $white = $this->getWriter()->imagecolorallocate($img, 255, 255, 255);

        // Fill image with white color
        $this->getWriter()->imagefill($img, 0, 0, $white);

        // Initiate x position
        $xpos = 0;

        // Draws the leader
        for ($i = 0; $i < 2; $i++) {
            $elementwidth = $this->getBarcodeWidthThin();
            $this->getWriter()->imagefilledrectangle(
                $img,
                $xpos,
                0,
                $xpos + $elementwidth - 1,
                $this->getBarcodeHeight(),
                $black
            );
            $xpos += $elementwidth;
            $xpos += $this->getBarcodeWidthThin();
            $xpos ++;
        }

        // Draw $text contents
        $all = strlen($text);

        // Draw 2 chars at a time
        for ($idx = 0; $idx < $all; $idx += 2) {
            $oddchar  = substr($text, $idx, 1);
            $evenchar = substr($text, $idx + 1, 1);

            // interleave
            for ($baridx = 0; $baridx < 5; $baridx++) {

                // Draws odd char corresponding bar (black)
                $elementwidth = $this->getBarcodeWidthThin();
                if (substr($this->_coding_map[$oddchar], $baridx, 1)) {
                    $elementwidth = $this->getBarcodeWidthThick();
                }

                $this->getWriter()->imagefilledrectangle(
                    $img, 
                    $xpos, 
                    0, 
                    $xpos + $elementwidth - 1, 
                    $this->getBarcodeHeight(), 
                    $black
                );

                $xpos += $elementwidth;

                // Left enought space to draw even char (white)
                $elementwidth = $this->getBarcodeWidthThin();
                if (substr($this->_coding_map[$evenchar], $baridx, 1)) {
                    $elementwidth = $this->getBarcodeWidthThick();
                }

                $xpos += $elementwidth; 
                $xpos ++;
            }
        }


        // Draws the trailer
        $elementwidth = $this->getBarcodeWidthThick();
        $this->getWriter()->imagefilledrectangle(
            $img, 
            $xpos, 
            0, 
            $xpos + $elementwidth - 1,
            $this->getBarcodeHeight(), 
            $black
        );
        $xpos += $elementwidth;
        $xpos += $this->getBarcodeWidthThin();
        $xpos ++;
        $elementwidth = $this->getBarcodeWidthThin();
        $this->getWriter()->imagefilledrectangle(
            $img,
            $xpos,
            0, 
            $xpos + $elementwidth - 1,
            $this->getBarcodeHeight(), 
            $black
        );

        return $img;
    }

} // class

?>
