<?php
namespace app\index\controller;
 
use think\Controller;
 
class Regester extends Controller
{

  public function regester(){
    	return $this->fetch();
  }
  public function doRegester(){
    	$param = input('post.');
    	if(empty($param['user_name'])){
    		
    		$this->error('用户名不能为空');
    	}
    	
    	if(empty($param['user_pwd'])){
    		
    		$this->error('密码不能为空');
    	}
        if(empty($param['user_pwd_sure'])){
    		
    		$this->error('请输入确认密码');
    	}
        if($param['user_pwd_sure'] != $param['user_pwd']){
    		
    		$this->error('两次输入密码不一致');
    	}
    	
    	// 验证用户名
    	$has = db('users')->where('user_name', $param['user_name'])->find();
    	if(empty($has)){
    		$data = ['user_name' => $param['user_name'], 'user_pwd' => md5($param['user_pwd'])];
            db('users')->insert($data);
            $this->success('注册成功','index/index');
    	} 
        else{
          $this->error('该用户名已存在，请重新输入新的用户名');
        }
    
    	//$this->redirect(url('index/index'));
      
  }
 
}