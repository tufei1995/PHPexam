<?php
	
	function accessDenied() {
		if (IS_AJAX) {
			echo '{"status":0,"msg":"你没有权限进行这个操作"}';
			exit();
		} else {
			alert('你没有权限进行这个操作');
		}
	}
	function getUserName($userid){
		if(!S('userid_'.$userid)){
			$info = M('user')->find($userid);
			S('userid_'.$userid, $info['name']);
		}
		return S('userid_'.$userid);
	}
	function getGroupName($gid){
		if(!S('group_name_'.$gid)){
			$info = M('group')->find($gid);
			S('group_name_'.$gid, $info['name']);
		}
		return S('group_name_'.$gid);
	}
	function send_message($uid, $msg) {
		if (is_array($uid)) {
			$data = array();
			foreach ($uid as $k => $v) {
				$data[] = array('uid' => $v, 'content' => $msg);
			}
			return $rel = M('message')->addAll($data);
		} else {
			return $rel = M('message')->add(array('uid' => $uid, 'content' => $msg));
		}
	}
	function getSectionInfo($id = 1) {
		$data = M('chapter')->where(array('courseid' => $id))->select();
		$keymap = array_column($data, 'pid', 'id');
		$title = array_column($data, 'title', 'id');
		$str = '';
		foreach ($keymap as $k => $v) {
			if ($v != '0') {
				$pid[$v][] = $k;
			}
		}
		foreach ($pid as $k => $v) {
			$str .= '<optgroup label="' . $title[$k] . '">';
			foreach ($v as $subv) {
				$str .= '<option value="' . $subv . '">' . $title[$subv] . '</option>';
			}
			$str .= '</optgroup>';
		}

		return $str;
	}
	function getChapterInfo($id = 1) {
		$data = M('chapter')->where(array('courseid' => $id, 'pid'=>0))->select();
		$str = '';
		foreach ($data as $k => $v) {
			$str .= '<option value="' . $v['id'] . '">' . $v['title'] . '</option>';
		}

		return $str;
	}
	function getChainNameById($id) {
		if (S('ChainName_' . $id)) {
			return S('ChainName_' . $id);
		} else {
			$name = M('chapter')->field('*,(select title from chapter a where a.id=chapter.pid) as ptitle,(select title from course where id = chapter.courseid) as course')->find($id);
			S('ChainName_' . $id, $name['course'].' - '.$name['ptitle'].' - '.$name['title']);
			return S('ChainName_' . $id);
		}
	}
	function getChapterAndSectionNameById($id) {
		if (S('ChapterSectionName_' . $id)) {
			return S('ChapterSectionName_' . $id);
		} else {
			$name = M('chapter')->field('*,(select title from chapter a where a.id=chapter.pid) as ptitle')->find($id);
			S('ChapterSectionName_' . $id, $name['ptitle'].' - '.$name['title']);
			return S('ChapterSectionName_' . $id);
		}
	}
	function getCourseNameById($id) {
		if (S('CourseName_' . $id)) {
			return S('CourseName_' . $id);
		} else {
			$name = M('course')->find($id);
			S('CourseName_' . $id, $name['title']);
			return S('CourseName_' . $id);
		}
	}
	function getAccessArray($data) {
		foreach ($data as $k => $v) {
			if ($v['pid'] == '0') {
				$lists[$v['aid']] = $v;
			} else {
				$lists[$v['pid']]['child'][] = $v;
				$lists[$v['pid']]['count']++;
			}
		}
		foreach ($lists as &$v) {
			if ($v['child']) {
				$v['struct'] = $v['title'];
				$i = $v['count'];
				foreach ($v['child'] as &$subv) {
					$pre = $i == '1' ? '&nbsp;&nbsp;&nbsp;└&nbsp;' : '&nbsp;&nbsp;&nbsp;├&nbsp;';
					$subv['struct'] = $pre.$subv['title'];
					$i--;
				}
			}
		}
		return $lists;
	}
	function getTreeList($data, $key = 'id') {
		foreach ($data as $v) {
			if ($v['pid'] == '0') {
				$newArray[$v[$key]] = $v;
			} else {
				$newArray[$v['pid']]['child'][] = $v;
				$newArray[$v['pid']]['count']++;
			}
		}
		foreach ($newArray as $v) {
			if (!$v['child']) {
				$v['struct'] = $v['title'];
				$list[] = $v;
			} else {
				$child = $v['child'];
				$i = $v['count'];
				$v['struct'] = $v['title'];
				unset($v['child'], $v['count']);
				$list[] = $v;
				foreach ($child as &$subv) {
					$pre = $i == '1' ? '&nbsp;&nbsp;&nbsp;└&nbsp;' : '&nbsp;&nbsp;&nbsp;├&nbsp;';
					$subv['struct'] = $pre.$subv['title'];
					$list[] = $subv;
					$i--;
				}
			}
		}
		return $list;
	}
	function getAccessList($data) {
		$data = getAccessArray($data);
		foreach ($data as $v) {
			if (!$v['child']) {
				$lists[] = $v;
			} else {
				$child = $v['child'];
				unset($v['child'], $v['count']);
				$lists[] = $v;
				foreach ($child as $subv) {
					$lists[] = $subv;
				}
			}
		}
		return $lists;
	}
	function setAccess($access) {
		$data = M('access')->where(array('aid'=>array('in', $access)))->select();
		$key = array_column($data, 'key', 'aid');
		foreach ($data as $k => $v) {
			if ($v['pid'] == '0') {
				$lists[$v['key']] = array();
			} else {
				$lists[$key[$v['pid']]][] = $v['key'];
			}
		}
		return $lists;
	}
	function getAccessInfo($aid){
		if(!S('access_info_'.$aid)){
			$info = M('access')->find($aid);
			S('access_info_'.$aid, $info);
		}
		return S('access_info_'.$aid);
	}
	function createPaper($qids, $range){
		foreach ($qids as $k => $v) {
			foreach ($v as $subk => $subv) {
				$subv ? $ques[$k][$subk] = M($k)->where(array('pid'=>array('in', $range), 'difficulty'=>$subk))->limit($subv)->order('rand()')->select() : '';
			}
		}
		$quesList = array();
		foreach ($ques as $k => $v) {
			if ($v) {
				foreach ($v as $subk => $subv) {
					if ($subv) {
						foreach ($subv as $ssv) {
							$qid[$k][] = $ssv[substr($k, 0, 1).'id'];
							$quesList[$k][] = $ssv;
						}
					}
				}
			}
		}
		return $qid;
	}
	function getPaperQuesList($qids){
		foreach ($qids as $k => $v) {
			$v ? $ques[$k] = M($k)->where(array(substr($k, 0, 1).'id'=>array('in', $v)))->order('rand()')->select() : '';
		}
		return $ques;
	}
	function getMenu($access){
		$data = M('access')->where(array('aid'=>array('in', $access), 'is_show'=>1))->select();
		$title = array_column($data, 'title', 'aid');
		$icon = array_column($data, 'icon', 'aid');
		$key = array_column($data, 'key', 'aid');
		foreach ($data as $k => $v) {
			if ($v['pid'] == '0') {
				$lists[$v['aid']] = array();
			} else {
				$lists[$v['pid']][] = $v['aid'];
			}
		}
		foreach($lists as $k=>$v){
			if ($v && is_array($v)){
				$str .=	'<li class="admin-parent">';
				$str .=	'<a class="am-cf am-collapsed" data-am-collapse="{target: \'#collapse-'.$key[$k].'\'}"><span class="am-icon-'.$icon[$k].'"></span> '.$title[$k].' <span class="am-icon-angle-right am-fr am-margin-right"></span></a>';
				$str .=	'<ul class="am-list am-collapse admin-sidebar-sub" id="collapse-'.$key[$k].'">';
				foreach ($v as $subv) {
					$str .= '<li><a href="../'.$key[$k].'/'.$key[$subv].'" class="am-cf"><span class="am-icon-'.$icon[$subv].'"></span> '.$title[$subv].'</a></li>';
				}
				$str .=	'</ul>';
				$str .=	'</li>';
			}
		}
		return $str;
	}
