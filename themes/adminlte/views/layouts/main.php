<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;


\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');


$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
$this->registerJsFile($publishedRes[1].'/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <style>
        .user-initials {
        width: 40px; /* Ajusta el ancho para que coincida con el tamaño deseado */
        height: 40px; /* Ajusta la altura para que coincida con el tamaño deseado */
        background-color: #ff8800;
        color: #ffffff;
        text-align: center;
        line-height: 40px; /* Centra verticalmente de manera similar a elevation-2 */
        border-radius: 50%; /* Forma de círculo */
        font-size: 20px; /* Ajusta el tamaño de fuente para que coincida con el estilo */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Sombra similar a elevation-2 */
    }
    </style>
    
</head>
<body class="hold-transition layout-fixed sidebar-collapse sidebar-mini layout-navbar-fixed">
<?php $this->beginBody() ?>

<div class="wrapper">


    <?php
    // Mostrar el menú solo si el usuario ha iniciado sesión
    if (!Yii::$app->user->isGuest) {
        echo $this->render('navbar', ['assetDir' => $assetDir]);
    }
    ?>

    <!-- Content Wrapper. Contains page content -->
    <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
    <!-- /.content-wrapper -->

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>