<?php

namespace app\modules\admin\models\searchModels;

use app\modules\admin\models\Products;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * KonfsSearch represents the model behind the search form of `app\modules\main\models\Konfs`.
 */
class ProductsSearch extends Model
{
    public $id;
    public $konf_id;
    public $konf_version;
    public $redaction_num;
    public $platform_version;
    public $txt;
    public $htm;
    public $xml;
    public $zip;

    public function __construct($konf_id)
    {
        parent::__construct();
        $this->konf_id = $konf_id;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['konf_version', 'redaction_num', 'platform_version'], 'safe'],
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
        $query = Products::find()->where(['konf_id' => $this->konf_id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere(['like', 'konf_version', $this->konf_version])
            ->andFilterWhere(['like', 'redaction_num', $this->redaction_num])
            ->andFilterWhere(['like', 'platform_version', $this->platform_version]);

        return $dataProvider;
    }
}
