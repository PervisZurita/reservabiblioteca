<?php

namespace app\controllers;

use app\models\Pc;
use app\models\PcSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PcController implements the CRUD actions for Pc model.
 */
class PcController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Pc models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PcSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pc model.
     * @param int $idpc Idpc
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($idpc, $biblioteca_idbiblioteca)
    {
        return $this->render('view', [
            'model' => $this->findModel($idpc, $biblioteca_idbiblioteca),
        ]);
    }

    /**
     * Creates a new Pc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Pc();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'idpc' => $model->idpc, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $idpc Idpc
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($idpc, $biblioteca_idbiblioteca)
    {
        $model = $this->findModel($idpc, $biblioteca_idbiblioteca);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'idpc' => $model->idpc, 'biblioteca_idbiblioteca' => $model->biblioteca_idbiblioteca]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $idpc Idpc
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($idpc, $biblioteca_idbiblioteca)
    {
        $this->findModel($idpc, $biblioteca_idbiblioteca)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $idpc Idpc
     * @param int $biblioteca_idbiblioteca Biblioteca Idbiblioteca
     * @return Pc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idpc, $biblioteca_idbiblioteca)
    {
        if (($model = Pc::findOne(['idpc' => $idpc, 'biblioteca_idbiblioteca' => $biblioteca_idbiblioteca])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
  
public function actionPrestar($idpc, $biblioteca_idbiblioteca)
{
    $model = $this->findModel($idpc, $biblioteca_idbiblioteca);
    
    // Verificamos que el PC esté disponible antes de prestarlo
    if ($model->estado === 'D') {
        $model->estado = 'ND'; // 'ND' significa No Disponible
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'El PC ha sido prestado.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo prestar el PC.');
        }
    } else {
        Yii::$app->session->setFlash('warning', 'El PC no está disponible para préstamo.');
    }

    return $this->redirect(['index']);
}
}
