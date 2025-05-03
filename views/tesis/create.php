<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tesis $model */

$this->title = Yii::t('app', 'Crear Tesis');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tesis-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
