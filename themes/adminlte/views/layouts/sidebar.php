<aside class="main-sidebar sidebar-light-success elevation-4">
    <!-- Brand Logo -->
    <a href="/site/index" class="brand-link">
        <img src="<?= Yii::$app->urlManager->baseUrl ?>/img/ESCUDETO_UTE-LVT.png" alt="Universidad Luis Vargas Torres" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">UTELVT | Biblioteca</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!--img-circle elevation-2 Sidebar user panel (optional) -->
        <div class="user-panel  mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <?php if (!Yii::$app->user->isGuest) : ?>
                    <?php
                    $cacheKey = 'user_' . Yii::$app->user->id;
                    $userData = Yii::$app->cache->get($cacheKey);

                    if ($userData === false) {
                        // Los datos del usuario no están en caché, obtén los datos de la base de datos o de donde corresponda
                        $userData = Yii::$app->user->identity;
                        // Almacena los datos del usuario en la caché por un período de tiempo específico (por ejemplo, 3600 segundos o 1 hora)
                        Yii::$app->cache->set($cacheKey, $userData, 3600);
                    }

                    $personalData = $userData->personaldata;
                    if ($personalData !== null) {
                        $nombre = $personalData->Nombres;
                        $apellido = $personalData->Apellidos;
                        $url = ['/personaldata/view', 'Ci' => $personalData->Ci];
                        $perfil = $personalData->Nombres;
                    }

                    // Acceder a datos de la tabla InformacionPersonal
                    $informacionEstudiante = $userData->informacionpersonal;
                    if ($informacionEstudiante !== null) {
                        $nombre = $informacionEstudiante->NombInfPer;
                        $apellido = $informacionEstudiante->ApellInfPer;
                        $perfil = $nombre . ' ' . $apellido;
                        $url = ['/informacionpersonal/view', 'CIInfPer' => $informacionEstudiante->CIInfPer];
                    }
                    // Acceder a datos de la tabla InformacionPersonalD
                    $informacionDocente = $userData->informacionpersonalD;
                    if ($informacionDocente !== null) {
                        $nombre = $informacionDocente->NombInfPer;
                        $apellido = $informacionDocente->ApellInfPer;
                        $perfil = $nombre . ' ' . $apellido;
                        $url = ['/informacionpersonald/view', 'CIInfPer' => $informacionDocente->CIInfPer];
                    }

                    $iniciales = substr($nombre, 0, 1) . substr($apellido, 0, 1);
                    echo '<div class="user-initials">' . $iniciales . '</div>';
                    ?>
                <?php else : ?>
                    <div class="user-initials">¿?</div>
                <?php endif; ?>
            </div>
            <div class="info">
                <?php if (!Yii::$app->user->isGuest) : ?>
                    <?= \yii\helpers\Html::a($perfil, $url) ?>
                <?php else : ?>
                    <a href="#">¿Qué estas haciendo aquí?</a> <!-- O el texto que desees para usuarios no autenticados -->
            <?php endif; ?>
            </div>
        </div>

        <?php
        $commonMenuItems = [
            ['label' => 'Página Principal', 'icon' => 'fas fa-home', 'url' => ['/site/index']],
        ];

        $adminMenuItems = [
            ['label' => 'ADMIN', 'header' => true],
            ['label' => 'Usuarios', 'url' => ['/user/index'], 'icon' => 'fas fa-user'],
            [
                'label' => 'Registro de Personas',
                'icon' => 'fas fa-address-book',
                'items' => [
                    ['label' => 'Estudiantes', 'icon' => 'fas fa-user-graduate', 'url' => ['/informacionpersonal/index'],],
                    ['label' => 'Personal Universitario', 'icon' => 'far fa-user-tie', 'url' => ['/informacionpersonald/index'],],
                    ['label' => 'Personas Externas', 'icon' => 'far fa-id-card', 'url' => ['/personaldata/index'],],

                ]
            ],
            [
                'label' => 'Roles y Permisos',
                'icon' => 'fas fa-users',
                'items' => [
                    ['label' => 'Rol', 'url' => ['/rbac/role'], 'target' => '_blank'],
                    ['label' => 'Asignación', 'url' => ['/rbac/assignment'], 'target' => '_blank'],
                    ['label' => 'Rutas', 'url' => ['/rbac/route'], 'target' => '_blank'],
                    ['label' => 'Permisos', 'url' => ['/rbac/permission'], 'target' => '_blank'],
                ]
            ],
            // Otros elementos específicos para administradores
        ];

        $personalMenuItems = [
            ['label' => 'Personal', 'header' => true],
            [
                'label' => 'Préstamo',
                'icon' => 'fas fa-clipboard',
                'items' => [
                    ['label' => 'Registros de Préstamo', 'icon' => 'fas fa-folder-open', 'url' => ['/prestamo/index']],
                    ['label' => 'Ingresar Solicitud', 'icon' => 'fas fa-file-upload', 'url' => ['/prestamo/create']],
                ]
            ],
            [
                'label' => 'Libros',
                'icon' => 'fas fa-book',
                'items' => [
                    ['label' => 'Catálogo de Libros', 'icon' => 'fas fa-book-open', 'url' => ['/libro/index']],
                    ['label' => 'Añadir Libro', 'icon' => 'far fa-edit', 'url' => ['/libro/create']],
                ]
            ],
            ['label' => 'PC', 'url' => ['/pc/index'], 'icon' => 'fas fa-desktop'],

            [
                'label' => 'Estadísticas',
                'icon' => 'fas fa-chart-area',
                'items' => [
                    ['label' => 'General', 'icon' => 'fas fa-chart-bar', 'url' => ['/prestamo/info']],
                    ['label' => 'Libros por Asignatura', 'icon' => 'fas fa-chart-pie', 'url' => ['/prestamo/estadisticalibro']],
                ]
            ],
        ];

        $userType = $userData->tipo_usuario;
        // Selecciona el conjunto de menuItems según el tipo de usuario
        if ($userType === 21 || $userType === 7) {
            //personal
            $menuItems = array_merge($commonMenuItems, $personalMenuItems);
        } elseif ($userType === 8) {
            //adminitradores
            $menuItems = array_merge($commonMenuItems, $personalMenuItems, $adminMenuItems);
        } else {
            //usuarios comunes
            $menuItems = $commonMenuItems;
        }

        // Finalmente, pasa las variables definidas al widget de menú
        echo \hail812\adminlte\widgets\Menu::widget([
            'items' => $menuItems,
        ]);
        ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>