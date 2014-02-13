<?php
//留言板首页控制器
class IndexAction extends Action {
    public function index(){
		$board = M('board')->select();
		$this->assign('board',$board)->display();
    }
    
	public function handle(){
		if (!IS_POST) _404('页面不存在！', U('Index'));
    	$data = array(
    		'username' => I('username'),
    		'content' => I('content'),
    	);
    	
    	if (M('board')->data($data)->add()) {
			$this->success('发布成功', U('index'));
    	} else {
			$this->error('发布失败，请重试');
    	}
    }
}