<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap4\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use app\models\ImportFormUsuario; // Asegúrate de que esté presente

$this->title = 'Usuarios Registrados';
$this->params['breadcrumbs'][] = $this->title;
$importModel = new ImportFormUsuario(); // Declarar la variable correctamente
?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $tipoUsuario = null;

        if (!Yii::$app->user->isGuest) {
            $tipoUsuario = Yii::$app->user->identity->tipo_usuario;

            if ($tipoUsuario === 8) {
                echo Html::a('Agregar Usuario <i class="fas fa-user-plus"></i>', ['create'], ['class' => 'btn btn-success my-3']);
                echo Html::a('Exportar <i class="fas fa-file-export"></i>', ['export'], [
                    'class' => 'btn btn-info my-3 mx-2',
                    'target' => '_blank'
                ]);
                
                echo Html::button('Importar <i class="fas fa-file-import"></i>', [
                    'class' => 'btn btn-warning my-3',
                    'data-toggle' => 'modal',
                    'data-target' => '#importModal'
                ]);
                
            }
        }
        ?>
    </p>

    <?php Pjax::begin(); ?>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'username',
                [
                    'attribute' => 'Status',
                    'value' => function ($model) {
                        return $model->Status == 1 ? 'Activo' : 'Inactivo';
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'Status', [
                        '1' => 'Activo',
                        '0' => 'Inactivo',
                    ], ['class' => 'form-control', 'prompt' => 'Todos']),
                ],
                [
                    'attribute' => 'tipo_usuario',
                    'value' => function ($model) {
                        $tipos = [
                            1 => 'Externo',
                            13 => 'Estudiante',
                            18 => 'Personal Universitario',
                            21 => 'Personal Biblioteca',
                            7 => 'Gerente',
                            8 => 'Administrador',
                        ];
                        return $tipos[$model->tipo_usuario] ?? 'N/A';
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'tipo_usuario', [
                        '1' => 'Externo',
                        '13' => 'Estudiante',
                        '18' => 'Personal Universitario',
                        '21' => 'Personal Biblioteca',
                        '7' => 'Gerente',
                        '8' => 'Administrador',
                    ], ['class' => 'form-control', 'prompt' => 'Todos']),
                ],
                [
                    'attribute' => 'Created_at',
                    'value' => 'Created_at',
                    'filter' => Html::input('date', 'UserSearch[Created_at]', $searchModel->Created_at ?? '', ['class' => 'form-control']),
                ],
                [
                    'attribute' => 'Updated_at',
                    'value' => 'Updated_at',
                    'filter' => Html::input('date', 'UserSearch[Updated_at]', $searchModel->Updated_at ?? '', ['class' => 'form-control']),
                ],
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                    'visible' => $tipoUsuario === 8,
                ],
            ],
        ]); ?>
    </div>

    <?php Pjax::end(); ?>

</div>

<!-- Modal para Importar -->
<?php
Modal::begin([
    'title' => 'Importar Usuarios',
    'id' => 'importModal',
]);

echo "<div class='mb-3'>
    <p><strong>Para importar los datos correctamente, el archivo CSV debe contener las siguientes columnas en este orden:</strong></p>
    <ul>
        <li><strong>id</strong>: ID del usuario (puede dejarse vacío si se genera automáticamente).</li>
        <li><strong>username</strong>: Nombre de usuario.</li>
        <li><strong>password</strong>: Contraseña del usuario (se recomienda en texto plano solo para importación).</li>
        <li><strong>auth_key</strong>: Clave de autenticación (puede dejarse vacío si se genera automáticamente).</li>
        <li><strong>status</strong>: Estado del usuario (por ejemplo, 10 para activo, 0 para inactivo).</li>
        <li><strong>tipo_usuario</strong>: Rol o tipo asignado (por ejemplo, \"administrador\", \"lector\").</li>
        <li><strong>created_at</strong>: Fecha de creación (formato YYYY-MM-DD HH:MM:SS).</li>
        <li><strong>updated_at</strong>: Fecha de última actualización (formato YYYY-MM-DD HH:MM:SS).</li>
    </ul>
    <p>Asegúrate de que el archivo esté correctamente formateado y con los encabezados mencionados antes de proceder con la carga.</p>
</div>";

$form = ActiveForm::begin([
    'action' => ['import'],
    'options' => ['enctype' => 'multipart/form-data']
]);

echo $form->field($importModel, 'file')->fileInput()->label('Seleccionar archivo CSV');

echo Html::submitButton('Importar', ['class' => 'btn btn-primary']);

ActiveForm::end();

Modal::end();
?>

