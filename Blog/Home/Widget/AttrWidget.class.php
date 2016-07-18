<?php 
namespace Home\Widget;
use Think\Controller;

Class AttrWidget extends Controller {

	public function blog_check( $attr , $selected ) {
		// p($checked);die;
		foreach ($attr as $k => $v) {
			$title = '<font  color="'.$v['color'].'">'.$v['title'].'</font>';
			$checked = '';
			foreach ($selected as $kk => $vv) {
				if( $v['id'] == $vv['id']  ){
				  	$checked = 'checked';
					break;
				}
			}
			echo '<input type="checkbox"  '.$checked.' name="attr_id[]" value="'.$v['id'].'" />'.$title.'&nbsp;&nbsp;';
		}
	}

}