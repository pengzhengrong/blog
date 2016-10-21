<?php 

namespace Home\Library\Weixin\MyMenu;

/**
 * 菜单的按钮分为一级菜单和二级菜单. 一级菜单最多3个,二级菜单最多5个.
 */
class Button {

	protected $type = '';
	protected $name = '';
	protected $key = '';
	protected $url = '';
	protected $subButton = array();

	public function __construct($name, $type='', $url='', $key='') {
		$this->name = $name;
		$this->type = $type;
		$this->url = $url;
		$this->key = $key;
	}

	public function getButton() {
		$data = array();
		if (empty($this->name)) {
			exit('菜单名称不能为空!');
		}
		$data['name'] = $this->name;

		if ($this->subButton) {
			$data['sub_button'] = $this->subButton;
			return $data;
		}
		if ($this->type) {
			$data['type'] = $this->type;
		}
		if ($this->url) {
			$data['url'] = $this->url;
		} elseif ($this->key) {
			$data['key'] = $this->key;
		}
		return $data;


	}

	public function addSubButton($subButton) {
		$this->subButton[] = $subButton->getButton();
	}

	public function __set($key, $value) {
		$this->$key = $value;
	}

	public function __get($key) {
		return $this->$key;
	}

}