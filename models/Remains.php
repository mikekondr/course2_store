<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "remains".
 *
 * @property int $id
 * @property int $good_id
 * @property int $consignment_id
 * @property int $document_id
 * @property float $count
 *
 * @property Goods $good
 * @property Consignments $consignment
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
            [['good_id', 'consignment_id', 'count'], 'required'],
            [['good_id', 'consignment_id'], 'integer'],
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
}
