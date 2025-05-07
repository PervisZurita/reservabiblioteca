<?php

namespace app\controllers;

use Yii;
use app\models\Libro;
use app\models\LibroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class LibroController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['estudiante'],
                'rules' => [
                    [
                        'actions' => ['estudiante'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new LibroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id, $biblioteca_idbiblioteca)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $biblioteca_idbiblioteca),
        ]);
    }

    public function actionCreate()
    {
        $model = new Libro();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
        }

        $model->loadDefaultValues();

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id, $biblioteca_idbiblioteca)
    {
        $model = $this->findModel($id, $biblioteca_idbiblioteca);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id, $biblioteca_idbiblioteca)
    {
        $this->findModel($id, $biblioteca_idbiblioteca)->delete();
        return $this->redirect(['index']);
    }

    public function actionEstudiante()
    {
        $searchModel = new LibroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('estudiante', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function findModel($id, $biblioteca_idbiblioteca)
    {
        if (($model = Libro::findOne(['id' => $id, 'biblioteca_idbiblioteca' => $biblioteca_idbiblioteca])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionExport()
    {
        $libros = Libro::find()->all();
        $filename = 'libros_export.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename);

        $fp = fopen('php://output', 'w');
        fputcsv($fp, [
            'ubicacion', 'numero', 'campus', 'clasificacion', 'asignatura',
            'titulo', 'autor', 'editorial', 'pais', 'anio de publicacion', 'codigo de barras'
        ]);

        foreach ($libros as $libro) {
            fputcsv($fp, [
                $libro->ubicacion,
                $libro->numero,
                $libro->biblioteca_idbiblioteca,
                $libro->clasificacion,
                $libro->asignatura_id,
                $libro->titulo,
                $libro->autor,
                $libro->editorial,
                $libro->pais_codigopais,
                $libro->anio_publicacion,
                $libro->codigo_barras
            ]);
        }

        fclose($fp);
        exit;
    }

    public function actionImport()
    {
        if (Yii::$app->request->isPost && isset($_FILES['importFile'])) {
            $file = $_FILES['importFile']['tmp_name'];

            if (($handle = fopen($file, 'r')) !== false) {
                $first = true;
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    if ($first) {
                        $first = false;
                        continue;
                    }

                    $libro = new Libro();
                    $libro->ubicacion = $data[0];
                    $libro->numero = $data[1];
                    $libro->biblioteca_idbiblioteca = $data[2];
                    $libro->clasificacion = $data[3];
                    $libro->asignatura_id = $data[4];
                    $libro->titulo = $data[5];
                    $libro->autor = $data[6];
                    $libro->editorial = $data[7];
                    $libro->pais_codigopais = $data[8];
                    $libro->anio_publicacion = $data[9];
                    $libro->codigo_barras = $data[10];
                    $libro->save();
                }
                fclose($handle);
            }

            return $this->asJson(['success' => true, 'message' => 'Importación completada correctamente']);
        }

        return $this->asJson(['success' => false, 'message' => 'Archivo no válido']);
    }

    public function actionPlantilla()
    {
        $csvContent = "ubicacion,numero,campus,clasificacion,asignatura,titulo,autor,editorial,pais,anio_de_publicacion,codigo_de_barras\n";
        $csvContent .= "A1,101,Campus Central,Historia,Matemáticas,Historia Universal,Juan Pérez,Editorial ABC,México,2020,1234567890123\n";
        $csvContent .= "B2,102,Campus Norte,Literatura,Lengua Española,Don Quijote de la Mancha,Miguel de Cervantes,Editorial XYZ,España,1605,9876543210987\n";
        $csvContent .= "C3,103,Campus Sur,Ciencias,Física,Fundamentos de Física,Isaac Newton,Editorial MNO,Reino Unido,1687,1231231231231\n";

        Yii::$app->response->sendContentAsFile($csvContent, 'plantilla_libros.csv', [
            'mimeType' => 'text/csv',
            'forceDownload' => true,
        ]);
    }
}
