<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Document
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// No direct access
defined('JPATH_BASE') or die;

/**
 * DocumentPDF class, provides an easy interface to parse and display a pdf document
 *
 * @package		Joomla.Framework
 * @subpackage	Document
 * @since		1.5
 */
class JDocumentPDF extends JDocument
{
	public $_engine	= null;

	public $_name		= 'joomla';

	public $_header	= null;

	public $_margin_header	= 5;
	public $_margin_footer	= 10;
	public $_margin_top	= 27;
	public $_margin_bottom	= 25;
	public $_margin_left	= 15;
	public $_margin_right	= 15;

	// Scale ratio for images [number of points in user unit]
	public $_image_scale	= 4;

	/**
	 * Class constructore
	 *
	 * @access public
	 * @param	array	$options Associative array of options
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		if (isset($options['margin-header'])) {
			$this->_margin_header = $options['margin-header'];
		}

		if (isset($options['margin-footer'])) {
			$this->_margin_footer = $options['margin-footer'];
		}

		if (isset($options['margin-top'])) {
			$this->_margin_top = $options['margin-top'];
		}

		if (isset($options['margin-bottom'])) {
			$this->_margin_bottom = $options['margin-bottom'];
		}

		if (isset($options['margin-left'])) {
			$this->_margin_left = $options['margin-left'];
		}

		if (isset($options['margin-right'])) {
			$this->_margin_right = $options['margin-right'];
		}

		if (isset($options['image-scale'])) {
			$this->_image_scale = $options['image-scale'];
		}

		//set mime type
		$this->_mime = 'application/pdf';

		//set document type
		$this->_type = 'pdf';
		/*
		 * Setup external configuration options
		 */
		define('K_TCPDF_EXTERNAL_CONFIG', true);

		/*
		 * Path options
		 */

		// Installation path
		define("K_PATH_MAIN", JPATH_LIBRARIES.DS."tcpdf");

		// URL path
		define("K_PATH_URL", JPATH_BASE);

		// Fonts path
		define("K_PATH_FONTS", JPATH_SITE.DS.'language'.DS."pdf_fonts".DS);

		// Cache directory path
		define("K_PATH_CACHE", K_PATH_MAIN.DS."cache");

		// Cache URL path
		define("K_PATH_URL_CACHE", K_PATH_URL.DS."cache");

		// Images path
		define("K_PATH_IMAGES", K_PATH_MAIN.DS."images");

		// Blank image path
		define("K_BLANK_IMAGE", K_PATH_IMAGES.DS."_blank.png");

		/*
		 * Format options
		 */

		// Cell height ratio
		define("K_CELL_HEIGHT_RATIO", 1.25);

		// Magnification scale for titles
		define("K_TITLE_MAGNIFICATION", 1.3);

		// Reduction scale for small font
		define("K_SMALL_RATIO", 2/3);

		// Magnication scale for head
		define("HEAD_MAGNIFICATION", 1.1);

		//page format
		define ('PDF_PAGE_FORMAT', 'A4');
	
		//page orientation (P=portrait, L=landscape)
		define ('PDF_PAGE_ORIENTATION', 'P');
		
		//document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
		define ('PDF_UNIT', 'mm');
	
		//header margin
		define ('PDF_MARGIN_HEADER', 5);
	
		//footer margin
		define ('PDF_MARGIN_FOOTER', 10);
	
		//top margin
		define ('PDF_MARGIN_TOP', 27);
	
		//bottom margin
		define ('PDF_MARGIN_BOTTOM', 25);
	
		//left margin
		define ('PDF_MARGIN_LEFT', 15);
	
		//right margin
		define ('PDF_MARGIN_RIGHT', 15);
	
		//main font name
		define ('PDF_FONT_NAME_MAIN', 'helvetica');
	
		//main font size
		define ('PDF_FONT_SIZE_MAIN', 10);

		define ('PDF_FONT_NAME_DATA', 'helvetica');
	
		//data font size
		define ('PDF_FONT_SIZE_DATA', 8);
	
		//Ratio used to scale the images
		define ('PDF_IMAGE_SCALE_RATIO', 4);
		
		/*
		 * Create the pdf document
		 */

		jimport('tcpdf.tcpdf');

		// Default settings are a portrait layout with an A4 configuration using millimeters as units
		$this->_engine = new TCPDF();

		//set margins
		$this->_engine->SetMargins($this->_margin_left, $this->_margin_top, $this->_margin_right);
		//set auto page breaks
		$this->_engine->SetAutoPageBreak(TRUE, $this->_margin_bottom);
		$this->_engine->SetHeaderMargin($this->_margin_header);
		$this->_engine->SetFooterMargin($this->_margin_footer);
		$this->_engine->setImageScale($this->_image_scale);
	}

	 /**
	 * Sets the document name
	 *
	 * @param   string   $name	Document name
	 * @access  public
	 * @return  void
	 */
	public function setName($name = 'joomla') {
		$this->_name = $name;
	}

	/**
	 * Returns the document name
	 *
	 * @access public
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	 /**
	 * Sets the document header string
	 *
	 * @param   string   $text	Document header string
	 * @access  public
	 * @return  void
	 */
	public function setHeader($text) {
		$this->_header = $text;
	}

	/**
	 * Returns the document header string
	 *
	 * @access public
	 * @return string
	 */
	public function getHeader() {
		return $this->_header;
	}

	/**
	 * Render the document.
	 *
	 * @access public
	 * @param boolean 	$cache		If true, cache the output
	 * @param array		$params		Associative array of attributes
	 * @return 	The rendered data
	 */
	public function render($cache = false, $params = array())
	{
		$pdf = &$this->_engine;

		// Set PDF Metadata
		$pdf->SetCreator($this->getGenerator());
		$pdf->SetTitle($this->getTitle());
		$pdf->SetSubject($this->getDescription());
		$pdf->SetKeywords($this->getMetaData('keywords'));

		// Set PDF Header data
		$pdf->setHeaderData('',0,$this->getTitle(), $this->getHeader());

		// Set PDF Header and Footer fonts
		$lang = &JFactory::getLanguage();
		$font = $lang->getPdfFontName();
		$font = ($font) ? $font : 'helvetica';

		$pdf->setRTL($lang->isRTL());

		$pdf->setFont($font);
		$pdf->setHeaderFont(array($font, '', 10));
		$pdf->setFooterFont(array($font, '', 8));

		// Initialize PDF Document
		$pdf->AliasNbPages();
		$pdf->AddPage();

		// Build the PDF Document string from the document buffer
		$this->fixLinks();
		$pdf->WriteHTML($this->getBuffer(), true);
		$data = $pdf->Output('', 'S');

		// Set document type headers
		parent::render();

		//JResponse::setHeader('Content-Length', strlen($data), true);

		JResponse::setHeader('Content-disposition', 'inline; filename="'.$this->getName().'.pdf"', true);

		//Close and output PDF document
		return $data;
	}

	public function fixLinks()
	{

	}

	/**
	 * Get the document head data
	 *
	 * @access	public
	 * @return	array	The document head data in array form
	 */
	public function getHeadData(){
		return false;
	}

	/**
	 * Set the document head data
	 *
	 * @access	public
	 * @param	array	$data	The document head data in array form
	 */
	public function setHeadData($data) {
		return false;
	}
}
