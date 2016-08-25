<?php

namespace Home\Widget;
use Think\Controller;

Class CateWidget extends Controller {

	Public function cate( $cate ) {
		// P($cate);die;
		$left = '';
		$right ='';
		foreach ($cate as  $k => $v) {
			if( $k%2 == 0 ) {
				$left .='<li><a href="/category_'.$v['id'].'"><i class="fa fa-angle-right"></i>'.$v['title'].'</a></li>';
			} else {
				$right .= '<li><a href="/category_'.$v['id'].'"><i class="fa fa-angle-right"></i>'.$v['title'].'</a></li>';
			}
		}
echo '<div class="widget container">
                    <a id="Categories"></a>
                    <h4>Categories</h4>
                    <div class="one-half">
                        <ul class="blog-category">'
                        	.$left.
	      '</ul>
                    </div>
                    <div class="one-half last-column">
                        <ul class="blog-category">'
                         	.$right. 
                        '</ul>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="decoration"></div>
</div>';
	}

}

 /*<div class="widget container">
                    <h4>Categories</h4>
                    <p>
                        Your categories styled up to look nice and clean!
                    </p>
                    <div class="one-half">
                        <ul class="blog-category">
                            <li><a href="#"><i class="fa fa-angle-right"></i>Category 1</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i>Category 2</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i>Category 3</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i>Category 4</a></li>
                        </ul>
                    </div>
                    <div class="one-half last-column">
                        <ul class="blog-category">
                            <li><a href="#"><i class="fa fa-angle-right"></i>Category 1</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i>Category 2</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i>Category 3</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i>Category 4</a></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="decoration"></div>
</div>*/