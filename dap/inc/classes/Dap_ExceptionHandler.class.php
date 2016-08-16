<?php

class Dap_ExceptionHandler {
	protected $_exception;
	protected $_logFile = 'exception.log';
	
	public function __construct(Exception $e) {
		$this->_exception = $e;
	}
	
	public static function handle(Exception $e) {
		$self = new self($e);
		$self->log();
		echo $self;
	}
	
	public function log() {
		error_log($this->_exception->getTraceAsString(), 3, $this->_logFile);
	}	
	
	public function __toString() {
		//TODO: CLEAN UP THIS ERROR STRING.
		$message = "
			<html>
			<head>
			<title>Error</title>
			</head>
			<body>
			<h3>An error occurred in this application</h3>
			<p>
			An error occurred in this application. Please try again. If
			the error occurs again, please contact the site admin.
			</p></body>
			</html>
		";
		return $message;
	}
}

?>
