<?php
namespace jakharbek\topics\widgets;

use Yii;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use jakharbek\topics\models\Topics;
use jakharbek\langs\components\Lang;
use yii\web\JsExpression;

/**
 * Class TopicsWidget
 * @package jakharbek\topics\widgets
 * @author jakahr javhar_work@mail.ru
 * Вывод темы в выбранный записи в сингле (single)
 * @example  @file update.php folder views
 * ```php


 ...

echo jakharbek\topics\widgets\TopicsWidget::widget([
  'selected' => $model->topicsSelected(),
  'model_db' => $model,'name' => 'Posts[topicsform]'
  ]);

 ...

 * ```
 */
class TopicsWidget extends Widget
{
    /**
     *  Вы должны вести текушей тип темы который должно вывестись эти
     *  категории перечеслаются в
        Topics::find()->allTypes();
     *  100 = posts
     *  200 = pages
     *  300 = castings
     */

    public $type = 100;//posts
    /**
     * @var array|String
     * selected array not use separator
     * but if you use string you must use separator
     */
    public $selected = [];
    /**
     * @var string
     * array
     * delimitr separator
     * , . - and others for selected by function explode
     */
    public $separator = "array";
    /**
     * @var ActiveRecord model
     */
    public $model_db;
    /**
     * @var attribute model;
     */
    public $attribute = 'topicsform';

    public $name = "topicsform";

    private $data = null;

    public function init()
    {
        parent::init();
        $this->data = Topics::find()->buildTreeByRoot($this->getSelected(),$this->type);
    }
    private function getSelected(){
        if($this->separator == "array")
        {
            if(is_array($this->selected))
            {
                return $this->selected;
            }
        }
        if($this->separator !== "array")
        {
            if(!is_array($this->selected))
            {
                $selecteds = explode($this->separator,$this->selected);
                $this->selected = [];
                foreach ($selecteds as $selected)
                {
                    $this->selected[$selected] = '';
                }
                return $this->selected;
            }
        }
        return $this->selected;
    }
    public function run()
    {

        echo \wbraganca\fancytree\FancytreeWidget::widget([
            'options' =>[
                'source' => $this->data,
                'extensions' => ['dnd'],
                'checkbox'=> 'true',
                'selectMode'=> 2,
                'dnd' => [
                    'preventVoidMoves' => true,
                    'preventRecursiveMoves' => true,
                    'autoExpandMS' => 400,
                    'dragStart' => new JsExpression('function(node, data) {
				return true;
			}'),
                    'dragEnter' => new JsExpression('function(node, data) {
				return true;
			}'),
                    'dragDrop' => new JsExpression('function(node, data) {
				data.otherNode.moveTo(node, data.hitMode);
			}'),
                ],
                'select' => new JsExpression('function(event, data) {
        // Display list of selected nodes
         var selKeys = $.map(data.tree.getSelectedNodes(), function(node){
          return node.key;
        }); 
        $("#topics-data").val(selKeys.join(","));
      }'),
            ]
        ]);

    echo Html::hiddenInput($this->name, $this->model_db->{$this->attribute},['id' => 'topics-data']);

    }
}