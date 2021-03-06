<?php # -*- coding: utf-8 -*-
/**
 * Replace one string with another in multiple tables at once.
 *
 * Usage:
 * <pre><code>
 * $tables = array (
 *    $wpdb->posts         => array (
 *        'post_content',
 *        'post_excerpt',
 *        'post_content_filtered',
 *    ),
 *    $wpdb->term_taxonomy => array (
 *        'description'
 *    ),
 *    $wpdb->comments      => array (
 *        'comment_content'
 *    )
 *);
 * $db_replace    = new Mlp_Db_Replace( $tables, 'Foo', 'Bar' );
 * $affected_rows = $db_replace->replace();
 * </code></pre>
 *
 * @author  Inpsyde GmbH, toscho
 * @version 2013.06.21
 */
class Mlp_Db_Replace {

	protected $tables, $from, $to;

	/**
	 * Constructor
	 *
	 * @param  array  $tables Table names as keys, columns as value arrays
	 * @param  string $from   String to find, will be escaped.
	 * @param  string $to     String to use as replacement, will be escaped.
	 */
	public function __construct( Array $tables, $from, $to ) {

		global $wpdb;

		$this->tables = $tables;
		$this->from   = $wpdb->_real_escape( $from );
		$this->to     = $wpdb->_real_escape( $to );
	}

	/**
	 * Replace references to old URI with the new one.
	 *
	 * @return int|FALSE Number of affected rows or FALSE on error
	 */
	public function replace() {

		global $wpdb;

		$sql = $this->get_replace_sql();
		return $wpdb->query( $sql );
	}

	/**
	 * Create an SQL query to replace the same strings in multiple tables and columns.
	 *
	 * @return string Complete SQL query
	 */
	public function get_replace_sql() {

		$table_names = join( '`,`', array_keys( $this->tables ) );
		$update      = "UPDATE `$table_names` SET \n";
		$replace     = array();

		foreach ( $this->tables as $table => $columns )
			$replace[] = $this->get_column_replace_sql( $table, $columns );

		return $update . join( ', ', $replace );
	}

	/**
	 * Create replacement SQL for single table with multiple columns.
	 *
	 * @param  string $table   Table name
	 * @param  array  $columns Column names
	 * @return string
	 */
	protected function get_column_replace_sql( $table, Array $columns ) {

		$rows = array ();

		foreach ( $columns as $column )
			$rows[] = "$table.$column = REPLACE( $table.$column, '$this->from', '$this->to' )";

		return join( ",\n", $rows );
	}
}