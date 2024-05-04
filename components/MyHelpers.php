<?php

namespace app\components;

class MyHelpers
{
    public static function getCreatedUpdatedGridCols(): array
    {
        return [
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
//                    if ( time() - $model['created_at'] > 3600 * 24 )
                        return \Yii::$app->formatter->asDatetime($model['created_at'], 'HH:mm dd.MM');
//                    else
//                        return \Yii::$app->formatter->asTime($model['created_at']);
                },
                'options' => [
                    'class' => 'd-none d-xl-table-cell',
                ],
                'contentOptions' => [
                    'class' => 'd-none d-xl-table-cell',
                    'style' => 'width: 110px;',
                ],
                'headerOptions' => [
                    'class' => 'd-none d-xl-table-cell',
                ],
                'filterOptions' => [
                    'class' => 'd-none d-xl-table-cell',
                ],
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
//                    if ( time() - $model['updated_at'] > 3600 * 24 )
                        return \Yii::$app->formatter->asDatetime($model['updated_at'], 'HH:mm dd.MM');
//                    else
//                        return \Yii::$app->formatter->asTime($model['updated_at']);
                },

                'options' => [
                    'class' => 'd-none d-lg-table-cell',
                ],
                'contentOptions' => [
                    'class' => 'd-none d-lg-table-cell',
                    'style' => 'width: 110px;',
                ],
                'headerOptions' => [
                    'class' => 'd-none d-lg-table-cell',
                ],
                'filterOptions' => [
                    'class' => 'd-none d-lg-table-cell',
                ],
            ],

        ];
    }
}