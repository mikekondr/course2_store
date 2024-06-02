<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name
 * @property string $vendor
 * @property int|null $category_id
 * @property int $expiry
 *
 * @property Categories $category
 */
class Goods extends \yii\db\ActiveRecord
{
    public $category_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'expiry'], 'integer'],
            [['name', 'vendor', 'category_id'], 'required'],
            [['name'], 'string', 'max' => 150],
            [['vendor'], 'string', 'max' => 50],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['category_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/goods', 'ID'),
            'name' => Yii::t('app/goods', 'Name'),
            'vendor' => Yii::t('app/goods', 'Vendor'),
            'category_id' => Yii::t('app/goods', 'Category ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'category_name' => Yii::t('app/categories', 'Name'),
            'expiry' => Yii::t('app/goods', 'Expiry'),
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }
}
