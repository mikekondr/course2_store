<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "consignments".
 *
 * @property int $id
 * @property int $good_id
 * @property Documents $document_id
 * @property float $price
 * @property int $created_at
 *
 * @property Goods $good
 * @property string $name
 */
class Consignments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consignments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['good_id', 'price', 'created_at'], 'required'],
            [['good_id', 'created_at'], 'integer'],
            [['price'], 'number'],
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
            'price' => Yii::t('app/goods', 'Price'),
            'created_at' => Yii::t('app/goods', 'Created At'),
        ];
    }

    public function getGood()
    {
        return $this->hasOne(Goods::class, ['goods_id' => 'good_id']);
    }

    public function getName()
    {
        return \Yii::$app->formatter->asDatetime($this->created_at, 'dd.MM.yyyy') . ' (' . $this->price . ')';
    }
}
