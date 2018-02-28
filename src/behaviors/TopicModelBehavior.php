<?php
namespace jakharbek\topics\behaviors;

/**
 *
 * @author Jakhar <javhar_work@mail.ru>
 *
 */

use jakharbek\topics\models\Topics;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class TopicModelBehavior
 * @package jakharbek\topics\behaviors
 * Поведение который добавлаются к behaviors к модели Active Record (Model)
 *
 * @example
 *
 * ```php
        use jakharbek\topics\behaviors\TopicModelBehavior;

        class Posts extends ActiveRecord
        {
            private $_topicsform;

            public function behaviors()
            {
                 ...
                        'topic_model'=> [
                        'class' => TopicModelBehavior::className(),
                        'attribute' => 'topicsform',
                        'separator' => ',',
                        ],
                 ...
            }

            ...

            public function getTopicsform(){
                return $this->_topicsform;
            }
            public function setTopicsform($value){
                return $this->_topicsform = $value;
            }
        }
 *
 *
 * ```
 */
class TopicModelBehavior extends Behavior
{
    /**
     * @var string
     * данные который используется в форме (Active Form)
     */
    public $attribute = "topicsform";
    /**
     * @var string
     * тип данных или сепаратор который разделает данные
     * типы array
     * сепаратор который разделает данные
     */
    public $separator = "array";

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE  => 'beforeInsertData',
            ActiveRecord::EVENT_AFTER_INSERT  => 'afterInsertData',
        ];
    }
    private function getData($topics){
        $data = [];
        if($this->separator == "array")
        {
            if(is_array($topics))
            {
                return $topics;
            }
        }
        if($this->separator !== "array")
        {
            if(!is_array($topics))
            {
                $selecteds = explode($this->separator,$topics);
                if(strlen(implode('',$selecteds)) == 0){return false;}

                foreach ($selecteds as $selected)
                {
                    $data[] = Topics::findOne($selected);
                }
                 return $data;
            }
        }
        return $topics;
    }
    private function unlinkData(){
        $topics = $this->owner->topics;
        if(count($topics) == 0){return false;}
        foreach ($topics as $topic):
            $this->owner->unlink('topics',$topic,true);
        endforeach;
    }
    public function beforeInsertData(){
        $model = $this->owner;

            //$model::getDb()->transaction(function($db) use ($model) {
                $this->unlinkData();
                $topics = $this->getData($this->owner->{$this->attribute});
                if(!$topics){return true;}
                foreach ($topics as $topic):
                    $this->owner->link('topics', $topic);
                endforeach;
            //}
    }
    public function afterInsertData(){
        $model = $this->owner;
            //$model::getDb()->transaction(function($db) use ($model) {
            $topics = $this->getData($this->owner->{$this->attribute});
            if(!$topics){return true;}
            foreach ($topics as $topic):
                $this->owner->link('topics', $topic);
            endforeach;

            //}
    }
    public function topicsSelected(){
        $topics = $this->owner->topics;
        $data = [];
        if(count($topics) == 0){return [];}
        foreach ($topics as $topic):
            $data[$topic->id] = $topic;
        endforeach;
        return $data;
    }
}