<?php

use app\models\Personaldata;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Lista de Personas Externas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personaldata-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $tipoUsuario = null; // Inicializamos la variable

        if (!Yii::$app->user->isGuest) {
            // El usuario ha iniciado sesión, podemos acceder a 'tipo_usuario'
            $tipoUsuario = Yii::$app->user->identity->tipo_usuario;

            if ($tipoUsuario === 8) {
                echo Html::a('Agregar Persona Externa <i class="fas fa-user-plus"></i>', ['create'], ['class' => 'btn btn-success my-3']);
            }
        }
        ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>


    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'], // Agrega una clase CSS personalizada al contenedor de paginación
                'maxButtonCount' => 5, // Controla el número de botones de página que se muestran
                'prevPageLabel' => 'Anterior',
                'nextPageLabel' => 'Siguiente',
                'prevPageCssClass' => 'page-item', // Clase CSS para el botón "Anterior"
                'nextPageCssClass' => 'page-item', // Clase CSS para el botón "Siguiente"
                'linkOptions' => ['class' => 'page-link'], // Agrega una clase CSS personalizada a los enlaces de página
                'activePageCssClass' => 'page-item active', // Clase CSS para la página activa
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'], // Estilo de los botones deshabilitados

            ],
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'Ci',
                'Apellidos',
                'Nombres',
                'FechaNacimiento',
                //'Genero',
                'Institucion',
                'Nivel',

                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Personaldata $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'Ci' => $model->Ci]);
                    },
                    'visible' => $tipoUsuario === 8, // Esto oculta la columna de acciones si el tipo de usuario es diferente de 8
                ],
            ],
        ]); ?>

        <?php Pjax::end(); ?>

    </div>

</div>