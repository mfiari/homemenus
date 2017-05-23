<?php

include_once WEBSITE_PATH."core/ControllerTemplate.php";

abstract class Controller_TicketResto_Template extends Controller_Template {
	
	public function __construct () {
		
	}
	
	protected function error ($code, $message = "") {
		header("HTTP/1.0 ".$code." ".$message);
	}
	
	protected function render ($vue) {
		return WEBSITE_PATH."modules/ticket_resto/vues/".$vue;
	}
}
