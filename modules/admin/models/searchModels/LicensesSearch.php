<?php

namespace app\modules\admin\models\searchModels;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Licenses;

/**
 * LicensesSearch represents the model behind the search form of `app\modules\admin\models\Licenses`.
 */
class LicensesSearch extends Licenses
{
    public $user;
    public $konf;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'konf_id', 'created_at', 'updated_at', 'life_time'], 'integer'],
            [['login', 'password_hash', 'user', 'konf'], 'safe'],
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
        $query = Licenses::find();
        $query->joinWith(['user', 'konf']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['user'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['konf'] = [
            'asc' => ['konf.conf_name' => SORT_ASC],
            'desc' => ['konf.conf_name' => SORT_DESC],
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
            'user_id' => $this->user_id,
            'konf_id' => $this->konf_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'life_time' => $this->life_time,
        ]);

        $query->andFilterWhere(['like', 'login', $this->login])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'username', $this->user])
            ->andFilterWhere(['like', 'conf_name', $this->konf]);
        return $dataProvider;
    }
}
