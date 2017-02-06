<?php
namespace rbac\components;

use Yii;
use yii\base\Module;
use yii\di\Instance;
use yii\web\User;


class AccessControl extends \yii\base\ActionFilter{

    private $_user = 'user';
    public $allowActions = [];

    /**
     * get user
     * @author jack
     * @return User
     */
    public function getUser(){
        if(!$this->_user instanceof User){
            $this->_user = Instance::ensure($this->_user, User::className());
        }
        return $this->_user;
    }

    /**
     * set user
     * @author jack
     * @return void
     */
    public function setUser($user){
        $this->_user = $user;
    }

    public function beforeAction($action){
        $actionId = $action->getUniqueId();
        $user = $this->getUser();
        if ($user->can('/'.$actionId)) {
            return true;
        }
        $obj = $action->controller;
        do {
            if ($user->can('/'.ltrim($obj->getUniqueId().'/*', '/'))) {
                return true;
            }
            $obj = $obj->module;
        } while ($obj !== null);
        $this->denyAccess($user);
    }

    /**
     * 拒绝用户访问
     * 用户没登录情况下重定向 如果登陆返回403
     * @author jack
     */
    protected function denyAccess($user){
        if($user->getIsGuest()){
            $user->loginRequired();
        }else{
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    protected function isActive($action)
    {
        $uniqueId = $action->getUniqueId();
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return false;
        }

        $user = $this->getUser();
        if ($user->getIsGuest() && is_array($user->loginUrl) && isset($user->loginUrl[0]) && $uniqueId === trim($user->loginUrl[0], '/')) {
            return false;
        }

        if ($this->owner instanceof Module) {
            // convert action uniqueId into an ID relative to the module
            $mid = $this->owner->getUniqueId();
            $id = $uniqueId;
            if ($mid !== '' && strpos($id, $mid.'/') === 0) {
                $id = substr($id, strlen($mid) + 1);
            }
        } else {
            $id = $action->id;
        }

        foreach ($this->allowActions as $route) {
            if (substr($route, -1) === '*') {
                $route = rtrim($route, '*');
                if ($route === '' || strpos($id, $route) === 0) {
                    return false;
                }
            } else {
                if ($id === $route) {
                    return false;
                }
            }
        }

        if ($action->controller->hasMethod('allowAction') && in_array($action->id, $action->controller->allowAction())) {
            return false;
        }

        return true;
    }
}