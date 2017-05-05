<?php
namespace Test\Controller;

class QuestionController extends CommonController {

	/* 选择题管理
	 * @time 2017-04-11
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function choice(){
		if (IS_AJAX) {
			$choice = M('choice');
			if ($_POST['id']){
				$this->result(1, '', $choice->field('*,(select courseid from chapter where id=choice.pid) as course')->find($_POST['id']));
			}
			$start = I('param.page');
			$field = '*,(select title from chapter where id=choice.pid) as pid';
			if (isset($_POST['keyword'])) {
				is_numeric($_POST['keyword']) ? $where['pid'] = $_POST['keyword'] : $where['title'] = array('like', '%' . I('param.keyword') . '%');
			}
			$this->result(1, '', $choice->field($field)->where($where)->order('cid desc')->page($start, '20')->select() , $choice->where($where)->count());
		} else {
			$this->assign('course', M('course')->select());
			$this->display();
		}
	}

	/* 判断题管理
	 * @time 2017-04-11
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function judge(){
		if (IS_AJAX) {
			$judge = M('judge');
			if ($_POST['id']){
				$this->result(1, '', $judge->field('*,(select courseid from chapter where id=judge.pid) as course')->find($_POST['id']));
			}
			$start = I('param.page');
			$field = '*,(select title from chapter where id=judge.pid) as pid';
			if (isset($_POST['keyword'])) {
				is_numeric($_POST['keyword']) ? $where['pid'] = $_POST['keyword'] : $where['title'] = array('like', '%' . I('param.keyword') . '%');
			}
			$this->result(1, '', $judge->field($field)->where($where)->order('jid desc')->page($start, '20')->select() , $judge->where($where)->count());
		} else {
			$this->assign('course', M('course')->select());
			$this->display();
		}
	}

	/* 主观题管理
	 * @time 2017-04-11
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function subjective(){
		if (IS_AJAX) {
			$subjective = M('subjective');
			if ($_POST['id']){
				$this->result(1, '', $subjective->field('*,(select courseid from chapter where id=subjective.pid) as course')->find($_POST['id']));
			}
			$start = I('param.page');
			$field = '*,(select title from chapter where id=subjective.pid) as pid';
			if (isset($_POST['keyword'])) {
				is_numeric($_POST['keyword']) ? $where['pid'] = $_POST['keyword'] : $where['title'] = array('like', '%' . I('param.keyword') . '%');
			}
			$this->result(1, '', $subjective->field($field)->where($where)->order('sid desc')->page($start, '20')->select() , $subjective->where($where)->count());
		} else {
			$this->assign('course', M('course')->select());
			$this->display();
		}
	}

	/* 填空题管理
	 * @time 2017-04-24
	 * @author	随风飘扬  <614128926@qq.com>*/
	public function filling(){
		if (IS_AJAX) {
			$filling = M('filling');
			if ($_POST['id']){
				$this->result(1, '', $filling->field('*,(select courseid from chapter where id=filling.pid) as course')->find($_POST['id']));
			}
			$start = I('param.page');
			$field = '*,(select title from chapter where id=filling.pid) as pid';
			if (isset($_POST['keyword'])) {
				is_numeric($_POST['keyword']) ? $where['pid'] = $_POST['keyword'] : $where['title'] = array('like', '%' . I('param.keyword') . '%');
			}
			$this->result(1, '', $filling->field($field)->where($where)->order('fid desc')->page($start, '20')->select() , $filling->where($where)->count());
		} else {
			$this->assign('course', M('course')->select());
			$this->display();
		}
	}
}