<?php
namespace Common\Controller;
use Think\Controller;

class ApiController extends Controller {
	
	public function _empty(){
		header('HTTP/1.1 404 Not Found');
		echo file_get_contents('404.html');
		exit();
	}
	
	private function apiReturn($code, $message, $data = array()) {
		$return = array(
			'errCode' => $code,
			'errMsg' => $message,
		);
		if(!empty($data)) $return['data'] = $data;
		$this->ajaxReturn($return);
	}
	
	protected function apiSuccess($data) {
		$this->apiReturn(0, 'OK', $data);
	}
	
	protected function apiError($code, $message) {
		$this->apiReturn($code, $message);
	}
}