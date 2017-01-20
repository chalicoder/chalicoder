<?php 
namespace common\models;

use yii\behaviors\TimestampBehavior;

class Module extends \yii\db\ActiveRecord{

	//是否启用模块
	const STATUS_OPEN = 1;
	const STATUS_CLOSE = 0;
	const STATUS_UNINSTALL = -1;

	//模块类型 核心或插件
	const TYPE_CORE = 1;
	const TYPE_PLUGIN = 2;

	public static function tableName(){
		return '{{%module}}';
	}

	public function rules(){
		return [
			[['id', 'name'], 'required'],
			[['status', 'type'], 'integer'],
			[['type'], 'in', 'range' => [1,2]],
			[['name'], 'string', 'max' => 50],
			[['class', 'bootstrap'], 'string', 'max' => 128],
			[['config'], 'string'],
			['status', 'default', 'value' => 1],
			[['id'], 'unique'],
		];
	}

	public function attributeLabels(){
		return [
            'id' => 'ID',
            'name' => '名称',
            'bootstrap' => '初始化的应用',
            'status' => '是否启用',
            'config' => '配置',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
	}

	public function behaviors(){
		return [
			TimestampBehavior::className()
		];
	}

	/**
	 * @param  string $type 模块类型
	 * @return array
	 */
	public static function findOpenModules($type = null){
		$query = static::find();
		return $query->where(['status' => self::STATUS_OPEN])->andFilterWhere(['type' => $type])->all();
	}

	public function getOpen(){
		return $this->status = self::STATUS_OPEN;
	}

	public function getInstall(){
		return $this->status != self::STATUS_UNINSTALL;
	}

	public static function getDb(){
		return \Yii::$app->db_admin;
	}
}