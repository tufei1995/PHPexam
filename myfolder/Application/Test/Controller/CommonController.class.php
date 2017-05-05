<?php
namespace Test\Controller;
use Think\Controller;

class CommonController extends Controller {

	public function __construct(){
		parent::__construct();
		if (CONTROLLER_NAME != 'User') {
			$this->checkLogin();
		}
	}

	public function _empty(){
		header('HTTP/1.1 404 Not Found');
		echo '请求的资源不存在';
	}
	
	/* 检测登陆
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	private function checkLogin(){
		if(empty($_SESSION['username'])) {
			if (IS_AJAX) {
				$this->result(0, '登录已失效，请重新登陆！');
			}
			header("Location:../user/login");
			exit();
		}
		if ($_SESSION['verify'] != '1' && !in_array(CONTROLLER_NAME, array('Common', 'Index', 'My'))) alert('请完善个人信息，若已完善请等待教师审核您的身份', '../my/profile');
		if (!array_key_exists(CONTROLLER_NAME, $_SESSION['access']) && !in_array(CONTROLLER_NAME, array('Common', 'Index', 'My')) && !in_array(ACTION_NAME, $_SESSION['access'][CONTROLLER_NAME])) accessDenied();
		if (!in_array(CONTROLLER_NAME, array('Common', 'Index', 'My')) && !in_array(ACTION_NAME, $_SESSION['access'][CONTROLLER_NAME])) accessDenied();
		if (in_array(ACTION_NAME, array('add', 'edit', 'delete'))) {
			if (!in_array($_REQUEST['table'], $_SESSION['access'][ACTION_NAME])) accessDenied();
		}
	}
	
	/* ajax返回列表页
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function result($rel, $msg, $data, $total) {
		if ($rel) $return['status'] = 1;
		else $return['status'] = 0;
		if ($msg) $return['msg'] = $msg;
		if (isset($data)) $return['data'] = $data;
		if (isset($total)) $return['total'] = $total;
		$this->ajaxReturn($return);
	}

	/* 输出alert();
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function alert($msg, $url) {
	    $url = $url ? 'location.href="' . $url . '";' : 'history.back(-1);';
	    echo "<html><head><meta charset='utf-8'><script>alert('{$msg}');" . $url . "</script></head></html>";
	    exit();
	}

	/* 公共删除
	 * @time 2017-04-10
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function delete() {
		if (IS_AJAX) {
			$type = I('param.table');
			$key = I('param.key');
			if ($type == 'report') {
				if (!is_array($key)) {
					$arr = explode('-', $key);
					$uid = $arr[0];
				} else {
					foreach ($key as $k => $v) {
						$arr = explode('-', $v);
						$uid[] = $arr[0];
					}
				}
				$tid = $arr[1];
				$rel = !is_array($uid) ? M('report')->where(array('uid'=>$uid, 'tid'=>$tid))->delete() : M('report')->where(array('uid'=>array('in',implode(',', $uid)), 'tid'=>$tid))->delete();
			} else {
				$db = M($type);
				$rel = $db->query('describe `' . $type . '`');
				
				foreach ($rel as $v) {
					if ($v['key'] == 'PRI') {
						$k = $v['field'];
						break;
					}
				}
				$rel = is_numeric($key) ? $db->where("`$k` = '$key'")->delete() : $db->where(array($k => array('in',implode(',', $key))))->delete();
			}
			if ($rel) {
				$this->result(1, '删除成功！');
			} else {
				$this->result(0, '删除失败！');
			}
		}
	}

	/* 公共增
	 * @time 2017-04-10
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function add() {
		if (IS_POST) {
			$table = I('post.table');
			unset($_POST['table']);
			if ($table == 'choice') {
				$_POST['type'] == 'single' ? $_POST['right_ans'] = $_POST['right_ans'] : $_POST['right_ans'] = implode('，', $_POST['right_ans']);
			}
			if ($table == 'user') {
				if ($_POST['password']) {
					$_POST['password'] = md5($_POST['password']);
				} else {
					unset($_POST['password']);
				}
				$_POST['access'] = M('group')->where(array('gid'=>I('post.gid')))->getField('access');
			}
			$table != 'user' ? $_POST['uid'] = $_SESSION['uid'] : '';
			if ($table == 'test') {
				$qid = createPaper($_POST['qids'], $_POST['range']);
				$_POST['qids'] = serialize($qid);
				$_POST['need_mark'] = $qid['filling'] || $qid['subjective'];
				$_POST['range'] = implode(',', $_POST['range']);
			}
			if (M($table)->add($_POST)) {
				$this->success('添加成功！', $_SERVER['HTTP_REFERER']);
			} else {
				$this->error('添加失败！');
			}
		} else {
			$this->display();
		}
	}

	/* 公共改
	 * @time 2017-04-10
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function edit() {
		if (IS_POST) {
			$table = I('post.table');
			$db = M($table);
			$rel = $db->query('describe `' . $table . '`');
			
			foreach ($rel as $v) {
				if ($v['key'] == 'PRI') {
					$k = $v['field'];
					break;
				}
			}
			$id = intval($_POST[$k]);
			unset($_POST['table'], $_POST[$k]);
			if ($_POST['password']) {
				$_POST['password'] = md5($_POST['password']);
			} else {
				unset($_POST['password']);
			}
			isset($_POST['access']) && $_POST['access'] ? $_POST['access'] = implode(',', $_POST['access']) : '';
			if ($table == 'test') {
				$_POST['type'] = implode(',', $_POST['type']);
				$_POST['range'] = implode(',', $_POST['range']);
			}
			if (M($table)->where("$k = '{$id}'")->save($_POST)) {
				$this->success('修改成功！', $_SERVER['HTTP_REFERER']);
			} else {
				$this->error('未作任何修改！');
			}
		}
	}

	/* 根据课程获取章节信息
	 * @time 2017-04-11
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function getSectionInfo(){
		if ($_POST['id']){
			$this->result(1, '', getSectionInfo($_POST['id']));
		}
	}

	/* 根据课程获取章信息
	 * @time 2017-04-11
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function getChapterInfo(){
		if ($_POST['id']){
			$this->result(1, '', getChapterInfo($_POST['id']));
		}
	}

	/* 根据ID获取章节信息
	 * @time 2017-04-11
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function getChapterInfoById(){
		if (IS_AJAX){
			$this->result(1, '', M('chapter')->find(I('param.id')));
		}
	}

	/* ajax获取学院下班级列表
	 * @time 2017-04-07
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function getClassInfo() {
		if (IS_AJAX) {
			$this->result(1, 'ok', M('class')->field('id,title')->where(array('pid' => I('param.id')))->select());
		}
	}

	/* ajax获取班级信息
	 * @time 2017-04-07
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function getClassInfoById() {
		if (IS_AJAX) {
			$this->result(1, '', M('class')->find(I('param.id')));
		}
	}
}