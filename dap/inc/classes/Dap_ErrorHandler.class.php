<?php

/**
 *
 * Registers Itself as a PHP Error Handler and proceeds to convert all
 * native "old school" PHP errors into new PHP5 Exceptions.
 *
 * Call Dap_ErrorHandler::Initialize(); before your try blocks and
 * Dap_ErrorHandler::Uninitialize(); afterwards.
 */
abstract class Dap_ErrorHandler
{
	/**
	 * Encapsulates set_error_handler()
	 */
	public static function Initialize()
	{
		set_error_handler(array("Dap_ErrorHandler", "HandleError"));
	}
	
	/**
	 * Encapsulates restore_error_handler()
	 */
	public static function Uninitialize()
	{
		restore_error_handler();
	}
	
	/**
	 * Handles PHP Errors
	 */
	public static function HandleError($errno, $errstr, $errfile, $errline, $errcontext)
	{
		echo "Handling Error..";
		throw new Dap_ErrorException($errstr, $errno, $errfile, $errline, $errcontext);
	}
}
?>
