<?php
defined('_JEXEC') or die();

use Joomla\CMS\Factory as JFactory;

/**
 * LaraDB facade
 */
class LaraDB
{
	/**
	 * Select database method
	 *
	 * @param String $query
	 * 		Query string
	 * @param Array $bindings
	 * 		Query string bindings
	 *
	 * @return Array
	 */
	public static function select($query, $bindings = []){
		$db = JFactory::getDBO();
		
		// Set Query
		$query = self::prepareQueryBindings($query, $bindings);
		$db->setQuery($query);

		// Execute and return
		return $db->loadAssocList();
	}
	
	/**
	 * Select database method
	 *
	 * @param String $query
	 * 		Query string
	 * @param Array $bindings
	 * 		Query string bindings
	 *
	 * @return Array
	 */
	public static function selectOne($query, $bindings = []){
		$db = JFactory::getDBO();

		// Set Query
		$query = self::prepareQueryBindings($query, $bindings);
		$db->setQuery($query);

		// Execute and return
		return $db->loadAssoc();
	}
	
	/**
	 * Prepare the query bindings
	 *
	 * @param String $query 
	 * 		Query string
	 * @param Array $bindings 
	 * 		Query string bindings
	 *
	 * @return Array
	 */
	public static function prepareQueryBindings($query, $bindings = []){
		$db = JFactory::getDBO();
		$time = microtime(true);
		
		if(!empty($bindings)){
			// Note: We can't replace the query params with the preg_replace() 
			// because this function evaluates escaped characters passed 
			// in the $replacement argument which causes undesired results.
			// The function str_replace() does not evaluates escaped 
			// characters passed in the $replacement argument.
			
			// Prepare placeholders
			foreach($bindings as $bindingKey => $bindingValue){
				$placeholder = "@@-placeholder-$bindingKey-$time-@@";
				$bindingKey = preg_quote($bindingKey, '/');
				$query = preg_replace('/\:'.$bindingKey.'\b/', $placeholder, $query);
			}

			// Replace placeholders
			foreach($bindings as $bindingKey => $bindingValue){
				$placeholder = "@@-placeholder-$bindingKey-$time-@@";
				$bindingValue = $db->escape($bindingValue);
				$query= str_replace($placeholder, $bindingValue, $query);
			}
		}

		return "$query;";
	}
	
	/**
	 * Update database method
	 *
	 * @param String $query
	 * 		Query string
	 * @param Array $bindings
	 * 		Query string bindings
	 *
	 * @return Integer
	 * 		Number of effected rows
	 */
	public static function update($query, $bindings = []){
		$db = JFactory::getDBO();
		
		// Set Query
		$query = self::prepareQueryBindings($query, $bindings);
		$db->setQuery($query);

		// Execute
		$db->execute();
		
		// Return
		return $db->getAffectedRows();
	}
	
	/**
	 * Determine if any rows exist
	 *
	 * @param String $query
	 * 		Query string
	 * @param Array $bindings
	 * 		Query string bindings
	 *
	 * @return Array
	 */
	public static function exists($query, $bindings = []){
		$db = JFactory::getDBO();
		
		// Set Query
		$query = self::prepareQueryBindings($query, $bindings);
		$db->setQuery($query);
		
		// Execute and return
		return $db->loadAssocList();
	}

	/**
	 * Insert database method
	 *
	 * @param String $query
	 * 		Query string
	 * @param Array $bindings
	 * 		Query string bindings
	 *
	 * @return Boolean
	 * 		True on success
	 */
	public static function insert($query, $bindings = []){
		$db = JFactory::getDBO();
		
		// Set Query
		$query = self::prepareQueryBindings($query, $bindings);
		$db->setQuery($query);

		// Execute and return
		return ($db->execute() !== false) ? true : false;
	}

	/**
	 * Delete database method
	 *
	 * @param String $query
	 * 		Query string
	 * @param Array $bindings
	 * 		Query string bindings
	 *
	 * @return Integer
	 * 		Number of effected rows
	 */
	public static function delete($query, $bindings = []){
		$db = JFactory::getDBO();
		
		// Set Query
		$query = self::prepareQueryBindings($query, $bindings);
		$db->setQuery($query);

		// Execute
		$db->execute();

		// Return
		return $db->getAffectedRows();
	}
}


