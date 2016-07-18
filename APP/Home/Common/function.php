<?php
function  p( $param ) {

	if( is_array( $param )){
		dump( $param );
		return;
	}
	echo $param."<br />";

}