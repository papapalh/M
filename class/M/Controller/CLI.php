<?php
namespace M\Controller;

abstract class CLI {

	// 方法
	public $action;
	// 参数
  public $params;

  // 获取路由信息,调用方法,赋予参数
  public function execute() {
    // 获取参数
  	$action = 'action' . $this->action;
    // 获取参数
    $params = (array) $this->params;

  	// 判断是否有这个方法
  	if($action && method_exists($this, $action)) {
  		$this->action = $action;
  	}
  	// 如这个方法为空,调用Index方法 
  	elseif(method_exists($this, '__index')) {
  		$action = '__index';
  	} 
  	// 如Index也没有,则报404
  	else {
  		die('调用方法不存在--');
  	}

  	$response = call_user_func_array(array($this, $action), $params);

  	return $response;
  }
}
