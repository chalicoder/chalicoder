<?php
namespace common\components;

use common\models\Module;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class LoadModule extends Component implements BootstrapInterface{

	public function bootstrap($app){
		$models = Module::findOpenModules(Module::TYPE_CORE);
		$bootstrapType = $app->id;	//应用ID
		foreach ($models as $model) {
			$this->setModule($model->id, ['class' => $model->class]);
			$bootstraps = explode("|", $model->bootstrap);
			if (in_array($bootstrapType, $bootstraps)) {
                $module = \Yii::$app->getModule($model->id);
                if ($module instanceof BootstrapInterface) {
                    $module->bootstrap($app);
                }
            }
		}
	}

	public function setModule($id, $config){
		$definitions = \Yii::$app->getModules();
		Yii::$app->setModule($id, ArrayHelper::merge($config, array_key_exists($id, $definitions) ? $definitions[$id] : []));
	}

}