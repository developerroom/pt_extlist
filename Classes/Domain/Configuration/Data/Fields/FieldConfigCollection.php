<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
*
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/



/**
 * Class implements a collection of field configurations.
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection extends tx_pttools_objectCollection {
	
	/**
	 * This collection is restricted to objects of type Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 *
	 * @var string
	 */
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig';
	
	
	
	/**
	 * Adds a field configuration object to collection
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfig
	 */
	public function addFieldConfig(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldConfig) {
		$this->addItem($fieldConfig, $fieldConfig->getIdentifier());
	}
	
	
	
	/**
	 * Returns a field configuration object for a given identifier
	 *
	 * @param string $identifier
	 * @return Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig
	 */
	public function getFieldConfigByIdentifier($identifier) {
		if ($this->hasItem($identifier)) {
			return $this->getItemById($identifier);
		} else {
			throw new Exception('Field configuration object for key ' . $identifier . 'does not exist!');
		}
	}
	
}

?>