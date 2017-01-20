<?php 
namespace common\modules\user\controllers;

use common\modules\user\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * 后台入口控制器
 * @author jack
 * @version  1.0
 */
class AdminController extends Controller{

	public function behaviors(){
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
					'confirm' => ['post'],
					'block' => ['post'],
				],
			],
		];
	}

	/**
	 * 后台登陆
	 * @author  jack
	 * @access public
	 */
	public function actionLogin(){
		$this->layout = '@common/modules/user/views/admin/main-login.php';
		if(!\Yii::$app->user->isGuest){
			return $this->goHome();
		}

		$model = new LoginForm();
		if($model->load(Yii::$app->request->post()) && $model->loginAdmin()){
			return $this->goBack();
		}else{
			if(Yii::$app->request->isAjax){
				return $this->renderAjax('login', ['model' => $model]);
			}

			return $this->render('login', ['model' => $model]);
		}
	}
}
