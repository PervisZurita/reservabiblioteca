<!-- Navbar -->
<?php

use yii\helpers\Html;
use yii\helpers\Url;

$userType = Yii::$app->user->identity->tipo_usuario ?? null;
?>

<nav class="main-header navbar navbar-expand navbar-dark bg-primary">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a href="/site/index" class="nav-link d-flex align-items-center">
                <img src="<?= Yii::$app->urlManager->baseUrl ?>/img/ESCUDETO_UTE-LVT.png" 
                     alt="Universidad Luis Vargas Torres" 
                     class="img-fluid rounded-circle mr-2" 
                     style="height: 35px;">
                <span class="font-weight-bold text-light">UTELVT | Biblioteca</span>
            </a>
        </li>

        <?php if ($userType === 21 || $userType === 7 || $userType === 8): ?>
            <li class="nav-item ml-3">
                <a href="<?= Url::to(['/user/index']) ?>" class="nav-link">
                    <i class="fas fa-users text-light"></i>&nbsp;Usuarios
                </a>
            </li>

            <li class="nav-item dropdown ml-3">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-book text-light"></i>&nbsp;Circulación
                </a>
                <div class="dropdown-menu shadow rounded border-0">
                    <a href="<?= Url::to(['/libro/index']) ?>" class="dropdown-item">Catálogo de Libros</a>
                    <div class="dropdown-divider"></div>
                    <a href="<?= Url::to(['/pc/index']) ?>" class="dropdown-item">Computadoras</a>
                    <div class="dropdown-divider"></div>
                    <a href="<?= Url::to(['/tesis/index']) ?>" class="dropdown-item">Catálogo de Tesis</a>
                </div>
            </li>

            <li class="nav-item dropdown ml-3">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-archive text-light"></i>&nbsp;Repositorio
                </a>
                <div class="dropdown-menu shadow rounded border-0">
                    <a href="<?= Url::to(['/tesis/index']) ?>" class="dropdown-item">Catálogo de Tesis</a>
                </div>
            </li>
            <li class="nav-item dropdown ml-3">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-credit-card text-light"></i>&nbsp;Préstamo
            </a>
            <div class="dropdown-menu shadow rounded border-0">
                <a href="<?= Url::to(['/prestamo/create']) ?>" class="dropdown-item">Préstamo</a>
                <a href="<?= Url::to(['/prestamo/estadisticalibro']) ?>" class="dropdown-item">Generador de Estadística</a>
            </div>
        </li>
        <?php endif; ?>
        <li class="nav-item dropdown ml-3">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-clock text-light"></i>&nbsp;Horarios
            </a>
            <div class="dropdown-menu shadow rounded border-0">
                <a href="<?= Url::to(['/biblioteca/view?idbiblioteca=1']) ?>" class="dropdown-item">Esmeraldas</a>
                <div class="dropdown-divider"></div>
                <a href="<?= Url::to(['/biblioteca/view?idbiblioteca=3']) ?>" class="dropdown-item">Mútile</a>
                <div class="dropdown-divider"></div>
                <a href="<?= Url::to(['/biblioteca/view?idbiblioteca=2']) ?>" class="dropdown-item">La Concordia</a>
            </div>
        </li>

        <li class="nav-item ml-3">
            <a href="<?= Url::to(['/site/about']) ?>" class="nav-link text-light">Acerca de Nosotros</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <?php if (!Yii::$app->user->isGuest): ?>
                <?php
                    $cacheKey = 'user_' . Yii::$app->user->id;
                    $userData = Yii::$app->cache->get($cacheKey);
                    if ($userData === false) {
                        $userData = Yii::$app->user->identity;
                        Yii::$app->cache->set($cacheKey, $userData, 3600);
                    }

                    $nombre = '';
                    $apellido = '';
                    $url = ['#'];
                    $personalData = $userData->personaldata;
                    if ($personalData !== null) {
                        $nombre = $personalData->Nombres;
                        $apellido = $personalData->Apellidos;
                        $url = ['/personaldata/view', 'Ci' => $personalData->Ci];
                    }
                    $iniciales = strtoupper(substr($nombre, 0, 1) . substr($apellido, 0, 1));
                ?>
                <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-toggle="dropdown">
                    <div class="user-initials bg-light text-dark rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px;">
                        <?= $iniciales ?>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow rounded" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="<?= Url::to($url) ?>">Editar Datos</a>
                    <div class="dropdown-divider"></div>
                    <?= Html::a('Cerrar Sesión', ['/site/logout'], ['data-method' => 'post', 'class' => 'dropdown-item']) ?>
                </div>
            <?php else: ?>
                <div class="user-initials bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px;">
                    ¿?
                </div>
            <?php endif; ?>
        </li>
    </ul>
</nav>
