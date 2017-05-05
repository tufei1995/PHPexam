<?php
namespace Test\Controller;

class UserController extends CommonController {
	
	/* 账号密码登录
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function login(){
		$user = M('user');
		if (IS_POST) {
			$check = $user->field('*,(select `name` from `group` where `gid` = `user`.gid) as `group`')->where(array('username' => I('post.username')))->find();
			if (md5(I('post.password')) == $check['password']) {
				unset($check['password']);
				$_SESSION = $check;
				$_SESSION['access'] = setAccess($check['access']);
				$_SESSION['menu'] = getMenu($check['access']);
				$_SESSION['messages'] = M('message')->where(array('uid'=>$check['uid'], 'read'=>'N'))->count();
				if ($check['name'] && $check['college'] && $check['class']) {
					header('Location:../index/index');
				} else {
					header('Location:../my/profile');
				}
			} else {
				$this->assign('message', '*账号或密码错误！');
				$this->display();
			}
		} else {
			$this->display();
		}
	}
	
	/* 注销登录
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function logout() {
		$_SESSION = array();
		header('Location:../user/login');
	}
	
	/* 注册新账户
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function register(){
		$user = M('user');
		if (IS_POST) {
			if ($_POST['password'] != $_POST['password1']) {
				$this->assign('message', '*两次输入的密码不一致！');
				$this->display();
				exit();
			}
			if ($user->where(array('username'=>I('post.username')))->find()) {
				$this->assign('message', '*该账号已存在，<a href="../user/login">点此登陆</a>');
				$this->display();
				exit();
			}
			$_POST['access'] = M('group')->where('gid = 1')->getField('access');
			$_POST['password'] = md5($_POST['password']);
			$_POST['create_time'] = date('Y-m-d H:i:s');
			$rel = $user->add($_POST);
			if ($rel) {
				$this->success('注册成功', '../user/login');
			} else {
				$this->error('注册失败');
			}
		} else {
			$this->display();
		}
	}
	
	/* 重置密码
	 * @time 2017-04-10
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function resetPassword(){
		$user = M('user');
		if (IS_POST) {
			if ($_POST['password'] != $_POST['password1']) {
				$this->assign('message', '*两次输入的密码不一致！');
				$this->display();
				exit();
			}
			$check = M('code')->where(array('to'=>I('post.email')))->order('id desc')->limit(1)->getField('code');
			if ($check != $_POST['code']){
				$this->assign('message', '*验证码不正确！');
				$this->display();
				exit();				
			}
			$password = md5($_POST['password']);
			$rel = $user->where(array('email'=>I('post.email')))->save(array('password'=>$password));
			if ($rel) {
				$this->success('密码重置成功', '../user/login');
			} else {
				$this->error('密码重置失败');
			}
		} else {
			$this->display();
		}
	}
	
	/* 发送邮件
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function sendMail(){
		$db = M('code');
		$code = rand(100000, 999999);
		$check = $db->order('id desc')->where("`to` = '".I('post.email')."' or `ip` = '".get_client_ip()."'")->limit(1)->getField('timestamp');
		if (time() - $check < 60 || cookie('forbid') == 'yes') {
			$this->result(0, '*邮件发送请求过于频繁！');		
		}
		M('code')->add(array('code'=>$code, 'to'=>I('post.email'), 'timestamp'=>time(), 'ip'=>get_client_ip()));
		$data = array(
			'to'			=>	I('post.email'),
			'subject'		=>	'您正在重置'.C('SYS_NAME').'账户的密码',
			'fromname'		=>	'考试系统邮件中心',
			'html'			=>	"\r\n您正在重置密码，您的验证码是<b>".$code."</b>",
		);
		import('@.Org.SendCloud');
		$scloud = new \SendCloud($data);
		if ($scloud->send()) {
			$this->result(1, '');
		} else {
			$this->result(0, '');
		}
		cookie('forbid', 'yes', 60);
	}
	
	/* qq登录
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function qqback() {
		$user = M('user');
		if (IS_POST) {
			$check = $user->where(array('username' => I('post.username')))->getField('password');
			if (md5(I('post.password')) == $check) {
				$rel = $user->where(array('username' => I('post.username')))->save(array('oid'=>I('post.oid')));
				if ($rel) {
					$this->success('绑定成功');
				} else {
					$this->error('绑定失败');
				}
			} else {
				$this->assign('message', '账号或密码错误');
				$this->display();
			}
		} else {
			if ($_GET['sign'] == md5($_SERVER['HTTP_USER_AGENT'] . $_GET['data'] . 'test')) {
				$data = unserialize(base64_decode($_GET['data']));
				$check = $user->field('*,(select `name` from `group` where `gid` = `user`.gid) as `group`')->where(array('oid' => $data['userid']))->find();
				if ($check) {
					$_SESSION = $check;
				$_SESSION['access'] = setAccess($check['access']);
				$_SESSION['menu'] = getMenu($check['access']);
				$_SESSION['messages'] = M('message')->where(array('uid'=>$check['uid'], 'read'=>'N'))->count();
					header('Location:../index/index');
				} else {
					if ($_SESSION['username']){
						$rel = $user->where(array('uid' => $_SESSION['uid']))->save(array('oid'=>$data['userid']));
						if ($rel) {
							$_SESSION['oid'] = $data['userid'];
							$this->success('绑定成功', '../index/index');
							exit();
						} else {
							$this->error('绑定失败', '../index/index');
							exit();
						}						
					}
					$this->assign('data', $data);
					$this->display();
				}
			} else {
				echo "invalid signature";
			}
		}
	}
	/* 微博登录
	 * @time 2017-04-06
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function wbback() {
		$user = M('user');
		if (IS_POST) {
			$check = $user->where(array('username' => I('post.username')))->getField('password');
			if (md5(I('post.password')) == $check) {
				$rel = $user->where(array('username' => I('post.username')))->save(array('wid'=>I('post.wid')));
				if ($rel) {
					$this->success('绑定成功');
				} else {
					$this->error('绑定失败');
				}
			} else {
				$this->assign('message', '账号或密码错误');
				$this->display();
			}
		} else {
			if ($_GET['sign'] == md5($_SERVER['HTTP_USER_AGENT'] . $_GET['data'] . 'test')) {
				$data = unserialize(base64_decode($_GET['data']));
				$check = $user->field('*,(select `name` from `group` where `gid` = `user`.gid) as `group`')->where(array('wid' => $data['userid']))->find();
				if ($check) {
					$_SESSION = $check;
				$_SESSION['access'] = setAccess($check['access']);
				$_SESSION['menu'] = getMenu($check['access']);
				$_SESSION['messages'] = M('message')->where(array('uid'=>$check['uid'], 'read'=>'N'))->count();
					header('Location:../index/index');
				} else {
					if ($_SESSION['username']){
						$rel = $user->where(array('uid' => $_SESSION['uid']))->save(array('wid'=>$data['userid']));
						if ($rel) {
							$_SESSION['wid'] = $data['userid'];
							$this->success('绑定成功', '../index/index');
							exit();
						} else {
							$this->error('绑定失败', '../index/index');
							exit();
						}						
					}
					$this->assign('data', $data);
					$this->display();
				}
			} else {
				echo "invalid signature";
			}
		}
	}
}