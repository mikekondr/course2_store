<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documents".
 *
 * @property int $id
 * @property string $doc_type 1-input, 2-output, 3 - order
 * @property int $doc_state True-active, False-draft
 * @property int $doc_date
 * @property int $author_id
 * @property int $counterparty
 * @property int $created_at
 * @property int $updated_at
 *
 * @property DocumentRows[] $documentRows
 * @property Users $author
 * @property string $date
 * @property string $doc_name
 */
class Documents extends ActiveRecord
{
    const DOCTYPE_INPUT = 1;
    const DOCTYPE_OUTPUT = 2;
    const DOCTYPE_ORDER = 3;
    const DOCTYPE_NAMES = [
        self::DOCTYPE_INPUT => "Input",
        self::DOCTYPE_OUTPUT => "Output",
        self::DOCTYPE_ORDER => "Order",
    ];
    const DOCSTATE_NAMES = [
        True => "Active",
        False => "Draft",
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doc_type', 'doc_date'], 'required'],
            [['date', 'doc_name'], 'safe'],
            [['doc_state', 'counterparty'], 'integer'],
            [['doc_type'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/docs', 'ID'),
            'doc_type' => Yii::t('app/docs', 'Doc Type'),
            'doc_state' => Yii::t('app/docs', 'Doc State'),
            'doc_date' => Yii::t('app/docs', 'Doc Date'),
            'date' => Yii::t('app/docs', 'Doc Date'),
            'author_id' => Yii::t('app/docs', 'Author'),
            'counterparty' => Yii::t('app/docs', 'Counterparty'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function transactions()
    {
        return [
            \yii\base\Model::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'author_id',
                ],
                'value' => function ($event) {
                    return Yii::$app->user->id;
                },
            ],
        ];
    }

    private function removeRelatedData()
    {
        if ($this->doc_type == self::DOCTYPE_INPUT) {
            //remove old remains and consignments
            Remains::deleteAll(['document_id' => $this->id]);
            Consignments::deleteAll(['document_id' => $this->id]);
        } else if ($this->doc_type == self::DOCTYPE_OUTPUT) {
            //remove old remains
            Remains::deleteAll(['document_id' => $this->id]);
        }

        return true;
    }

    public function beforeSave($insert)
    {
        return $this->removeRelatedData() && parent::beforeSave($insert);
    }

    public function beforeDelete()
    {

        return $this->removeRelatedData() && parent::beforeDelete();
    }

    /**
     * Gets query for [[DocumentRows]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentRows()
    {
        return $this->hasMany(DocumentRows::class, ['document_id' => 'id'])->inverseOf('document');
    }

    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['id' => 'author_id']);
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDateTime($this->doc_date);
    }

    public function setDate($date)
    {
        $this->doc_date = Yii::$app->formatter->asTimestamp($date);
    }

    public function getDoc_Name()
    {
        return Yii::t('app/docs', self::DOCTYPE_NAMES[$this->doc_type]) . ' № ' . $this->id;
    }

    public static function getDocumentsTypes(){
        $res = [];
        foreach (self::DOCTYPE_NAMES as $key => $value) {
            if ($key == self::DOCTYPE_ORDER && !(
                Yii::$app->user->can('editOrders') || Yii::$app->user->can('editOwnOrders')
                )) {
                continue;
            }
            $res[$key] = Yii::t('app/docs', $value);
        }
        return $res;
    }

    public static function getDocumentsStates(){
        $res = [];
        foreach (self::DOCSTATE_NAMES as $key => $value) {
            $res[$key] = Yii::t('app/docs', $value);
        }
        return $res;
    }

}
