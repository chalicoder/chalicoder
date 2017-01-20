<?php 
namespace common\modules\user\models;

use Yii;
use yii\base\Model;

/**
 * 登陆model
 * @author  jack
 * @version 1.0
 */
class LoginForm extends Model{
	public $username;
	public $password;
	public $rememberMe = true;
	public $verifyCode;
	private $_user;

	public function rules(){
		return [
			[['username', 'password'], 'required'],
			['rememberMe', 'bollean'],
			['password', 'validatePassword'],
		];
	}

	public function attributeLabels(){
		return [
			'username' => '用户名/Email',
			'password' => '密码',
			'rememberMe' => '记住我',
		];
	}

	/**
	 * 密码和账号验证
	 * @param  $attribute 验证属性
	 * @param  $params   其他参数
	 * @return nil
	 */
	public function validatePassword($attribute, $params){
		if(!$this->hasErrors()){
			$user -> getUser();
			if(!$user || !$user->validatePassword($this->password)){
				$this->addError($attribute, '账号或密码错误');
			}
		}
	}

	/**
	 * 登陆用户
	 * @author jack
	 * @access public
	 * @return array
	 */
	public function getUser(){
		if($this->_user === null){
			$this->_user = User::findByUsernameOrEmail($this->username);
		}
		return $this->_user;
	}
}