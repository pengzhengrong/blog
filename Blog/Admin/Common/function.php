<?php

function fontColor() {
	$basic = '0123456789abcdef';
	$length = 6;
	for ($i=0; $i<$length;$i++) {
		$rand = mt_rand( 0 , strlen( $basic )-1);
		$colors[] = substr( $basic, $rand , 1 );
	}
	$color = implode('',$colors);
	// my_log( 'color',$color );
	return 'grey';
	// return '#'.$color;
}


