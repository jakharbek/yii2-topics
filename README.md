Topics
==========
Topics

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jakharbek/yii2-topics "*"
```

or add

```
"jakharbek/yii2-topics": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

You need to connect a controller or an action to the controller

controller

```php

    'controllerMap' => [ 
        'topics' => 'jakharbek\topics\controllers\TopicsController'
    ],

```

action

```php

   public function actions()
       {
           return [ 
               'topics' => [
                   'class' => 'jakharbek\topics\actions\TopicsAction'
               ] 
           ];
       }

```

You must have an extension
```
jakharbek/yii2-langs
```

You need to connect i18n for translations

```php
 'jakhar-topics' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/jakharbek/yii2-topics/src/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'jakhar-topics'       => 'main.php',
                    ],
                ],
```

and migrate the database

```php
yii migrate --migrationPath=@vendor/jakharbek/yii2-topics/src/migrations
```



Update (Active Record) - Single
-----

example with Posts elements

You must connect behavior to your database model (Active Record)
```php
 'topic_model'=> [
                        'class' => TopicModelBehavior::className(),
                        'attribute' => 'Topicsform',
                        'separator' => ',',
                        ],
```

example

```php
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
```

Afterwards you need to add your widget form.
```php
jakharbek\topics\widgets\TopicsWidget::widget
```
example
```php
echo jakharbek\topics\widgets\TopicsWidget::widget([
  'selected' => $model->topicsSelected(),
  'model_db' => $model,'name' => 'Posts[topicsform]'
  ]);
```

and of course do not forget to prescribe links for your model

```php
    public function getPoststopics()
    {
        return $this->hasMany(Poststopics::className(), ['post_id' => 'post_id']);
    }


    public function getTopics()
    {
        return $this->hasMany(Topics::className(), ['id' => 'id'])->viaTable('poststopics', ['post_id' => 'post_id']);
    }
```

It's all!

