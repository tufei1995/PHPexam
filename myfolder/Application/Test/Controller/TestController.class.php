<?php
namespace Test\Controller;

class TestController extends CommonController {

	/* 考试列表
	 * @time 2017-04-19
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function lists(){
		if (IS_AJAX) {
			$test = M('test');
			if ($_POST['id']) {
				$this->result(1, '', $test->find(I('param.id')));
			}
			$start = I('param.page');
			if (I('param.keyword')) $where['title'] = array('like', '%' . I('param.keyword') . '%');
			$this->result(1, '', $test->where($where)->order('tid desc')->page($start, '20')->select() , $test->where($where)->count());
		} else {
			$this->assign('course', M('course')->field('id,title')->select());
			$this->display();
		}
	}

	/* 学生自测
	 * @time 2017-04-24
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function selftest(){
		$history = M('history');
		if ($_GET['id']) {
			$data = $history->field("uid,pid,count(DISTINCT qid) as done")->where('pid=\''.I('get.id').'\'')->group('uid')->select();
			$userinfo = M('user')->field('uid,username sno,name,(select title from class where class.id=user.class) as `class`')->where(array('uid'=>array('in', array_column($data, 'uid'))))->select();
			foreach ($userinfo as $k => $v) {
				$info[$v['uid']] = $v;
			}
			$lists = $history->field('uid,type,qid,status')->where('pid=\''.I('get.id').'\'')->select();
			foreach ($lists as $k => $v) {
				$count[$v['uid']]['right'] += $v['status'];
				$count[$v['uid']][$v['type']]++;
				$count[$v['uid']][$v['type'].'_right'] += $v['status'];
			}
			foreach ($data as &$v) {
				$v = array_merge($v, $count[$v['uid']], $info[$v['uid']]);
				$v['title'] = getChainNameById($v['pid']);
			}
			$this->assign('data', $data);
			$this->display('selftest_info');
		} else {
			$data = $history->field('pid,count(DISTINCT uid) as users,((select count(uid) from choice where pid = history.pid)+(select count(0) from judge where pid = history.pid)) as total')->group('pid')->select();
			foreach ($data as $key => &$value) {
				$value['title'] = getChainNameById($value['pid']);
			}
			$this->assign('data', $data);
			$this->display();
		}
	}

	/* 考生名单
	 * @time 2017-04-24
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function student_list(){
		if (IS_AJAX) {
			$user = M('user');
			$start = I('param.page');
			$data = M('report')->where(array('tid'=>I('get.id')))->select();
			$uid = array_column($data, 'uid');
			$where['uid'] = array('in', implode(',', $uid));
			if (I('param.keyword')) $where['name|username'] = array('like', '%' . I('param.keyword') . '%');
			$this->result(1, '', $user->field('username,name,uid,create_time,(select title from `class` where id=user.class) as `class`')->where($where)->order('uid desc')->page($start, '20')->select() , $user->where($where)->count());
		} else {
			$this->display();
		}
	}

	/* 添加考生
	 * @time 2017-04-24
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function add_student(){
		if (IS_AJAX) {
			$user = M('user');
			if ($_POST['id']) {
				if (is_array($_POST['id'])) {
					foreach ($_POST['id'] as $v) {
						$data[] = array('tid'=>$_GET['id'], 'uid'=>$v);
					}
					$rel = M('report')->addAll($data);
				} else {
					$data = array('tid'=>$_GET['id'], 'uid'=>$_POST['id']);
					$rel = M('report')->add($data);
				}
				send_message($_POST['id'], '您有一项新的考试待参加，<a href="../exam/test">点击查看</a>');
				$rel ? $this->success('添加成功！') : $this->error('添加失败！');
			}
			$start = I('param.page');
			$data = M('report')->where(array('tid'=>I('get.id')))->select();
			$uid = array_column($data, 'uid');
			$where['uid'] = array('not in', implode(',', $uid));
			$where['class'] = I('post.class');
			if (I('param.keyword')) $where['class'] = I('param.keyword');
			$this->result(1, '', $user->field('username,name,uid,(select title from `class` where id=user.class) as `class`')->where($where)->order('uid desc')->page($start, '20')->select() , $user->where($where)->count());
		} else {
			$this->assign('college', M('class')->field('id,title')->where(array('pid' => 0))->select());
			$this->display();
		}
	}

	/* 考试成绩
	 * @time 2017-04-24
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function report(){
		$report = M('report');
		$lists = $report->where(array('tid'=>I('get.id'), 'status'=>0))->select();
		$uids = array_column($lists, 'uid');
		if (!$uids) alert('暂时没有考生完成考试');
		$userinfo = M('user')->field('uid,username sno,name,(select title from class where class.id=user.class) as `class`')->where(array('uid'=>array('in', $uids)))->select();
		foreach ($userinfo as $k => $v) {
			$uinfo[$v['uid']] = $v;
		}
		foreach ($lists as $k => $v) {
			$uid = $v['uid'];
			$count[$uinfo[$uid]['class']]['people']++;
			$count[$uinfo[$uid]['class']]['zongfen'] += $v['zongfen'];
			$count[$uinfo[$uid]['class']]['keguan'] += $v['keguan'];
			$v = unserialize($v['statistic']);
			$count[$uinfo[$uid]['class']]['total'] += $v['count']['total'];
			$count[$uinfo[$uid]['class']]['choice']['total'] += $v['count']['choice']['total'];
			$count[$uinfo[$uid]['class']]['judge']['total'] += $v['count']['judge']['total'];
			$count[$uinfo[$uid]['class']]['filling']['total'] += $v['count']['filling']['total'];
			$count[$uinfo[$uid]['class']]['subjective']['total'] += $v['count']['subjective']['total'];
			$count[$uinfo[$uid]['class']]['right'] += $v['count']['right'];
			$count[$uinfo[$uid]['class']]['choice']['right'] += $v['count']['choice']['right'];
			$count[$uinfo[$uid]['class']]['judge']['right'] += $v['count']['judge']['right'];
			$count[$uinfo[$uid]['class']]['filling']['right'] += $v['count']['filling']['right'];
			$count[$uinfo[$uid]['class']]['subjective']['right'] += $v['count']['subjective']['right'];
			$count[$uinfo[$uid]['class']]['zhuguan'] += $v['count']['zhuguan'];
			unset($v['choice'], $v['judge'], $v['filling'], $v['subjective']);
			$v = array_merge($uinfo[$uid], $v);
			$reports[$v['class']][] = $v;
		}
		$this->assign('data', M('test')->find($_GET['id']));
		$this->assign('count', $count);
		$this->assign('reports', $reports);
		$this->display();
	}

	/* 阅卷
	 * @time 2017-04-24
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function mark(){
		if (IS_POST) {
			if (IS_AJAX) {
				$test = M('test');
				$start = I('param.page');
				$field = "*,(select count(0) from report where report.tid = test.tid and is_mark='1' and status='0') done,(select count(0) from report where report.tid = test.tid and status='0') need";
				$where['need_mark'] = '1';
				if (I('param.keyword')) $where['title'] = array('like', '%' . I('param.keyword') . '%');
				$this->result(1, '', $test->field($field)->where($where)->order('tid desc')->page($start, '20')->select() , $test->where($where)->count());
			} else {
				$report = M('report');
				$tid = $_POST['tid'];
				unset($_POST['tid']);
				$uids = array_keys($_POST);
				$lists = $report->where(array('tid'=>$tid, 'uid'=>array('in', $uids)))->select();
				foreach ($lists as $k => $v) {
					$answer = unserialize($v['answer']);
					$statistic = unserialize($v['statistic']);
					foreach ($answer as $subk => $subv) {
						if ($subk == 'filling' || $subk == 'subjective') {
							foreach ($_POST[$v['uid']][$subk] as $ssk => $ssv) {
								$statistic[$subk][$ssk] = $ssv;
								$statistic['zscore'] += $ssv;
								$statistic['score'] += $ssv;
							}
						}
					}
					$data = array();
					$data['zongfen'] = $statistic['score'];
					$data['zhuguan'] = $statistic['zscore'];
					$data['is_mark'] = 1;
					$data['statistic'] = serialize($statistic);
					$rel = $report->where(array('tid'=>$tid, 'uid'=>$v['uid']))->save($data);
				}
				if ($rel) {
					$this->success('阅卷成功！', './mark');
				} else {
					$this->error('阅卷失败！');
				}
			}
		} else {
			if ($_GET['id']) {
				$lists = M('report')->where(array('tid'=>I('get.id'), 'status'=>0, 'is_mark'=>0))->select();
				foreach ($lists as $k => $v) {
					$answer = unserialize($v['answer']);
					$testinfo = M('test')->find($_GET['id']);
					$qids = unserialize($testinfo['qids']);
					foreach ($answer as $subk => $subv) {
						if ($subk == 'filling' || $subk == 'subjective') {
							$ques = M($subk)->field(substr($subk, 0, 1).'id,title')->where(array(substr($subk, 0, 1).'id'=>array('in', $qids[$subk])))->select();
							$qtitle = array_column($ques, 'title', substr($subk, 0, 1).'id');
							$info = $subk == 'filling' ? '填空题' : '主观题';
							foreach ($subv as $ssk => $ssv) {
								$data[$subk][] = array('uid'=>$v['uid'], 'qid'=>$ssk, 'ques'=>$qtitle[$ssk], 'answer'=>$ssv);
							}
						}
					}
				}
				if (!count($data)) alert('暂时没有需要阅卷的内容！');
				$this->assign('data', $data);
				$this->display('mark_list');
				exit();
			}
			$this->display();
		}
	}
	public function statistic() {
		$data = M('test')->find(intval($_GET['id']));
		$ques = getPaperQuesList(unserialize($data['qids']));
		foreach ($ques as $k => $v) {
	 		foreach ($v as $subk => $subv) {
	 			$count['total']['total']++;
	 			$count['total'][$k]++;
	 			$count['total'][$subv['difficulty']]++;
	 			$count[$k][$subv['difficulty']]++;
	 			$count['pids'][$subv['pid']]++;
	 			$count['pid'][$subv['pid']][$k]++;
	 			$count['pid'][$subv['pid']][$subv['difficulty']]++;
	 		}
		}
		$this->assign('ques', $ques);
		$this->assign('data', $data);
		$this->assign('count', $count);
		$this->display();
	}
	/* 导出试题
	 * @time 2017-04-28
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function export() {
		import('Org.Util.Word');
		$i = 0;
		$count = 1;
		$str = '';
		$data = M('test')->find(intval($_GET['id']));
		$ques = getPaperQuesList(unserialize($data['qids']));
		$listMap = array('一', '二', '三', '四');
		$typeMap = array('choice'=>'选择题', 'filling'=>'填空题', 'judge'=>'判断题', 'subjective'=>'主观题');
		foreach ($ques as $k => $v) {
		 	if ($v) {
				$count = 1;
		 		$str .= $listMap[$i++].'、'.$typeMap[$k].'<w:br />';
		 		foreach ($v as $subk => $subv) {
		 			$str .= $count++.'. '.$subv['title'].'<w:br />';
		 			if ($k == 'choice') {
		 				$str .= ' A. '.$subv['ansa'].'<w:br />';
		 				$str .= ' B. '.$subv['ansb'].'<w:br />';
		 				$str .= ' C. '.$subv['ansc'].'<w:br />';
		 				$str .= ' D. '.$subv['ansd'].'<w:br />';
		 				$str .= '答案：'.$subv['right_ans'].'<w:br />';
		 			} else if ($k == 'judge') {
		 				$str .= '答案：'.$subv['right_ans'].'<w:br />';
		 			} else if ($k == 'subjective') {
		 				$str .= '<w:br /><w:br /><w:br /><w:br />';
		 				$str .= '<w:br /><w:br />参考答案：'.$subv['right_ans'].'<w:br />';
		 			} else {
		 				$str .= '参考答案：'.$subv['right_ans'].'<w:br />';
		 			}
		 		}
		 		$str .= '<w:br />';
		 	}
		 }
		$data['ques'] = $str;
		new \Word($data, $data['title']);
	}
}