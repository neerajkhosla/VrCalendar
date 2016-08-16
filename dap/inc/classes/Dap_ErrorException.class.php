<?php 
/**
 * This exception behaves like a "old school" PHP Error
 */
class Dap_ErrorException extends Exception
{
	/**
	 * The PHP Error Context
	 *
	 * The fifth parameter is optional, errcontext, which is an array that points to the active symbol table at the point the error occurred. In other words, errcontext  will contain an array of every variable that existed in the scope the error was triggered in. User error handler must not modify error context.
	 */
	private $m_arContext;

	/**
	 * Constructor
	 */
	public function __construct($vMessage, $vCode, $vFile, $vLine, $arContext = null)
	{
		echo "Testing error exception";
		parent::__construct($vMessage, $vCode);
		echo "Testing error exception..done";
		
		$this->file = $vFile;
		$this->line = $vLine;
		
		$this->m_arContext = $arContext;
	}
}
?>
