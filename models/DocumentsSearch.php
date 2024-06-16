<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DocumentsSearch represents the model behind the search form of `app\models\Documents`.
 */
class DocumentsSearch extends Documents
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'doc_state', 'counterparty', 'created_at', 'updated_at'], 'integer'],
            [['doc_type'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Documents::find();

        $viewOper = Yii::$app->user->can('viewOperations');
        $viewOrder = Yii::$app->user->can('viewOrders');
        $viewOwn = Yii::$app->user->can('viewOwnOrders');

        if ($viewOwn) {
            $query->andFilterWhere([
                'author_id' => Yii::$app->user->id,
                'doc_type' => 3,
            ]);
        } else if ($viewOrder && !$viewOper) {
            $query->andFilterWhere([
                'doc_type' => 3,
            ]);
        } else if ($viewOper && !$viewOper) {
            $query->andFilterWhere(['<>', 'doc_type', 3]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'doc_state' => $this->doc_state,
            'counterparty' => $this->counterparty,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'doc_type', $this->doc_type]);

        return $dataProvider;
    }
}
