<?php
namespace Common\Controller;
use Think\Controller;

class EmptyController extends Controller {
	public function index() {
		$this->apiError('404', 'API_NOT_FOUND');
	}
	public function _empty() {
		$this->apiError('404', 'API_NOT_FOUND');
	}
}
?>