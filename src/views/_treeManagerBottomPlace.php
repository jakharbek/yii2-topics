<?php
/**
 * Created by PhpStorm.
 * User: Javharbek
 * Date: 22.02.2018
 * Time: 16:32
 */
echo $form->field($node, 'description')->textarea();

if($node->isRoot())
{
    echo $form->field($node, 'type')->dropDownList(\jakharbek\topics\models\Topics::find()->allTypes());
}

echo $form->field($node, 'slug');