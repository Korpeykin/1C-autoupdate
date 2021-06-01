<?php

namespace app\modules\admin\models\searchModels;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Targets;

/**
 * TargetsSearch represents the model behind the search form of `app\modules\admin\models\Targets`.
 */
class TargetsSearch extends Targets
{
    public $konf_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'konf_id'], 'integer'],
            [['target', 'konf_name'], 'safe'],
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
        $query = Targets::find();
        $query->joinWith(['konfs']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        $dataProvider->sort->attributes['konfs'] = [
            'asc' => ['conf_name' => SORT_ASC],
            'desc' => ['conf_name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'target', $this->target]);
        $query->andFilterWhere(['like', 'conf_name', $this->konf_name]);

        return $dataProvider;
    }
}
