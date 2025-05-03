<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tesis $model */

$this->title = Yii::t('app', 'Actualizar Tesis: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tesis-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
