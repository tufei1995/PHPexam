<?php
function post($url, $data = '', $header = array(), $return_header = false) {
	$ip = "27.17.".rand(0,255).'.'.rand(0,255);
	$header = $header ? $header : array(
		'User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; Redmi Note 3 Build/LMY47V) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile MQQBrowser/6.8 TBS/036872 Safari/537.36 MicroMessenger/6.3.32.960 NetType/WIFI Language/zh_CN sfpy',
		'X-Forwarded-For: '. $ip,
		'X_Forwarded: '. $ip,
		'Forwarded_For: '. $ip,
		'Client_Ip: '. $ip,
		'Via: '. $ip
	);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, 0);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_HEADER, $return_header);
	curl_setopt($curl, CURLOPT_NOBODY, $return_header);
	$content = curl_exec($curl);
	curl_close($curl);
	
	return $content;
}

function alert($msg, $url) {
    $url = $url ? 'location.href="' . $url . '";' : 'history.back(-1);';
    echo "<html><head><meta charset='utf-8'><script>alert('{$msg}');" . $url . "</script></head></html>";
    exit();
}
function push_to_baidu($urls,$module){
	if(empty($urls)) exit('no sitemap data to push today');
	$api = C('BD_PUSH.'.$module);
	$ch = curl_init();
	$options =  array(
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => implode("\n", $urls),
		CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
	);
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	$data = json_decode($result, true);
	
	return $result;
}
function get($module, $api, $request) {
	$server = C("{$module}_SERVER");
	$url = C("{$module}_API_URL.$api");
	$content = file_get_contents($server . $url . '?' . $request);
	
	return $content;
}
function isQQ() {
	$is_qq = is_numeric(strpos($_SERVER['HTTP_USER_AGENT'], 'QQ'));
	if ($is_qq) 
	return true;
	else 
	return false;
}
function isMobile() {
	$is_ios = is_numeric(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone'));
	$is_android = is_numeric(strpos($_SERVER['HTTP_USER_AGENT'], 'Android'));
	if ($is_ios || $is_android) 
	return true;
	else 
	return false;
}
function isSpider(){
	return preg_match('/p(.?)der/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/g(.?)le/i', $_SERVER['HTTP_USER_AGENT']);
}
function tranArray(&$value, &$key) {
	$value = $key == 'X-Oauth-token' ? $key . ': ' . $value : $key . ': ' . $value;
}
function BaiduTranslate($content, $to = 'en') {
	$appid = '20151208000007492';
	$appsercet = 'O4bjNzWzHh6vyFQ0XOy_';
	$salt = rand();
	$sign = md5($appid . $content . $salt . $appsercet);
	$content = file_get_contents("http://api.fanyi.baidu.com/api/trans/vip/translate?q=$content&from=zh&to=$to&appid=$appid&salt=$salt&sign=$sign");
	$content = json_decode($content, true);
	
	return $content['trans_result'][0]['dst'];
}
function pagination($total) {
	$start = I('path.1') ? I('path.1') : 1;
	$prv = $start - 1;
	$next = $start + 1;
	$url = I('path.0') ? I('path.0') : 'page';
	$total_page = ($total % 20) ? (int)($total / 20) + 1 : $total / 20;
	if ($total_page == '1') $page = '
					  <li class="am-disabled"><a href="#">&laquo;</a></li>
					  <li class="am-disabled"><a href="#">1</a></li>
					  <li class="am-disabled"><a href="/' . I('path.0') . '/' . $total_page . '.html">&raquo;</a></li>';
	else if ($start == '1') $page = '
					  <li class="am-disabled"><a href="#">&laquo;</a></li>
					  <li class="am-disabled"><a href="#">1</a></li>
					  <li><a href="/' . $url . '/2.html">2</a></li>
					  <li><a href="/' . $url . '/' . $total_page . '.html">&raquo;</a></li>';
	else if ($start == $total_page) $page = '
				  <li><a href="/' . $url . '/1.html">&laquo;</a></li>
				  <li><a href="/' . $url . '/' . $prv . '.html">' . $prv . '</a></li>
				  <li class="am-disabled"><a href="#">' . $start . '</a></li>
				  <li class="am-disabled"><a href="/' . I('path.0') . '/' . $total_page . '.html">&raquo;</a></li>';
	else if ($start == '2') $page = '
				  <li><a href="/' . $url . '/' . $prv . '.html">&laquo;</a></li>
				  <li><a href="/' . $url . '/' . $prv . '.html">' . $prv . '</a></li>
				  <li class="am-disabled"><a href="#">' . $start . '</a></li>
				  <li><a href="/' . $url . '/' . $next . '.html">' . $next . '</a></li>
				  <li><a href="/' . $url . '/' . $total_page . '.html">&raquo;</a></li>';
	else if ($start > '2') $page = '
				  <li><a href="/' . $url . '/1.html">&laquo;</a></li>
				  <li><a href="/' . $url . '/' . $prv . '.html">' . $prv . '</a></li>
				  <li class="am-disabled"><a href="#">' . $start . '</a></li>
				  <li><a href="/' . $url . '/' . $next . '.html">' . $next . '</a></li>
				  <li><a href="/' . $url . '/' . $total_page . '.html">&raquo;</a></li>';
	
	return '
				<ul class="am-pagination am-pagination-centered">
				' . $total . '记录/' . $total_page . '页' . $page . '
				</ul>
';
}
function update() {
	$type = I('param.type');
	$key = I('param.key');
	$kvalue = I('param.kvalue');
	$param = I('param.param');
	$value = I('param.value');
	$rel = M($type)->execute("UPDATE `$type` SET $param='$value' WHERE $key='$kvalue'");
	if ($rel) $msg = '操作成功';
	else $msg = '操作失败';
	apiMsg($msg);
}
function whutjwc($sno, $password) {
	$curl = curl_init();
	$header = array(
		'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
		'Connection:keep-alive',
		'Cookie:',
		'Host:sso.jwc.whut.edu.cn',
		'Referer:http://sso.jwc.whut.edu.cn/Certification/toIndex.do',
		'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36'
	);
	$request = 'systemId=&xmlmsg=&userName=' . $sno . '&password=' . $password . '&type=xs&imageField.x=6&imageField.y=10';
	curl_setopt($curl, CURLOPT_URL, 'http://sso.jwc.whut.edu.cn/Certification/login.do');
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$content = curl_exec($curl);
	curl_close($curl);
	$content = str_replace('&nbsp;', '', $content);
	$preg = "/<span class=\"font2\">(.*)<\/span>/i";
	$preg1 = "/<td align=\"center\">(.*)<\/td>/i";
	$preg2 = "/<td>(.*)<\/td>/i";
	$preg3 = "/<td>([\s\S]*)<\/td>/i";
	preg_match_all($preg, $content, $arr);
	preg_match_all($preg1, $content, $p);
	preg_match_all($preg2, $content, $v);
	if (!$arr[1][0]) {
		return false;
	} else {
		$data['status'] = 'ok';
		$data['info'] = array(
			'name' => $arr[1][0],
			'phone' => $arr[1][1]
		);
		for ($i = 0; $i < count($v[1]); $i+= 6) {
			$data['table'][] = array(
				'name' => $v[1][$i],
				'teacher' => $v[1][$i + 1],
				'room' => $v[1][$i + 2],
				'type' => $v[1][$i + 3],
				'point' => $p[1][$i / 6]
			);
			$data['class'][] = $v[1][$i];
			$data['teacher'][] = $v[1][$i + 1];
		}
		return $data;
	}
}
function create_sitemap_xml($urls, $name){
	$xml = '<?xml version="1.0" encoding="utf-8"?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<!--sitemap updated at '.date('Y-m-d H:i:s').' -->';
	foreach($urls as $v){
		$xml.='
		<url>
		   <loc>'.$v.'</loc>
		   <lastmod>'.date('Y-m-d').'</lastmod>
		</url>';
	}
	$xml.='
	   </urlset>';
	if(file_put_contents('Public/'.$name.'.xml', $xml)) return true;
	else return false;
}
function checkField($fields,$type='param',$fliter='htmlentities') {
	if(empty($fields)) {
		$data = array_keys($_POST);
	} else {
		$data = explode(',', str_replace(' ', '', $fields));
	}
	foreach($data as $tmp) {
		$v[$tmp] = addslashes(I($type.'.'.$tmp,'',$fliter));
	}
	return $v;
}

function sendmail($api,$data = array()){
	$data['api_user'] = C('SC_API_USER');
	$data['api_key'] = C('SC_API_KEY');
	$url = C('SC_SERVER').$api.C('DEFAULT_RETURN');
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL,$url);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$content = curl_exec($curl);
	curl_close($curl);
	return $content;
}

function tuling($text){
	$content = file_get_contents('http://www.tuling123.com/openapi/api?key=1a43f9c6098cb01c9a0a1e8121380851&info=' . $text);
	$content = json_decode($content, true);
	return $content['text'];
}

function checkLogin(){
	if(empty($_SESSION['username'])) {
		header("Location:/login");
		exit();
	}
}