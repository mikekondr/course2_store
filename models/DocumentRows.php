<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "document_rows".
 *
 * @property int $id
 * @property int $document_id
 * @property int $good_id
 * @property Goods $good
 * @property int $count
 * @property float $price
 *
 * @property Documents $document
 */
class DocumentRows extends ActiveRecord
{
    /**
     * these are flags that are used by the form to dictate how the loop will handle each item
     */
    const UPDATE_TYPE_CREATE = 'create';
    const UPDATE_TYPE_UPDATE = 'update';
    const UPDATE_TYPE_DELETE = 'delete';

    const SCENARIO_BATCH_UPDATE = 'batchUpdate';

    private $_updateType;

    public function getUpdateType()
    {
        if (empty($this->_updateType)) {
            if ($this->isNewRecord) {
                $this->_updateType = self::UPDATE_TYPE_CREATE;
            } else {
                $this->_updateType = self::UPDATE_TYPE_UPDATE;
            }
        }

        return $this->_updateType;
    }

    public function setUpdateType($value)
    {
        $this->_updateType = $value;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_rows';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['updateType', 'required', 'on' => self::SCENARIO_BATCH_UPDATE],
            ['updateType',
                'in',
                'range' => [self::UPDATE_TYPE_CREATE, self::UPDATE_TYPE_UPDATE, self::UPDATE_TYPE_DELETE],
                'on' => self::SCENARIO_BATCH_UPDATE
            ],

            [['good_id', 'count', 'price'], 'required'],
            [['document_id'], 'required', 'except' => self::SCENARIO_BATCH_UPDATE],
            [['document_id', 'good_id', 'count'], 'integer'],
            [['price'], 'number'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documents::class, 'targetAttribute' => ['document_id' => 'id']],
            [['good_id'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::class, 'targetAttribute' => ['good_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/docs', 'ID'),
            'document_id' => Yii::t('app/docs', 'Document ID'),
            'good_id' => Yii::t('app/docs', 'Good ID'),
            'count' => Yii::t('app/docs', 'Count'),
            'price' => Yii::t('app/docs', 'Price'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->document->doc_state && $this->document->doc_type == Documents::DOCTYPE_INPUT) {

            $cons = new Consignments();
            $cons->good_id = $this->good_id;
            $cons->price = $this->price;
            $cons->document_id = $this->document_id;
            $cons->created_at = $this->document->doc_date;
            $cons->save();

            $rem = new Remains();
            $rem->good_id = $this->good_id;
            $rem->count = $this->count;
            $rem->consignment_id = $cons->id;
            $rem->document_id = $this->document_id;
            $rem->created_at = $this->document->doc_date;
            $rem->save();

        } else if ($this->document->doc_state && $this->document->doc_type == Documents::DOCTYPE_OUTPUT) {

            //add new remains by consignments
            $total = $this->count;

            $rems = (new \yii\db\Query())
                ->from('remains r')
                ->select(['consignment_id', 'SUM(count) as remain'])
                ->where(['r.good_id' => $this->good_id])
                ->groupBy(['consignment_id'])
                ->having(['>', 'SUM(count)', 0])
                ->join('LEFT JOIN', 'consignments c', 'c.id = r.consignment_id')
                ->orderBy(['c.created_at' => SORT_ASC])
                ->all();
            foreach ($rems as $rem) {
                if ($total <= 0)
                    break;

                $new_rem = new Remains();
                $new_rem->good_id = $this->good_id;
                $new_rem->count = -min($rem['remain'], $total);
                $new_rem->consignment_id = $rem['consignment_id'];
                $new_rem->document_id = $this->document_id;
                if ($new_rem->save())
                    $total += $new_rem->count;
            }

            if ($total > 0) {
                Yii::$app->session->setFlash('error', 'Not enough remaining consignments for ' . $this->good->name);
                return false;
            }
        }

        return parent::beforeSave($insert);
    }

    public function getDocument()
    {
        return $this->hasOne(Documents::class, ['id' => 'document_id'])->inverseOf('documentRows');
    }

    public function getGood()
    {
        return $this->hasOne(Goods::class, ['id' => 'good_id']);
    }
}
