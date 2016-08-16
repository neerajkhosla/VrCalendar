<?php

class Dap_Base {

	
   	var $id;	
	var $error_id;	
	var $error_array = array();
	//
	
	function getId() {
	        return $this->id;
	}
	
	function setId($o) {
	      $this->id = $o;
	}
	
	function getError_id() {
	        return $this->error_id;
	}
	
	function setError_id($o) {
	      $this->error_id = $o;
	}
	
	function getErrors() {
	        return $this->error_array;
	}
	
	function addError($o) {
	      $this->error_array[] = $o;
	}

	function getError($index) {
		return $this->error_array[$index];	
	}
}
?>
