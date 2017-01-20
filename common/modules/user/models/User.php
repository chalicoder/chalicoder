<?php 
namespace common\modules\user\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements IdentityInterface{

	const STATUS_DELETED = 0;	//禁用
    const STATUS_ACTIVE = 10;	//正常

	public function tableName(){
		reutrn '{{%user}}';
	}

	public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function scenarios(){
    	$scenarios = parent::scenarios();
    	return array_merge($scenarios, [
            'register' => ['username', 'email', 'password'],
            'connect'  => ['username', 'email'],
            'create'   => ['username', 'email', 'password'],
            'update'   => ['username', 'email', 'password'],
            'settings' => ['username', 'email', 'password'],
            'resetPassword' => ['password']
        ]);
    }

    public function rules(){
    	return [
    		['username', 'required', 'on' => 'create'],
            ['username', 'unique', 'on' => 'create'],
            ['email', 'required', 'on' => 'create'],
            ['email', 'unique', 'on' => 'create'],
            ['password', 'required', 'on' => ['register']],
    	];
    }

    public function attributeLabels(){
    	 return [
            'username' => '用户名',
            'password' => '密码',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '注册时间',
            'login_at' => '最后登录时间'
        ];
    }

    /**
     * 获取用户状态
     * @author  jack
     * @access public
     * @return array
     */
    public static function getStatusList(){
    	return [
    		self::STATUS_DELETED => '禁用',
    		self::STATUS_ACTIVE => '正常'
    	];
    }

    /**
     * get用户信息
     * @param  $login 用户名或者邮箱
     * @return array
     */
    public static function findByUsernameOrEmail($login){
    	return static::find()->where(['or', 'username = "' . $login . '"', 'email = "' . $login . '"'])->andWhere(['blocked_at' => null])->one();
    }
} 