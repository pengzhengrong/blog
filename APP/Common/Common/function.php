<?php
function getSearch( $rest ,$fields, $key='hits'){

	$databack = array();
	$rest = $rest[$key];
	// P($rest);die;
	if( $key == 'hits' ){
		foreach ($rest['hits'] as  $k=>$v) {
			foreach ($fields as $kk=>$vv) {
				if( strpos($vv,'_') === 0){
					$databack[$k][$kk] = $v[$vv];
				}elseif( $kk=='highlight' ){
					$databack[$k][$kk] = $v[$vv];
				}else{
					$databack[$k][$kk] = $v['fields'][$vv][0];
				}
			}
		}
	}
	// P($databack);die;
	return $databack;
}

function getChildrens( $arr , $id ){
	$databack = $id;
	foreach ($arr as $key => $value) {
		if( $value['pid'] == $id ){
			$databack .=  ','.getChildrens( $arr , $value['id'] );
		}
	}
	return $databack;
}
