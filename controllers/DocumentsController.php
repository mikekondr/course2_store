<?php

namespace app\controllers;

use app\models\DocumentRows;
use app\models\Documents;
use app\models\DocumentsSearch;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DocumentsController implements the CRUD actions for Documents model.
 */
class DocumentsController extends Controller
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
     * Lists all Documents models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DocumentsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Documents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Documents();
        $model->doc_date = time();
        $rows = [];

        if ($this->request->isPost) {
            $formDetails = Yii::$app->request->post('DocumentRows', []);
            foreach ($formDetails as $i => $formDetail) {
                $row = new DocumentRows(['scenario' => DocumentRows::SCENARIO_BATCH_UPDATE]);
                $row->setAttributes($formDetail);
                $rows[] = $row;
            }

            //handling if the addRow button has been pressed
            if (Yii::$app->request->post('addRow') == 'true') {
                $model->load(Yii::$app->request->post());
                $rows[] = new DocumentRows(['scenario' => DocumentRows::SCENARIO_BATCH_UPDATE]);
                return $this->render('create', [
                    'model' => $model,
                    'rows' => $rows
                ]);
            }

            if ($model->load($this->request->post())) {
                if (Model::validateMultiple($rows) && $model->validate()) {
                    $model->save();
                    foreach($rows as $row) {
                        $row->document_id = $model->id;
                        $row->save();
                    }
//                    return $this->redirect(['view', 'id' => $model->id]);
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'rows' => $rows
        ]);
    }

    /**
     * Updates an existing Documents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $rows = $model->documentRows;

        $formDetails = Yii::$app->request->post('DocumentRows', []);
        foreach ($formDetails as $i => $formDetail) {
            //loading the models if they are not new
            if (isset($formDetail['id']) && isset($formDetail['updateType']) && $formDetail['updateType'] != DocumentRows::UPDATE_TYPE_CREATE) {
                //making sure that it is actually a child of the main model
                $row = DocumentRows::findOne(['id' => $formDetail['id'], 'document_id' => $model->id]);
                $row->setScenario(DocumentRows::SCENARIO_BATCH_UPDATE);
                $row->setAttributes($formDetail);
                $rows[$i] = $row;
                //validate here if the modelDetail loaded is valid, and if it can be updated or deleted
            } else {
                $row = new DocumentRows(['scenario' => DocumentRows::SCENARIO_BATCH_UPDATE]);
                $row->setAttributes($formDetail);
                $rows[] = $row;
            }
        }

        //handling if the addRow button has been pressed
        if (Yii::$app->request->post('addRow') == 'true') {
            $rows[] = new DocumentRows(['scenario' => DocumentRows::SCENARIO_BATCH_UPDATE]);
            return $this->render('update', [
                'model' => $model,
                'rows' => $rows
            ]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Model::validateMultiple($rows) && $model->validate() && $model->save()) {
                $err = false;
                foreach($rows as $row) {
                    //details that has been flagged for deletion will be deleted
                    if ($row->updateType == DocumentRows::UPDATE_TYPE_DELETE) {
                        $row->delete();
                    } else {
                        //new or updated records go here
                        $row->document_id = $model->id;
                        if (!$row->save()) {
                            $err = true;
                            break;
                        }
                    }
                }
//                return $this->redirect(['view', 'id' => $model->id]);
                if (!$err)
                    return $this->redirect(['index']);
            }
        }


        return $this->render('update', [
            'model' => $model,
            'rows' => $rows
        ]);
    }

    /**
     * Deletes an existing Documents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Documents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Documents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Documents::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app/docs', 'The requested page does not exist.'));
    }
}
