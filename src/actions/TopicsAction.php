<?php
namespace jakharbek\topics\actions;

use Yii;
use yii\base\Action;

class TopicsAction extends Action{

    public function run(){

        return $this->controller->render('@vendor/jakharbek/yii2-topics/src/views/topics');
    }

}