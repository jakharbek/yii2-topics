<?php
/**
 * Created by PhpStorm.
 * User: Javharbek
 * Date: 22.02.2018
 * Time: 14:55
 */
use kartik\tree\TreeView;
use jakharbek\topics\models\Topics;
use jakharbek\langs\components\Lang;
?>

<?php
echo \jakharbek\langs\widgets\LangsWidgets::widget();
echo TreeView::widget([
    // single query fetch to render the tree
    // use the Product model you have in the previous step
    'query' => Topics::find()->lang()->addOrderBy('root, lft'),
    'headingOptions' => ['label' => Yii::t('jakhar-topics','topics')],
    'fontAwesome' => true,
    'isAdmin' => false,
    'displayValue' => 1,
    'softDelete' => true,
    'cacheSettings' => [
        'enableCache' => true
    ],
    'nodeAddlViews' => [
        \kartik\tree\Module::VIEW_PART_2 => '@vendor/jakharbek/yii2-topics/src/views/_treeManagerBottomPlace',
    ]
]);