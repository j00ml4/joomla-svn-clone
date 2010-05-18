<?php
/**
 * @version		$Id: interface.php 14583 2010-05-16 07:16:48Z joomila $
 * @package		NoixFLAPP.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

/**
 * Knowledge Domain Specific Language Interface
 * 
 * @package		NoixFLAPP.Framework
 * @subpackage 	knowledge
 * @author 		Julio Pontes
 * @since 		1.0
 */
interface JKnowledgeDslInterface extends Iterator, Countable
{
	/**
     * Catalog must be accept another KnowledgeDSL by reference
     *
     * @param JKnowledgeDslInterface $reference
     * @since 1.0
     */
    public function __construct(JKnowledgeDslInterface $reference = null);
}