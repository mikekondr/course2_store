<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "remains".
 *
 * @property int $id
 * @property int $good_id
 * @property int $consignment_id
 * @property int $document_id
 * @property int $created_at
 * @property float $count
 *
 * @property Goods $good
 * @property Consignments $consignment
 * @property Documents $document
 */
class Remains extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remains';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['good_id', 'consignment_id', 'count', 'document_id'], 'required'],
            [['good_id', 'consignment_id', 'document_id'], 'integer'],
            [['count'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/goods', 'ID'),
            'good_id' => Yii::t('app/goods', 'Good ID'),
            'consignment_id' => Yii::t('app/goods', 'Consignment ID'),
            'count' => Yii::t('app/goods', 'Count'),
            'created_at' => Yii::t('app', 'Created At'),
            'document_id' => Yii::t('app/docs', 'Document ID'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'created_at',
                ],
                'value' => function ($event) {
                    return $this->document->created_at;
                },
            ],
        ];
    }
    public function getGood()
    {
        return $this->hasOne(Goods::class, ['id' => 'good_id']);
    }

    public function getConsignment()
    {
        return $this->hasOne(Consignments::class, ['id' => 'consignment_id']);
    }

    public function getDocument()
    {
        return $this->hasOne(Documents::class, ['id' => 'document_id']);
    }
}
