<?php
namespace Test\Controller;

class SettingController extends CommonController {

	/* 章节管理
	 * @time 2017-04-07
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function chapter() {
		$chapter = M('chapter');
		if (IS_AJAX) {
			$where = array();
			$start = I('param.page');
			if (I('param.keyword')) $where['title'] = array('like', '%' . I('param.keyword') . '%');
			$data = $chapter->where($where)->order('pid asc')->select();
			$data = getTreeList($data);
			$this->result(1, '', $data);
		} else {
			$data = $chapter->select();
			$data = getTreeList($data);
			$this->assign('course', M('course')->field('id,title')->select());
			$this->display();
		}
	}

	/* 章节管理
	 * @time 2017-04-07
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function classes() {
		$class = M('class');
		if (IS_AJAX) {
			$start = I('param.page');
			if (I('param.keyword')) $where['title'] = array('like', '%' . I('param.keyword') . '%');
			$data = $class->where($where)->select();
			$data = getTreeList($data);
			$this->result(1, '', $data);
		} else {
			$this->assign('college', $class->field('id,title')->where(array('pid'=>0))->select());
			$this->display();
		}
	}

	/* 课程管理
	 * @time 2017-04-14
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function course() {
		$course = M('course');
		if (IS_AJAX) {
			$start = I('param.page');
			if ($_POST['id']) $this->result(1, '', $course->find(I('post.id')));
			if (I('param.keyword')) $where['title'] = array('like', '%' . I('param.keyword') . '%');
			$data = $course->field('title,id,uid')->where($where)->order('id asc')->select();
			$this->result(1, '', $data);
		} else {
			$this->display();
		}
	}

	/* 用户管理
	 * @time 2017-04-14
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function user() {
		$user = M('user');
		if (IS_AJAX) {
			if ($_POST['id']) $this->result(1, '', M('user')->field('uid,username,name,gid,(select title from class where class.id=user.class) as class')->find($_POST['id']));
			$start = I('param.page');
			if (I('param.keyword')) $where['name|username'] = array('like', '%' . I('param.keyword') . '%');
			$this->result(1, '', $user->field('username,name,uid,create_time,(select title from `class` where id=user.class) as `class`,(select name from `group` where gid=user.gid) as `group`')->where($where)->order('uid desc')->page($start, '20')->select() , $user->where($where)->count());
		} else {
			$this->assign('group', M('group')->field('gid,name')->select());
			$this->display();
		}
	}
	public function group() {
		if (IS_AJAX) {
			$group = M('group');
			if ($_POST['id']) {
				$id = I('param.id');
				$data = $group->find($id);
				$this->result(1, '', $data);
			}
			$start = I('param.page');
			if (I('param.keyword')) $where['name'] = array('like', '%' . I('param.keyword') . '%');
			$this->result(1, '', $group->where($where)->order('gid asc')->page($start, '20')->select(), $group->where($where)->count());
		} else {
			$this->display();
		}
	}
	public function grant() {
		if (IS_POST) {
			$table = I('post.table');
			$k = $table == 'user' ? 'uid' : 'gid';
			$id = intval($_POST[$k]);
			$_POST['access'] ? $_POST['access'] = implode(',', $_POST['access']) : '';
			if (M($table)->where("$k = '{$id}'")->save($_POST)) {
				$this->success('授权成功！', $_SERVER['HTTP_REFERER']);
			} else {
				$this->error('权限未作任何修改！');
			}
		} else {
			$access = M('access');
			$data = $_GET['g'] ? M('group')->find($_GET['g']) : M('user')->find($_GET['u']);
			$data['access'] = json_encode(explode(',', $data['access']));
			$this->assign('data', $data);
			$this->assign('access', getAccessArray($access->select()));
			$this->display();			
		}
	}
	public function access(){
		if (IS_AJAX) {
			$access = M('access');
			if ($_POST['id']) {
				$this->result(1, '', $access->find($_POST['id']));
			}
			$start = I('param.page');
			if (I('param.keyword')) $where['title'] = array('like', '%' . I('param.keyword') . '%');
			$data = $access->where($where)->select();
			if (!I('param.keyword')) $data = getTreeList($data, 'aid');
			$this->result(1, '', $data);
		} else {
			$this->assign('pid', M('access')->field('aid,title')->where(array('pid'=>0))->select());
			$this->display();
		}
	}
	public function verify(){
		$user = M('user');
		if (IS_AJAX) {
			if ($_POST['id']) $this->result($user->where(array('uid'=>I('post.id')))->save(array('verify'=>1)), '');
			$start = I('param.page');
			$where['verify'] = 0;
			if (I('param.keyword')) $where['name|username'] = array('like', '%' . I('param.keyword') . '%');
			$this->result(1, '', $user->field('username,name,uid,create_time,(select name from `group` where gid=user.gid) as `group`')->where($where)->order('uid desc')->page($start, '20')->select() , $user->where($where)->count());
		} else {
			$this->assign('group', M('group')->field('gid,name')->select());
			$this->display();
		}
	}
}