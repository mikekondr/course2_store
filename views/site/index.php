<?php

/** @var yii\web\View $this */

use app\models\Documents;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$this->title = 'Store';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">

            <?php if (Yii::$app->authManager->getAssignment('manager', Yii::$app->user->id)): ?>

            <div class="col-12 col-xl-6">

                <div class="row">
                    <h3 class="col text-center"><?=Yii::t('app', 'Documents')?></h3>
                </div>
                <div class="row">
                    <div class="col">
                        <?= GridView::widget([
                            'dataProvider' => new ActiveDataProvider([
                                'query' => Documents::find()->orderBy(['created_at' => SORT_DESC])->limit(20),
                                'sort' => false,
                            ]),
                            'layout' => "{items}\n{pager}",
                            'columns' => [
                                'id',
                                [
                                    'attribute' => 'doc_date',
                                    'value' => 'date',
                                ],
                                [
                                    'attribute' => 'doc_type',
                                    'value' => function($model) {
                                        return Yii::t('app/docs', Documents::DOCTYPE_NAMES[$model->doc_type]);
                                    }
                                ],
                                [
                                    'attribute' => 'doc_state',
                                    'value' => function($model) {
                                        return Yii::t('app/docs', Documents::DOCSTATE_NAMES[$model->doc_state]);
                                    }
                                ],
                                [
                                    'attribute' => 'author_id',
                                    'value' => 'author.fullname',
                                ],
                            ],
                        ]);?>
                        <?= Html::a('Всі документи...', ['documents/index'], ['class' => 'text-end']) ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="row">
                    <h3 class="col text-center">Прострочені товари</h3>
                </div>
                <div class="row">
                    <?=GridView::widget([
                        'dataProvider' => new ActiveDataProvider([
                            'query' => (new \yii\db\Query())
                                ->select("r.good_id, r.consignment_id, SUM(r.count) count, g.name,
                                                        c.created_at cons_date, c.price, g.expiry")
                                ->from(['r'=>'remains'])
                                ->where(['>', ':now - (c.created_at + g.expiry * 24*60*60)', 0])
                                ->params([':now' => time()])
                                ->groupBy('good_id, consignment_id')
                                ->leftJoin(['g'=>'goods'], 'g.id = r.good_id')
                                ->leftJoin(['c'=>'consignments'], 'c.id = r.consignment_id')
                                ->having('SUM(r.count) > 0')
                                ->orderBy('count DESC')
                                ->limit(20),
                            'pagination' => [
                                'pageSize' => 50
                            ],
                        ]),
                        'layout' => "{items}\n{pager}",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'good_id',
                                'label' => Yii::t('app/goods', 'Good'),
                                'value' => function ($model) {
                                    return $model['name'];
                                }
                            ],
                            [
                                'attribute' => 'consignment_id',
                                'label' => Yii::t('app/goods', 'Consignment'),
                                'value' => function ($model) {
                                    return is_null($model['cons_date']) ? '' : date('d.m.Y', $model['cons_date']) . ' (' . $model['price'] . ')';
                                },
                            ],
                            [
                                'label' => Yii::t('app/goods', 'Expire'),
                                'value' => function ($model) {
                                    if (is_null($model['expiry']))
                                        return '-';
                                    else {
                                        $dt = new DateTime(date('Y-m-d', $model['cons_date']));
                                        $dt->add(new DateInterval('P' . $model['expiry'] . 'D'));

                                        return $dt->format('d.m.Y');
                                    }
                                }
                            ],
                            'count',
                        ],
                    ]);?>
                    <?= Html::a('Повний звіт...', ['remains/expired']) ?>
                </div>
            </div>

            <?php elseif (Yii::$app->authManager->getAssignment('storekeeper', Yii::$app->user->id)): ?>

            <div class="col-12 col-xl-6">

                <div class="row">
                    <h3 class="col text-center"><?=Yii::t('app', 'Documents')?></h3>
                </div>
                <div class="row">
                    <div class="col">
                        <?= GridView::widget([
                            'dataProvider' => new ActiveDataProvider([
                                'query' => Documents::find()->orderBy(['created_at' => SORT_DESC])->limit(20)->where(['<>', 'doc_type', 3]),
                                'sort' => false,
                            ]),
                            'layout' => "{items}\n{pager}",
                            'columns' => [
                                'id',
                                [
                                    'attribute' => 'doc_date',
                                    'value' => 'date',
                                ],
                                [
                                    'attribute' => 'doc_type',
                                    'value' => function($model) {
                                        return Yii::t('app/docs', Documents::DOCTYPE_NAMES[$model->doc_type]);
                                    }
                                ],
                                [
                                    'attribute' => 'doc_state',
                                    'value' => function($model) {
                                        return Yii::t('app/docs', Documents::DOCSTATE_NAMES[$model->doc_state]);
                                    }
                                ],
                                [
                                    'attribute' => 'author_id',
                                    'value' => 'author.fullname',
                                ],
                            ],
                        ]);?>
                        <?= Html::a('Всі документи...', ['documents/index']) ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6">
                <div class="row">
                    <h3 class="col text-center">Залишки товарів</h3>
                </div>
                <div class="row">
                    <?=GridView::widget([
                        'dataProvider' => new ActiveDataProvider([
                            'query' => (new \yii\db\Query())
                                ->select("r.good_id, r.consignment_id, SUM(r.count) count, g.name,
                                                        c.created_at cons_date, c.price")
                                ->from(['r'=>'remains'])
                                ->groupBy('good_id, consignment_id')
                                ->leftJoin(['g'=>'goods'], 'g.id = r.good_id')
                                ->leftJoin(['c'=>'consignments'], 'c.id = r.consignment_id')
                                ->having('SUM(r.count) > 0')
                                ->orderBy('count ASC')
                                ->limit(20),
                        ]),
                        'layout' => "{items}\n{pager}",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'good_id',
                                'label' => Yii::t('app/goods', 'Good'),
                                'value' => function ($model) {
                                    return $model['name'];
                                }
                            ],
                            [
                                'attribute' => 'consignment_id',
                                'label' => Yii::t('app/goods', 'Consignment'),
                                'value' => function ($model) {
                                    return is_null($model['cons_date']) ? '' : date('d.m.Y', $model['cons_date']) . ' (' . $model['price'] . ')';
                                },
                            ],
                            'count',
                        ],
                    ]) ?>
                    <?= Html::a('Повний звіт...', ['remains/remains']) ?>
                </div>
            </div>

            <?php elseif (Yii::$app->authManager->getAssignment('client', Yii::$app->user->id)): ?>

            <div class="col-12 col-xl-6">
                <div class="row">
                    <h3 class="col text-center"><?=Yii::t('app', 'My orders')?></h3>
                </div>
                <div class="row">
                    <div class="col">
                        <?= GridView::widget([
                            'dataProvider' => new ActiveDataProvider([
                                'query' => Documents::find()
                                    ->orderBy(['created_at' => SORT_DESC])
                                    ->limit(20)
                                    ->where([
                                        'doc_type' => 3,
                                        'author_id' => Yii::$app->user->id,
                                    ]),
                                'sort' => false,
                            ]),
                            'layout' => "{items}\n{pager}",
                            'columns' => [
                                'id',
                                [
                                    'attribute' => 'doc_date',
                                    'value' => 'date',
                                ],
                                [
                                    'attribute' => 'doc_type',
                                    'value' => function($model) {
                                        return Yii::t('app/docs', Documents::DOCTYPE_NAMES[$model->doc_type]);
                                    }
                                ],
                                [
                                    'attribute' => 'doc_state',
                                    'value' => function($model) {
                                        return Yii::t('app/docs', Documents::DOCSTATE_NAMES[$model->doc_state]);
                                    }
                                ],
                                [
                                    'attribute' => 'author_id',
                                    'value' => 'author.fullname',
                                ],
                            ],
                        ]);?>
                        <?= Html::a('Всі документи...', ['documents/index']) ?>
                    </div>
                </div>
            </div>
                <div class="col-12 col-xl-6">
                    <div class="row">
                        <h3 class="col text-center">Залишки товарів</h3>
                    </div>
                    <div class="row">
                        <?=GridView::widget([
                            'dataProvider' => new ActiveDataProvider([
                                'query' => (new \yii\db\Query())
                                    ->select("r.good_id, r.consignment_id, SUM(r.count) count, g.name,
                                                        c.created_at cons_date, c.price")
                                    ->from(['r'=>'remains'])
                                    ->groupBy('good_id, consignment_id')
                                    ->leftJoin(['g'=>'goods'], 'g.id = r.good_id')
                                    ->leftJoin(['c'=>'consignments'], 'c.id = r.consignment_id')
                                    ->having('SUM(r.count) > 0')
                                    ->orderBy('count ASC')
                                    ->limit(20),
                            ]),
                            'layout' => "{items}\n{pager}",
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'good_id',
                                    'label' => Yii::t('app/goods', 'Good'),
                                    'value' => function ($model) {
                                        return $model['name'];
                                    }
                                ],
                                [
                                    'attribute' => 'consignment_id',
                                    'label' => Yii::t('app/goods', 'Consignment'),
                                    'value' => function ($model) {
                                        return is_null($model['cons_date']) ? '' : date('d.m.Y', $model['cons_date']) . ' (' . $model['price'] . ')';
                                    },
                                ],
                                'count',
                            ],
                        ]) ?>
                        <?= Html::a('Повний звіт...', ['remains/remains']) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
