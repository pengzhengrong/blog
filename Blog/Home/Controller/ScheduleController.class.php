<?php

namespace Home\Controller;

use Think\Controller;
use Home\Model\ScheduleModelDB;

Class ScheduleController extends CommonController {

	protected $db = null;

	public function _initialize() {
		$this->db = new ScheduleModelDB();
	}

	//日历首页
	Public function calendar() {
		$this->display();
	}

	//初始化日历日程
	Public function index() {
		// 只查询当月的日程
		// 在调用fullcalendar 时，会自动加上start和end参数，不错。
		$where = array(
			'starttime' => array('gt', I('start',0,'intval') ),
			'endtime' => array('lt', I('end',0,'intval')),
			'user_id' => session('user_id')
			);
		$rest = $this->db->getData('*', $where);
		$data = array();
		foreach ($rest as $v) {
			$data[] = array(
				'id' => $v['id'],
				'title' => $v['title'],
				'start' => date('Y-m-d H:i',$v['starttime']),
				'end' => date('Y-m-d H:i',$v['endtime']),
				'url' => '',
				'allDay' => $v['isallday'],
				'color' => $v['color']
				);
		}
		// logger( json_encode($data));
		echo json_encode($data);
	}

	// 添加可拖动的事件
	Public function event() {
		$data = I('post.');
		$data['user_id'] = session('user_id');
		$rest = M('event')->add($data);
		$this->ajaxReturn( setAjaxReturn( $rest,'添加事件失败！' ) );
	}

	Public function event_del() {
		$id = I('id');
		$rest = M('event')->fetchSql(false)->delete($id);
		$this->ajaxReturn( setAjaxReturn( $rest,'删除事件失败！' ) );
	}

	//添加日程事件
	Public function schedule_add() {
		if( IS_POST ) {
			// logger( json_encode(I('post.')) );
			$colors = array("#360","#f30","#06c");
			$key = array_rand($colors);
			$color = $colors[$key];
			$data = array(
				'title' => I('event'),
				'starttime' => strtotime( I('startdate').' '.I('s_hour',0,'intval').':'.I('s_minute',0,'intval') ),
				//'endtime' => strtotime( I('enddate').' '.I('e_hour').':'.I('e_minute') ),
				'isallday' => I('isallday',0,'intval'),
				'color' => $color,
				'user_id' => session('user_id')
				);
			if( I('isend',0,'intval') == 1 ) {
				$data['endtime'] = strtotime( I('enddate').' '.I('e_hour').':'.I('e_minute') );
			}
			// $rest = M('calendar')->add($data);
			$rest = $this->db->insert($data);
			if( $rest ) {
				echo 1;
			} else {
				echo '添加事件失败！';
			}
			exit;
		}
		$this->date = I('date');
		// logger($this->date);
		$this->display();
	}

	//修改事件
	Public function schedule_edit() {
		if( IS_POST ) {
			// logger( json_encode( I('post.') ) );
			$data = array(
				'id' => I('id'),
				'title' => I('event'),
				'starttime' => strtotime( I('startdate').' '.I('s_hour').':'.I('s_minute') ),
				//'endtime' => strtotime( I('enddate').' '.I('e_hour').':'.I('e_minute') ),
				'isallday' => I('isallday',0,'intval'),
				);
			if( I('isend',0,'intval') == 1 ) {
				$data['endtime'] = strtotime( I('enddate').' '.I('e_hour',0,'intval').':'.I('e_minute',0,'intval') );
			} else {
				$data['endtime'] = 0;
			}
			// $rest = M('calendar')->fetchSql(false)->save($data);
			$rest = $this->db->update($data);
			if( $rest ) {
				echo 1;
			} else {
				echo '更新失败！';
			}
			exit;
		}
		// $rest = M('calendar')->find(I('id'));
		$rest = $this->db->getRow('*', ['id'=>I('id')]);
		if($rest){
			$this->id = $rest['id'];
			$this->title = $rest['title'];
			$starttime = $rest['starttime'];
			$this->start_d = date("Y-m-d",$starttime);
			$this->start_h = date("H",$starttime);
			$this->start_m = date("i",$starttime);

			$endtime = $rest['endtime'];
			if($endtime==0){
				$this->end_d = $startdate;
				$this->end_chk = '';
				$this->end_display = "style='display:none'";
			}else{
				$this->end_d = date("Y-m-d",$endtime);
				$this->end_h = date("H",$endtime);
				$this->end_m = date("i",$endtime);
				$this->end_chk = "checked";
				$this->end_display = "style=''";
			}

			$allday = $rest['isallday'];
			if($allday==1){
				$this->display = "style='display:none'";
				$this->allday_chk = "checked";
			}else{
				$this->display = "style=''";
				$this->allday_chk = '';
			}
			$this->display();
		}
	}

	//删除事件
	Public function schedule_del() {
		// $rest = M('calendar')->delete(I('id'));
		$rest = $this->db->del(['id' => I('id')]);
		if( $rest ) {
			echo 1;
		} else {
			echo '删除失败！';
		}
	}
}