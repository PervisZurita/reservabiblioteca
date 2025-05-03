<?php

namespace app\controllers;

use app\models\InformacionpersonalD;
use app\models\InformacionpersonaldSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InformacionpersonaldController implements the CRUD actions for InformacionpersonalD model.
 */
class InformacionpersonaldController extends Controller
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
     * Lists all InformacionpersonalD models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InformacionpersonaldSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InformacionpersonalD model.
     * @param string $CIInfPer Ci Inf Per
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($CIInfPer)
    {
        return $this->render('view', [
            'model' => $this->findModel($CIInfPer),
        ]);
    }

    /**
     * Creates a new InformacionpersonalD model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new InformacionpersonalD();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'CIInfPer' => $model->CIInfPer]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing InformacionpersonalD model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $CIInfPer Ci Inf Per
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($CIInfPer)
    {
        $model = $this->findModel($CIInfPer);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CIInfPer' => $model->CIInfPer]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing InformacionpersonalD model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $CIInfPer Ci Inf Per
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($CIInfPer)
    {
        $this->findModel($CIInfPer)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the InformacionpersonalD model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $CIInfPer Ci Inf Per
     * @return InformacionpersonalD the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($CIInfPer)
    {
        if (($model = InformacionpersonalD::findOne(['CIInfPer' => $CIInfPer])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
