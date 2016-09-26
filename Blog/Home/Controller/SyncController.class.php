<?php
namespace Home\Controller;
use Think\Controller;
Class SyncController extends Controller{


	Public function _initialize() {
		/*S(array(
			'type'=>'redis',
			'host'=>'127.0.0.1',
			'port'=>'6379',
			'prefix'=>'App_',
			'expire'=>60
			)
			);*/
		// $Log = new \Think\Log();
		// $config = array(
		// 	'log_path' => '/home/pzr/workspace/blog/log/blog_click.log',
		// 	);
		// $Log::init($config);
		// $this->Log = $Log;
		}

		Public function syncBlogClink() {
			set_time_limit(0);
		// \Think\Log::write('每2H执行一次','INFO','File','/home/pzr/workspace/blog/log/blog_click.log');
			if (S('BLOG_IDS_CACHE') != null) {
				$ids = S('BLOG_IDS_CACHE');
				foreach ($ids as $k => $id) {
					$cacheKey = "BLOG_ID_".$id;
					if( S($cacheKey) ) {
						$value = S($cacheKey);
						$rest = M('blog')->where('id='.$id)->setInc('click',$value);
						\Think\Log::write($cacheKey.':'.$value,'INFO','File','/data0/log/blog_click.log');
						S($cacheKey,null);
					}
				}
				S('BLOG_IDS_CACHE', null);
			}
		// 效率太低
		/*$rest = M('blog')->field(array('id'))->where('status=0')->select();
		foreach ($rest as $k => $v) {
			$cacheKey = "BLOG_ID_".$v['id'];
			if( S($cacheKey) ) {
				$value = S($cacheKey);
				$rest = M('blog')->where('id='.$v['id'])->setInc('click',$value);
				\Think\Log::write($cacheKey.':'.$value,'INFO','File','/data0/log/blog_click.log');
				S($cacheKey,null);
			}
		}*/
	}

	/**
	 * [syncBlog update]
	 * @return [type] [description]
	 */
	Public function syncBlog() {
		// logger('每2H执行一次','blog_click.log');
		$fields = array('id' ,'title','content','created','cat_id','status');
		if( F('UPDATE_TIME') ) {
			$update_time = F('UPDATE_TIME');
		}
		if( $_GET['update_time'] ) {
			$update_time = $_GET['update_time'];
		}
		if( empty($update_time) ) {
			$update_time =  strtotime('2016-06-21 15:20');
		}
		// logger('update_time:'.$update_time,'blog_click.log');
		vendor('Elastic/Elastic','','.class.php');
		$param = C('DEFAULT_HOST');
		$elastic = new \Elastic($param);

		$fields = array('id' ,'title','content','created','cat_id','status');
		$rest = M('blog')->field($fields)->where('update_time>'.$update_time)->select();
		$sync_blog = array();
		for( $i=0;$i<count($rest);$i++ ) {
			if( $rest[$i]['status'] == 0 ) {
				$temp = dataclean( $rest[$i]['content'] );
				$rest[$i]['content'] = $temp;
				$sync_blog[] = $rest[$i];
			} else {
				$elastic->delete(array('id'=>$rest[$i]['id']));
				// logger('delete blog id:'.$rest[$i]['id'],'blog_click.log');
				\Think\Log::write('delete blog id:'.$rest[$i]['id'],'INFO','File','/data0/log/blog_sync.log');
			}
		}
		$max_time = M('blog')->field("max(update_time) as time")->fetchSql(false)->where('update_time>='.$update_time)->find();
		$time = $max_time['time'];
		F('UPDATE_TIME',$time);

		$elastic->create_index_by_rest($sync_blog , $fields );
	}

	Public function test() {
		P(111);
	}

}