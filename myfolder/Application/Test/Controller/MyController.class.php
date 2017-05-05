<?php
namespace Test\Controller;

class MyController extends CommonController {
	public function index(){
		$this->display();
	}
	
	/* 站内消息
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function message() {
		if (IS_AJAX) {
			$this->result(1, '', M('message')->where("`uid` = '{$_SESSION['uid']}'")->order('id desc')->select() , M('message')->where("`uid` = '{$_SESSION['uid']}'")->count());
		} else {
			$this->display();
		}
	}
	
	/* 标记为已读
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function read() {
		if (IS_AJAX) {
			$key = I('param.key');
			$db = M('message');
			$rel = is_numeric($key) ? $db->where("`id` = '$key'")->save(array('read' => 'Y')) : $db->where(array('id' => array('in', implode(',', $key))))->save(array('read' => 'Y'));
			if ($rel) {
				$_SESSION['messages'] = $db->where("`read` = 'N' and `uid` = '{$_SESSION['uid']}'")->count();
				$this->result(1, '操作成功！');
			} else {
				$this->result(0, '操作失败！');
			}
		}
	}
	
	/* 全部标记为已读
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function readall() {
		if (IS_AJAX) {
			$rel = M('message')->where("`uid` = '{$_SESSION['uid']}'")->save(array('read' => 'Y'));
			if ($rel) {
				$_SESSION['messages'] = 0;
				$this->result(1, '操作成功！');
			} else {
				$this->result(0, '操作失败！');
			}
		}
	}
	
	/* 解绑QQ
	 * @time 2017-04-10
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function unbind() {
		$rel = M('user')->where("`uid` = '{$_SESSION['uid']}'")->save(array('oid' => ''));
		if ($rel) {
			$_SESSION['oid'] = '';
			$this->success('解绑成功', '../my/profile');
			exit();
		} else {
			$this->error('解绑失败', '../my/profile');
			exit();
		}
	}
	
	/* 我的资料
	 * @time 2017-04-10
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function profile() {
		$user = M('user');
		if (IS_POST) {
			$rel = M('user')->where("`uid` = '{$_SESSION['uid']}'")->save($_POST);
			if ($rel) {
				$_SESSION['name'] = I('param.name');
				$this->success('操作成功！');
			} else {
				$this->error('个人信息未作修改！');
			}
		} else {
			$data = $user->field('*,(select `name` from `group` where `gid` = `user`.gid) as `group`')->where(array('uid' => $_SESSION['uid']))->find();
			$this->assign('college', M('class')->field('id,title')->where(array('pid' => 0))->select());
			$this->assign('class', M('class')->field('id,title')->where(array('pid' => $data['college']))->select());
			$this->assign('data', $data);
			$this->display();
		}
	}
}