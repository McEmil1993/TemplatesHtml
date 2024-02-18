<?php
//header("Content-Type: text/html; charset=ISO-8859-1");

class model{

	public function status($stat){
		$status = array('0' => 'Inactive', '1' => 'Active');
		return $status[$stat];
	}
	
}

?>