<?php
/**
 * JHook is used to trigger events in Joomla
 * This is the hook api for Joomla
 *
 * There are two types of hooks: filters and actions
 * Filters are meant to modify any of the passed parameters
 * Actions are meant to perform an action at that point of the script
 */
class JHook
{
	public $filters = array();
	public $merged_hooks = array();
	public $current_hook = '';
	public $actions = array();

	/**
	 * Returns a reference to the global Event JHook object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * 		<pre>  $dispatcher = &JHook::getInstance();</pre>
	 *
	 * @access	public
	 * @return	JHook	The EventDispatcher object.
	 * @since	1.0
	 */
	public function &getInstance()
	{
		static $instance;

		if ( !is_object( $instance ) ) {
			$instance = new JHook();
		}

		return $instance;
	}

	/**
	 * Triggers a hook for filtering
	 *
	 * @param string The name for the filter
	 * @param array An array of arguments
	 * @return array Returns results from called hooks
	 */
	public function trigger( $hook, $args = null )
	{
		// If no arguments were passed, we still need to pass an empty array to
		// the call_user_func_array function.
		if ( $args === null ) {
			$args = array();
		}

		$result = array();

		// Record current filter
		$this->current_hook[] = $hook;

		if ( !isset( $this->filters[$hook] ) ) {
			array_pop( $this->current_hook );
			return $result;
		}

		// Sort
		if ( !isset( $this->merged_hooks[$hook] ) ) {
			ksort( $this->filters[$hook] );
			$this->merged_hooks[$hook] = true;
		}

		reset( $this->filters[$hook] );

		foreach ( $this->filters[$hook] as $priority ) {
			foreach ( (array) $priority as $function ) {
				if ( !is_null( $function['function'] ) ) {
					$result[] = call_user_func_array( $function['function'], $args );
				}
			}
		}

		// Done with the hook, remove it
		array_pop( $this->current_hook );

		return $result;
	}

	public function addFilter( $hook, $function, $priority = 10 )
	{
		$idx = $this->_callUniqueId( $hook, $function, $priority );
		$this->filters[$hook][$priority][$idx] = array( 'function' => $function );
		unset( $this->merged_hooks[$hook] );

		return true;
	}

	public function hasFilter( $hook, $function = false )
	{
		$has = !empty( $this->filters[$hook] );
		if ( false === $function || false == $has ) {
			return $has;
		}

		if ( !$idx = $this->_callUniqueId( $hook, $function, false ) ) {
			return false;
		}

		foreach ( (array) array_keys( $this->filters[$hook] ) as $priority ) {
			if ( isset( $this->filters[$hook][$priority][$idx] ) ) {
				return $priority;
			}
		}

		return false;
	}

	public function removeFilter( $hook, $function, $priority = 10 )
	{
		$function = $this->_callUniqueId( $hook, $function, $priority );

		$r = isset( $this->filters[$hook][$priority][$function] );

		if ( true === $r ) {
			unset( $this->filters[$hook][$priority][$function] );

			if ( empty( $this->filters[$hook][$priority] ) ) {
				unset( $this->filters[$hook][$priority] );
			}

			unset( $this->merged_hooks[$hook] );
		}

		return $r;
	}

	public function removeAllFilters( $hook, $priority = false )
	{
		if ( isset( $this->filters[$hook] ) ) {
			if ( false !== $priority && isset( $this->filters[$hook][$priority] ) ) {
				unset( $this->filters[$hook][$priority] );
			} else {
				unset( $this->filters[$hook] );
			}
		}

		if ( isset( $this->merged_hooks[$hook] ) ) {
			unset( $this->merged_hooks[$hook] );
		}

		return true;
	}

	public function currentHook()
	{
		return end( $this->current_hook );
	}

	/**
	 * FUNCTIONS FOR HANDLING HOOKS
	 */

	/**
	 * TODO: Should be moved to some JUtilities class for everyone to use
	 * Method gets a unique id for the hooked function
	 */
	function _callUniqueId( $hook, $function, $priority )
	{
		static $filter_id_count = 0;

		if ( is_string( $function ) ) {
			return $function;
		}

		if ( is_object( $function ) ) {
			$function = array( $function, '' );
		} else {
			$function = (array) $function;
		}

		if ( is_object( $function[0] ) ) {
			// Object Class Calling
			if ( function_exists( 'spl_object_hash' ) ) {
				return spl_object_hash( $function[0] ) . $function[1];
			} else {
				return md5( get_class( $function[0] ) . $function[1] );
			}
		} else if ( is_string( $function[0] ) ) {
			// Static Calling
			return $function[0] . $function[1];
		}
	}
}