<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\InformacionpersonalD $model */

$this->title = 'Ingresar Docente';
$this->params['breadcrumbs'][] = ['label' => 'Informacionpersonal Ds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="informacionpersonal-d-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
