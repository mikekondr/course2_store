<?php

namespace app\controllers;

use app\models\Remains;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RemainsController implements the CRUD actions for Remains model.
 */
class RemainsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * Lists all Remains models.
     *
     * @return string
     */
    public function actionCirculation()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Remains::find(),
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('circulation', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRemains()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => (new Query())
                ->select("r.good_id, r.consignment_id, SUM(r.count) count, g.name,
                    c.created_at cons_date, c.price")
                ->from(['r'=>'remains'])
                ->groupBy('good_id, consignment_id')
                ->leftJoin(['g'=>'goods'], 'g.id = r.good_id')
                ->leftJoin(['c'=>'consignments'], 'c.id = r.consignment_id')
                ->having('SUM(r.count) > 0'),
            'pagination' => [
                'pageSize' => 50
            ],
        ]);

        return $this->render('remains', [
            'dataProvider' => $dataProvider,
            'title' => Yii::t('app/goods', 'Remains'),
            'hasExpirity' => false,
        ]);
    }

    public function actionExpired()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => (new Query())
                ->select("r.good_id, r.consignment_id, SUM(r.count) count, g.name,
                    c.created_at cons_date, c.price, g.expiry")
                ->from(['r'=>'remains'])
                ->where(['>', ':now - (c.created_at + g.expiry * 24*60*60)', 0])
                ->params([':now' => time()])
                ->groupBy('good_id, consignment_id')
                ->leftJoin(['g'=>'goods'], 'g.id = r.good_id')
                ->leftJoin(['c'=>'consignments'], 'c.id = r.consignment_id')
                ->having('SUM(r.count) > 0'),
            'pagination' => [
                'pageSize' => 50
            ],
        ]);


        return $this->render('remains', [
            'dataProvider' => $dataProvider,
            'title' => Yii::t('app/goods', 'Expired'),
            'hasExpirity' => true,
        ]);
    }

    /**
     * Finds the Remains model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Remains the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Remains::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
