<?php
$vote = new Vote();
$vote->vote();

Class Vote {

	var $getCookieUrl = 'http://218.199.196.196/2014culture/Services/Users/UserLogin.ashx';
	var $registerUrl = 'http://218.199.196.196/2016culture/Services/Users/UserReg.ashx';
	var $loginUrl = 'http://218.199.196.196/2016culture/Services/Users/UserLogin.ashx';
	var $commentUrl = 'http://218.199.196.196/2016culture/Services/Scores/ScoreAdd.ashx';
	var $cookie = '';

	public function vote(){
		$rel = $this->request($this->getCookieUrl, '', true);
		preg_match_all("/ASP.NET_SessionId=(.*); pa/i", $rel, $session);
		$this->cookie = $session[1][0];
		$data = $this->register();
		$this->login($data);
		$this->comment();
	}

	private function register(){
		$data['cardid'] = $this->createID();
		$data['realname'] = $this->createName();
		$data['email'] = rand(111111111,2000000000).'@qq.com';
		$data['name'] = $this->username($data['email'], $data['realname']);
		$data['password0'] = rand(111111111,2000000000);
		$data['password1'] = $data['password0'];
		$rel = $this->request($this->registerUrl, $data);
		$rel = json_decode($rel, true);
		if ($rel['code'] == 1) {
			return $data;
		}
		else {
			exit('注册失败');
		}
	}

	private function login($data){
		$login['username'] = $data['name'];
		$login['userpassword'] = $data['password0'];
		$rel = $this->request($this->loginUrl, $login);
		$rel = json_decode($rel, true);
		if ($rel['code'] != 1) exit('登陆失败');
	}

	private function comment(){
		$content = array(
			'这个APP非常好用，充值校园卡，看课表，查自习室等等功能非常好，别的学校同学非常羡慕，问我他们能不能使用这个APP。',
			'挺不错的~在我用了这么多大学软件里面，算是最实用的吧。',
			'从每一个方便了我们的生活，学习，！！！！！！',
			'学生团队自主研发的一款可以让学生老师通过一款软件就使用许多功能，校历，课表，校车，小黄车……听取同学的意见不断研发新功能。',
			'特别实用的，课表，校园卡校车自习室，没有的话生活会缺少些东西的',
			'真心服务我们学生软件，几乎所有校园生活实用功能都有，而且还在不断推出新的功能，很棒！',
			'很好呀，能查看自己的课表，还可以查校历成绩，更能够查看消费记录充饭卡，很棒棒！',
			'功能齐全，使用方便，很好用。对我们的帮助很大！',
			'包含了几乎关于学生的所有，课程表、自习室、校园卡、缴费、新闻、成绩、图书馆……十分方便！广受大家热爱。',
			'好评，为学生的生活提供极大的便利。并且真正为学生考虑，一直在完善。',
			'app好棒啊。与学校的联系也特别好。活动也十分丰富。',
			'掌上理工大基本涵盖了理工学者所需的各项功能',
			'一个app里包含了在学校可能涉及的所有方面，课程表、自习室、校园卡、缴费、新闻、成绩、图书馆……方便得不像话！而且ios版颜值很高',
			'经常用，很实用，提供了方便，值得信赖，好好发展。',
			'我们武理的同学人手一个，什么都能做，很有用。',
			'我们理工的token真的不是浪得虚名，可以说大家都在用理工大的软件，真的超级实用~',
			'好用功能多嗨呀，好评哦，超级喜欢，还会推送有意思的活动',
			'一个app里包含了在学校可能涉及的所有方面，课程表、自习室、校园卡、缴费、新闻、成绩、图书馆……方便得不像话！而且i	os版颜值很高',
			'一进校就开始使用的APP，设计很贴心，并且功能在不断完善，偶尔有节日小惊喜，很棒了！',
			'很多实用功能，学生团队自主开发，贴合实际',
			'哈哈哈，理工学子必备软件。在理工，只要QQ，微信，支付宝和掌上理工大就可畅游。',
			'说句实在话，因为掌理我的大学省下来很多时间，方便了很多。',
			'非常棒的app好评好评好评好评好评好评好评！',
			'十足的科技感和时代感，用了很多年，非常方便，是服务师生的好伙伴。',
			'从进校起，学姐就介绍我们掌上理工大这款app，专门针对我们武汉理工大学师生，功能十分齐全，使用十分便捷，还会有惊喜小活动，真的非常棒，支持！',
			'非常不错的App，好评好评好评好评好评~',
			'十足的科技感和时代感，用了很多年，非常方便，是服务师生的好伙伴。');
		$data = 'applyid=185&score=5&comment='.urlencode($content[rand(0, count($content)-1)]);
		echo $rel = $this->request($this->commentUrl, $data , $header);
		$rel = json_decode($rel, true);
		if ($rel['code'] != 1) exit('投票失败');
		//echo '投票成功';

	}

	private function username($email, $realname){
		$case = rand(1,7);
		switch ($case) {
			case '1': $username = rand(2222222,9999999); break;
			case '2': $username = $realname.'@whut'; break;
			case '3': $username = $realname.'1314'; break;
			case '4': $username = $email; break;
			case '5': $username = $realname.rand(1991,1998); break;
			case '6': $username = substr(md5(rand(2222,9999)), rand(0,20), rand(5,8)); break;
			case '7': $username = $realname; break;
		}
		return $username;
	}

	private function createID(){
		$province = array('11','12','13','14','15','21','22','23','31','32','33','34','35','36','37','41','42','43','44','45','46','50','51','52','53','54','61','62','63','64','65');
		$shi = array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');
		return $province[rand(0,30)].$shi[rand(0,20)].$shi[rand(0,20)].'199'.rand(0,8).$shi[rand(0,11)].$shi[rand(0,27)].rand(1000,9999);
	}

	private function createName(){
		$first = array(
			'赵','钱','孙','李','周','吴','郑','王',
			'冯','陈','楮','卫','蒋','沈','韩','杨',
			'朱','秦','尤','许','何','吕','施','张',
			'孔','曹','严','华','金','魏','陶','姜',
			'戚','谢','邹','喻','柏','水','窦','章',
			'云','苏','潘','葛','奚','范','彭','郎',
			'鲁','韦','昌','马','苗','凤','花','方',
			'俞','任','袁','柳','酆','鲍','史','唐',
			'费','廉','岑','薛','雷','贺','倪','汤',
			'滕','殷','罗','毕','郝','邬','安','常',
			'乐','于','时','傅','皮','卞','齐','康',
			'伍','余','元','卜','顾','孟','平','黄',
			'和','穆','萧','尹','姚','邵','湛','汪',
			'祁','毛','禹','狄','米','贝','明','臧',
			'计','伏','成','戴','谈','宋','茅','庞',
			'熊','纪','舒','屈','项','祝','董','梁',
			'杜','阮','蓝','闽','席','季','麻','强',
			'贾','路','娄','危','江','童','颜','郭',
			'梅','盛','林','刁','锺','徐','丘','骆',
			'高','夏','蔡','田','樊','胡','凌','霍',
			'虞','万','支','柯','昝','管','卢','莫',
			'经','房','裘','缪','干','解','应','宗',
			'丁','宣','贲','邓','郁','单','杭','洪',
			'包','诸','左','石','崔','吉','钮','龚'
		);
		$last = array(
			'伟','飞','敏','冰','彦磊','嘉伟','诚','振','震',
			'星','不凡','敏','冰','婷婷','家伟','城','力','海','河','舟'
		);
		return $first[rand(0, count($first)-1)].$last[rand(0, count($last)-1)];
	}

	private function request($url, $data = '', $return_header = false) {
		if (is_array($data)) {
			foreach ($data as $k => $v) {
				$test[] = $k.'='.urlencode($v);
			}
			$data = implode('&', $test);
		}
		$header = array(
			'User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; Redmi Note 3 Build/LMY47V) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile MQQBrowser/6.8 TBS/036872 Safari/537.36 MicroMessenger/6.3.32.960 NetType/WIFI Language/zh_CN sfpy',
			'Cookie: ASP.NET_SessionId='.$this->cookie,
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_HEADER, $return_header);
		curl_setopt($curl, CURLOPT_NOBODY, $return_header);
		$content = curl_exec($curl);
		curl_close($curl);
		
		return $content;
	}
}