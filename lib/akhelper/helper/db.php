<?php

class AKDb {
	
	public static function execute ($func) 
	{
		$class = JFactory::getDbo();
		
        if (is_callable( array( $class, $func ) ))
        {
            $temp = func_get_args();
            array_shift( $temp );
            $args = array();
            foreach ($temp as $k => $v) {
                $args[] = &$temp[$k];
            }
            return call_user_func_array( array( $class, $func ), $args );
        }
        else
        {
            JError::raiseWarning( 0, $class.'::'.$func.' not supported.' );
            return false;
        }
	}
	
	public static function setQuery( $sql ) {
		$db = JFactory::getDBO();
		$db->setQuery( $sql );
		return $db ;
	}
	
	public static function query( $sql ) {
		return self::setQuery( $sql )->query();
	}
	
	public static function loadResult($sql){
		return self::setQuery( $sql )->loadResult();
	}
 
    /**
     * Load an array of single field results into an array
     */
    public static function loadResultArray($sql , $numinarray = 0){
		return self::setQuery( $sql )->loadResultArray($numinarray);
	}
    /**
     * Fetch a result row as an associative array
     */
    public static function loadAssoc(){
		return self::setQuery( $sql )->loadAssoc();
	}
 
    /**
     * Load a associactive list of database rows
     *
     * @param    string    The field name of a primary key
     * @param    string    An optional column name. Instead of the whole row, only this column value will be in the return array.
     * @return    array    If key is empty as sequential list of returned records.
     */
    public static function loadAssocList($sql , $key = null, $column = null){
		return self::setQuery( $sql )->loadAssocList($key , $column);
	}
 
    /**
     * This global public static function loads the first row of a query into an object
     *
     * @return    object 
     */
    public static function loadObject($sql){
		return self::setQuery( $sql )->loadObject();
	}
 
    /**
     * Load a list of database objects
     *
     * @param    string    The field name of a primary key
     * @return    array    If <var>key</var> is empty as sequential list of returned records.
     *  If <var>key</var> is not empty then the returned array is indexed by the value
     *  the database key.  Returns <var>null</var> if the query fails.
     */
    public static function loadObjectList($sql , $key=''){
		return self::setQuery( $sql )->loadObjectList($key);
	}
 
    /**
     * Load the first row returned by the query
     *
     * @return    mixed    The first row of the query.
     */
    public static function loadRow($sql){
		return self::setQuery( $sql )->loadRow();
	}
 
    /**
     * Load a list of database rows (numeric column indexing)
     *
     * If <var>key</var> is not empty then the returned array is indexed by the value
     * the database key.  Returns <var>null</var> if the query fails.
     *
     * @param    string    The field name of a primary key
     * @return    array 
     */
    public static function loadRowList($sql , $key=''){
		return self::setQuery( $sql )->loadRowList($key);
	}
 
    /**
     * Load the next row returned by the query.
     *
     * @return    mixed    The result of the query as an array, false if there are no more rows, or null on an error.
     *
     * @since    1.6.0
     */
    public static function loadNextRow($sql){
		return self::setQuery( $sql )->loadNextRow();
	}
 
    /**
     * Load the next row returned by the query.
     *
     * @return    mixed    The result of the query as an object, false if there are no more rows, or null on an error.
     *
     * @since    1.6.0
     */
    public static function loadNextObject($sql){
		return self::setQuery( $sql )->loadNextObject();
	}
 
    /**
     * Inserts a row into a table based on an objects properties
     * @param    string    The name of the table
     * @param    object    An object whose properties match table fields
     * @param    string    The name of the primary key. If provided the object property is updated.
     */
    public static function insertObject($table, &$object, $keyName = NULL){
		$db = JFactory::getDBO();
		return $db->insertObject($table, &$object, $keyName) ;
	}
 
    /**
     * Update an object in the database
     *
     * @param    string 
     * @param    object 
     * @param    string 
     * @param    boolean 
     */
    public static function updateObject($table, &$object, $keyName, $updateNulls=false){
		$db = JFactory::getDBO();
		return $db->updateObject($table, &$object, $keyName, $updateNulls) ;
	}
}



