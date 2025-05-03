<?php
use yii\helpers\Html;
?>

<h5>¿Desea prestar el computador <strong><?= Html::encode($model->nombre) ?></strong>?</h5>
<p>Ubicado en: <strong><?= Html::encode($model->bibliotecaIdbiblioteca->Campus) ?></strong></p>
<p>Estado actual: <strong><?= Html::encode($model->estado) ?></strong></p>

<div class="text-right">
    <?= Html::a('Confirmar Préstamo', ['confirmar-prestamo', 'id' => $model->idpc], [
        'class' => 'btn btn-primary',
    ]) ?>
    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
</div>
