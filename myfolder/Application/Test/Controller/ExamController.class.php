<?php
namespace Test\Controller;

class ExamController extends CommonController {

	/* 自我测试
	 * @time 2017-04-11
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function selftest(){
		if (IS_AJAX) {
			$this->result(1, '', M('chapter')->field('id,title,courseid,(select count(0) from history where pid = chapter.id and uid=\''.$_SESSION['uid'].'\')/((select count(0) from choice where pid = chapter.id)+(select count(0) from judge where pid = chapter.id)) as process,((select count(0) from choice where pid = chapter.id)+(select count(0) from judge where pid = chapter.id)) as total')->where("`pid` != 0 and `courseid` = '{$_POST['id']}'")->select());
		} else {
			$this->assign('course', M('course')->select());
			$this->display();
		}
	}

	/* 开始自我测试
	 * @time 2017-04-17
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function start_self_test(){
		if (IS_POST) {
			$history = array();
			foreach ($_POST as $k => $v) {
				$qid = substr($k, 1);
				if (substr($k, 0, 1) == 'c') {
					$cids[] = $qid;
				} elseif (substr($k, 0, 1) == 'j') {
					$jids[] = $qid;
				}
			}
			$cids ? $cdata = M('choice')->where(array('cid'=>array('in', implode(',', $cids))))->select() : '';
			$choice = array_column($cdata, 'right_ans', 'cid');
			$jids ? $jdata = M('judge')->where(array('jid'=>array('in', implode(',', $jids))))->select() : '';
			$judge = array_column($jdata, 'right_ans', 'jid');
			foreach ($_POST as $k => $v) {
				$qid = substr($k, 1);
				if (substr($k, 0, 1) == 'c') {
					$v = is_array($v) ? implode('，', $v) : $v;
					$history[] = array('uid'=>$_SESSION['uid'], 'qid'=>$qid, 'type'=>'choice', 'pid'=>$_POST['pid'], 'answer'=>$v, 'status'=>$choice[$qid] == $v ? 1 : 0);
					unset($_POST[$k]);
				} elseif (substr($k, 0, 1) == 'j') {
					$history[] = array('uid'=>$_SESSION['uid'], 'qid'=>$qid, 'type'=>'judge', 'pid'=>$_POST['pid'], 'answer'=>$v, 'status'=>$judge[$qid] == $v ? 1 : 0);
					unset($_POST[$k]);
				}
			}
			$_POST['uid'] = $_SESSION['uid'];
			$rel = M('history')->addAll($history);
			if ($rel) {
				$this->success('提交成功！', './selftest');
			} else {
				$this->error('提交失败！');
			}
		} else {
			$history = M('history');
			$choice = $history->where(array('uid'=>$_SESSION['uid'],'type'=>'choice', 'pid'=>I('get.id')))->select();
			$chistory = implode(',', array_column($choice, 'qid'));
			$judge = $history->where(array('uid'=>$_SESSION['uid'],'type'=>'judge', 'pid'=>I('get.id')))->select();
			$jhistory = implode(',', array_column($judge, 'qid'));
			$cdata = M('choice')->where(array('cid'=>array('not in', $chistory), 'pid'=>I('get.id')))->order('rand()')->select();
			$jdata = M('judge')->where(array('jid'=>array('not in', $jhistory), 'pid'=>I('get.id')))->order('rand()')->select();
			if (count($jdata)+count($cdata) == 0) {
				alert('暂时没有需要做的题目！');
			} else {
				$this->assign('choice', $cdata);
				$this->assign('data', M('chapter')->find($_GET['id']));
				$this->assign('judge', $jdata);
				$this->assign('count', count($cdata));
				$this->assign('total', count($jdata)+count($cdata));
				$this->display();
			}
		}
	}

	/* 自我测试报告
	 * @time 2017-04-17
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function report(){
		if (IS_AJAX) {
			$history = M('history');
			$status = $_POST['t'] == '0' ? 1 : 0;
			$_POST['uid'] = $_SESSION['uid'];
			$rel = $history->where($_POST)->save(array('collect'=>$status));
			$this->result($rel ? 1 : 0);
		} else {
			$check = M('chapter')->field('(select count(0) from history where pid = chapter.id and uid=\''.$_SESSION['uid'].'\')as done,((select count(0) from choice where pid = chapter.id)+(select count(0) from judge where pid = chapter.id)) as total')->where("`id` = '{$_GET['id']}'")->select();
			if ($check['done'] != $check['total']) alert('你还没有完成该部分练习，不能查看报告！');
			$history = M('history');
			$data = $history->where(array('uid'=>$_SESSION['uid'], 'pid'=>I('get.id')))->select();
			foreach ($data as $key => $value) {
				$value['type'] == 'choice' ? $cids[$value['qid']] = $value['answer'] : $jids[$value['qid']] = $value['answer'];
				$value['type'] == 'choice' ? $collect['c'.$value['qid']] = $value['collect'] : $collect['j'.$value['qid']] = $value['collect'];
			}
			$cdata = M('choice')->where(array('pid'=>I('get.id')))->select();
			$jdata = M('judge')->where(array('pid'=>I('get.id')))->select();
			$this->assign('cids', $cids);
			$this->assign('jids', $jids);
			$this->assign('collect', $collect);
			$this->assign('choice', $cdata);
			$this->assign('data', M('chapter')->find($_GET['id']));
			$this->assign('judge', $jdata);
			$this->assign('count', count($cdata));
			$this->assign('total', count($jdata)+count($cdata));
			$this->display();
		}
	}

	/* 收藏题目
	 * @time 2017-04-18
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function collection(){
		if ($_GET['id']) {
			$history = M('history');
			$data = $history->where(array('uid'=>$_SESSION['uid'], 'pid'=>I('get.id'), 'collect'=>'1'))->select();
			foreach ($data as $key => $value) {
				$qids[$value['type']][] = $value['qid'];
			}
			foreach ($qids as $key => $value) {
				$ques[$key] = M($key)->where(array(substr($key, 0, 1).'id'=>array('in', $value)))->select();
			}
			if (count($ques) == 0) {
				alert('您没有收藏题目！');
			} else {
				$this->assign('data', M('chapter')->find($_GET['id']));
				$this->assign('ques', $ques);
				$this->display('collection_detail');
			}
		} else {
			$history = M('history');
			$data = $history->field('pid id,(select `title` from chapter where id = history.pid) as title,count(qid) as done,((select count(0) from choice where pid = history.pid)+(select count(0) from judge where pid = history.pid)) as total,sum(collect) as collect,(select time from history a where a.pid=history.pid and uid=\''.$_SESSION['uid'].'\' order by `id` desc limit 1 ) as time')->where('collect != 0 and uid=\''.$_SESSION['uid'].'\'')->group('pid')->select();
			foreach ($data as $key => &$value) {
				$value['title'] = getChainNameById($value['id']);
			}
			$this->assign('data', $data);
			$this->display();
		}
	}

	/* 错题记录
	 * @time 2017-04-18
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function notebook(){
		if ($_GET['id']) {
			$history = M('history');
			$data = $history->where(array('uid'=>$_SESSION['uid'], 'pid'=>I('get.id'), 'status'=>'0'))->select();
			foreach ($data as $key => $value) {
				$qids[$value['type']][] = $value['qid'];
			}
			foreach ($qids as $key => $value) {
				$ques[$key] = M($key)->where(array(substr($key, 0, 1).'id'=>array('in', $value)))->select();
			}
			if (count($ques) == 0) {
				alert('您没有错题记录！');
			} else {
				$this->assign('data', M('chapter')->find($_GET['id']));
				$this->assign('ques', $ques);
				$this->display('notebook_detail');
			}
		} else {
			$history = M('history');
			$data = $history->field('pid id,(select `title` from chapter where id = history.pid) as title,count(qid) as done,((select count(0) from choice where pid = history.pid)+(select count(0) from judge where pid = history.pid)) as total,sum(status) as `status`,(select time from history a where a.pid=history.pid and uid=\''.$_SESSION['uid'].'\' order by `id` desc limit 1 ) as time')->where('uid=\''.$_SESSION['uid'].'\'')->group('pid')->select();
			foreach ($data as $key => &$value) {
				$value['title'] = getChainNameById($value['id']);
			}
			$this->assign('data', $data);
			$this->display('');
		}
	}

	/* 统一考试
	 * @time 2017-04-24
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function test(){
		$report = M('report');
		if (IS_POST) {
			$map = array('s'=>'subjective', 'c'=>'choice', 'j'=>'judge', 'f'=>'filling');
			$tid = $_POST['tid'];
			unset($_POST['tid']);
			$testinfo = M('test')->find($_GET['id']);
			$check = $report->where(array('uid'=>$_SESSION['uid'], 'tid'=>$tid))->find();
			if (!$check || in_array($check['status'], array(0, -1)) || time()-strtotime($check['start_time']) > $testinfo['time']*60) alert('access denied!');
			if (date('Y-m-d') < $testinfo['start']) alert('考试还未开始!');
			if (date('Y-m-d') > $testinfo['end']) alert('考试已经结束!');
			$qids = unserialize($testinfo['qids']);
			foreach ($_POST as $k => $v) {
				$v = is_array($v) ? implode(',', $v) : $v;
				$answer[$map[substr($k, 0, 1)]][substr($k, 1)] = $v;
				unset($_POST[$k]);
			}
			foreach ($qids as $k => $v) {
				if ($k == 'choice' || $k == 'judge') {
					$ques[$k] = M($k)->where(array(substr($k, 0, 1).'id'=>array('in', $v)))->select();
					foreach ($ques[$k] as $subk => $subv) {
						$statistic['count']['total']++;
						$statistic['count'][$subv['pid']]['total']++;
						$statistic['count'][$k]['total']++;
						if ($subv['right_ans'] == $answer[$k][$subv[substr($k, 0, 1).'id']]) {
							$statistic[$k]['right'][] = $subv[substr($k, 0, 1).'id'];
							$statistic['count']['right']++;
							$statistic['count'][$k]['right']++;
							$statistic['count'][$subv['pid']]['right']++;
							$statistic['score'] += 2;
						} else {
							$statistic['count']['wrong']++;
							$statistic['count'][$k]['wrong']++;
							$statistic['count'][$subv['pid']]['wrong']++;
							$statistic[$k]['wrong'][] = $subv[substr($k, 0, 1).'id'];
						}
					}
				}
			}
			$data['status'] = 0;
			$data['keguan'] = $statistic['score'];
			$data['end_time'] = date('Y-m-d H:i:s');
			$data['answer'] = serialize($answer);
			$data['statistic'] = serialize($statistic);
			$rel = $report->where(array('uid'=>$_SESSION['uid'], 'tid'=>$tid))->save($data);
			if ($rel) {
				$this->success('提交成功！', './test');
			} else {
				$this->error('提交失败！');
			}
		} else {
			if ($_GET['id']) {
				$test = M('test');
				$data = $test->find($_GET['id']);
				if (!$data) alert('access denied!');
				$check = $report->where(array('uid'=>$_SESSION['uid'], 'tid'=>I('get.id')))->find();
				if (!$check || in_array($check['status'], array(0, 2))) alert('access denied!');
				if (date('Y-m-d') < $data['start']) alert('考试还未开始!');
				if (date('Y-m-d') > $data['end']) alert('考试已经结束!');
				$ques = getPaperQuesList(unserialize($data['qids']));
				$update['status'] = $check['status'] == '-1' ? '1' : '2';
				$update['start_time'] = date('Y-m-d H:i:s');
				$report->where(array('uid'=>$_SESSION['uid'], 'tid'=>I('get.id')))->save($update);
				$this->assign('data', $data);
				$this->assign('ques', $ques);
				$this->assign('total', $total);
				$this->display('start_test');
			} else {
				$data = $report->where(array('report.uid'=>$_SESSION['uid']))->join('test on test.tid = report.tid')->select();
				$map = array('-1'=>'未参加考试', '0'=>'考试已完成', '1'=>'一次未提交(还可参加一次考试)', '2'=>'两次未提交(无法再次参加考试)');
				$this->assign('map', $map);
				$this->assign('data', $data);
				$this->display();
			}
		}
	}
}