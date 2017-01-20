<?php 
namespace rbac\components;

use Yii;


class AccessControl extends \yii\base\ActionFilter{

	private $_user = 'user';
	public $allowActions = [];
}