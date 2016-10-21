<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model;

Class SyncController extends Controller{

	Public function syncBlogClink() {
		set_time_limit(0);
		if (S('BLOG_IDS_CACHE') != null) {
			$ids = S('BLOG_IDS_CACHE');
			foreach ($ids as $k => $id) {
				$cacheKey = "BLOG_ID_".$id;
				if( S($cacheKey) ) {
					$value = S($cacheKey);
					$rest = M('blog')->where('id='.$id)->setInc('click',$value);
					S($cacheKey,null);
				}
			}
			S('BLOG_IDS_CACHE', null);
		}
	}

	/**
	 * 同步博客搜索引擎
	 */
	Public function syncBlog() {
		$model = new Model\BlogDataModel();
		$blog = new Model\BlogModel();
		$fields = array('id' ,'title','content','created','cat_id','status');
		if( F('UPDATE_TIME') ) {
			$update_time = F('UPDATE_TIME');
		}
		if( $_GET['update_time'] ) {
			$update_time = strtotime($_GET['update_time']);
		}
		if( empty($update_time) ) {
			$update_time = strtotime('2016-06-21 15:20:00');
		}
		vendor('Elastic/Elastic','','.class.php');
		$param = C('DEFAULT_HOST');
		$elastic = new \Elastic($param);

		$fields = array('id' ,'title','created','cat_id','isdisplay','status');
		$where = array('time'=>array('gt',$update_time));
		$rest = $blog->getDataCache($fields, $where, 300);
		// P($update_time);
		// P($rest);die;
		if (!$rest) {
			exit("time>='{$update_time}'数据为空！");
		}
		$sync_blog = array();
		for( $i=0;$i<count($rest);$i++ ) {
			if( $rest[$i]['isdisplay'] == 0 && $rest[i]['status']==0 ) {
				$temp = $model->getFields('content', array('id'=>$rest[$i]['id']));
				$rest[$i]['content'] = dataclean($temp);
				$sync_blog[] = $rest[$i];
			} else {
				if ($rest[$i]['id']) {
					$elastic->search();
					@$elastic->delete(array('id'=>$rest[$i]['id']));
				}
			}
		}
		$time = $blog->getFields('max(`time`)',array('time'=>array('gt',$update_time)));
		F('UPDATE_TIME',$time);

		$elastic->create_index_by_rest($sync_blog , $fields );
	}

}
