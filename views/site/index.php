<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;

$this->title = 'Biblioteca General';

$this->registerCss("
@import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap');

.welcome-container {
    background-size: cover;
    background-position: center;
    animation: backgroundAnimation 60s infinite alternate;
    transition: background-image 2s ease-in-out;
}

@keyframes backgroundAnimation {
    0% { background-image: url('/img/1.jpg'); }
    25% { background-image: url('/img/2.jpeg'); }
    50% { background-image: url('/img/3.jpeg'); }
    75% { background-image: url('/img/4.jpeg'); }
    100% { background-image: url('/img/1.jpg'); }
}

.site-index {
    text-align: center;
    padding: 30px 0;
    color: #FFF;
    font-family: 'Raleway', sans-serif;
    font-size: 1.2em;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    text-transform: uppercase;
}

.site-index h1 {
    font-size: 3em;
    margin: 20px 0;
    transition: font-size 0.3s;
}

.site-index:hover h1 {
    font-size: 3.5em;
}

.site-index .lead {
    font-size: 1.2em;
    margin-bottom: 20px;
}

.site-index .btn-success,
.site-index .btn-primary {
    font-size: 1em;
    padding: 15px 20px;
    background-color: #5EB400;
    border: none;
    color: #FFF;
    transition: background-color 0.3s, transform 0.2s;
    margin: 10px;
}

.site-index .btn-success:hover,
.site-index .btn-primary:hover {
    background-color: #4E9A00;
    transform: scale(1.05);
}

.col-lg-4 {
    flex: 1;
    margin: 20px;
    padding: 20px;
    border-radius: 10px;
    background-color: #FFF;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.col-lg-4:hover {
    transform: scale(1.05);
}

.col-lg-4 p {
    font-size: 1em;
    margin-bottom: 10px;
}

.btn-outline-secondary {
    font-size: 1em;
    background-color: #FF6B00;
    color: #FFF;
    border: none;
}

.btn-outline-secondary:hover {
    background-color: #E95A00;
}
");

?>

<style>
    .icon-box {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        text-align: center;
        margin-bottom: 30px;
        transition: transform 0.3s ease;
    }

    .icon-box:hover {
        transform: scale(1.1);
    }

    .icon-box i {
        font-size: 80px;
        color: #007bff;
        margin-bottom: 20px;
        transition: color 0.3s ease;
    }

    .icon-box:hover i {
        color: #0056b3;
    }

    .icon-box h2 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .icon-box p {
        font-size: 16px;
        color: #6c757d;
    }

    .btn-outline-secondary {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #007bff;
        color: #fff;
    }

    @media (max-width: 768px) {
        .icon-box {
            margin-bottom: 50px;
        }
    }
    
    .icon-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .icon-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .icon-box i {
        transition: color 0.3s ease;
    }

    .icon-box:hover i {
        color: #fff;
    }

    .btn-primary, .btn-info, .btn-success {
        font-weight: bold;
        text-transform: uppercase;
        padding: 12px 24px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary:hover, .btn-info:hover, .btn-success:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .btn-lg {
        font-size: 1.2rem;
    }

    .text-primary { color: #007bff !important; }
    .text-info { color: #17a2b8 !important; }
    .text-success { color: #28a745 !important; }
    .text-muted { color: #6c757d !important; }
</style>

<div class="site-index welcome-container">
    <?php
    $cacheKey = 'user_' . Yii::$app->user->id;
    $userData = Yii::$app->cache->get($cacheKey);

    if ($userData === false) {
        $userData = Yii::$app->user->identity;
        $userData = app\models\User::find()
            ->with('personaldata', 'informacionpersonal', 'informacionpersonalD')
            ->where(['id' => Yii::$app->user->id])
            ->one();
        Yii::$app->cache->set($cacheKey, $userData, 3600);
    }

    $personalData = $userData->personaldata;
    $informacionEstudiante = $userData->informacionpersonal;
    $informacionDocente = $userData->informacionpersonalD;
    $modi = User::findByUsername($userData->username);
    $nombres = '';
    $tipoUsuario = ''; // Variable para almacenar el tipo de usuario

    if ($personalData !== null) {
        $nombres = $personalData->Nombres;
        $tipoUsuario = $userData->tipo_usuario; // Asumiendo que el tipo de usuario está en el modelo User
        
        if ($informacionEstudiante !== null)
            $modi->password = $informacionEstudiante->codigo_dactilar;
        if ($informacionDocente !== null)
            $modi->password = $informacionDocente->ClaveUsu;
        $modi->save();
        $url = ['/personaldata/update', 'Ci' => $personalData->Ci];
    } elseif ($informacionEstudiante !== null) {
        $url = ['/informacionpersonal/update', 'CIInfPer' => $informacionEstudiante->CIInfPer];
        $nombres = $informacionEstudiante->NombInfPer;
        $tipoUsuario = 'Estudiante';
    } elseif ($informacionDocente !== null) {
        $url = ['/informacionpersonald/update', 'CIInfPer' => $informacionDocente->CIInfPer];
        $nombres = $informacionDocente->NombInfPer;
        $tipoUsuario = 'Docente';
    }
    ?>

    <h1 class="display-4">¡Bienvenido, <?php echo $nombres ?>!</h1>
    <p class="lead">"Te deseamos una experiencia enriquecedora y llena de inspiración."</p>
</div>

<div class="body-content">
    <?php if (!Yii::$app->user->isGuest && in_array($tipoUsuario, ['Estudiante', 'Externo'])): ?>
    <div class="row">
        <!-- Espacios de la Biblioteca -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="icon-box p-4 border rounded shadow-lg bg-light text-center">
                <i class="fas fa-chair fa-3x mb-3 text-primary"></i>
                <h2 class="h4 font-weight-bold text-dark">Espacios de la Biblioteca</h2>
                <p class="text-muted">Reserva un espacio en la biblioteca para estudiar, trabajar en equipo o realizar investigaciones en un entorno cómodo y tranquilo.</p>
                <?php echo Html::button('SOLICITAR ESPACIO', [
                    'class' => 'btn btn-primary btn-lg',
                    'id' => 'open-modal-button',
                    'data-toggle' => 'modal',
                    'data-target' => '#prestamo-modal',
                    'data-remote' => Url::to(['/prestamo/prestarespacio']),
                ]); ?>
            </div>
        </div>

        <!-- Computadoras -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="icon-box p-4 border rounded shadow-lg bg-light text-center">
                <i class="fas fa-laptop fa-3x mb-3 text-info"></i>
                <h2 class="h4 font-weight-bold text-dark">Computadoras</h2>
                <p class="text-muted">¿Necesitas un computador para tus tareas o proyectos? También ofrecemos la posibilidad de solicitar préstamo de computadoras.</p>
                <p><a class="btn btn-info btn-lg" href="<?= Url::to(['/pc/index']) ?>">SOLICITAR PC &raquo;</a></p>
            </div>
        </div>

        <!-- Catálogo de Libros -->
        <div class="col-12 col-lg-4 mb-4">
            <div class="icon-box p-4 border rounded shadow-lg bg-light text-center">
                <i class="fas fa-book fa-3x mb-3 text-success"></i>
                <h2 class="h4 font-weight-bold text-dark">Catálogo de Libros</h2>
                <p class="text-muted">Explora nuestra amplia colección de libros. Sumérgete en el mundo de la literatura y descubre nuevas historias y conocimientos.</p>
                <p><a class="btn btn-success btn-lg" href="<?= Url::to(['/libro/index']) ?>">SOLICITAR LIBROS &raquo;</a></p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="modal fade" id="prestamo-modal" tabindex="-1" role="dialog" aria-labelledby="prestamo-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="prestamo-modal-label"><i class="fas fa-university"></i> Registro de Visita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="prestamo-modal-content"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs('
    $("#open-modal-button").on("click", function () {
        $("#prestamo-modal-content").load($(this).data("remote"), function() {
            $("#prestamo-modal-content #submit-button").on("click", function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "/prestamo/prestarespacio",
                    data: $("#prestamo-formulario").serialize(),
                    success: function (data) {
                        // Manejar la respuesta
                    }
                });
            });
        });
    });
');
?>