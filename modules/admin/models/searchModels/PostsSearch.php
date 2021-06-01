<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\Posts;
use Yii;
use app\modules\user\constants\Consts;

/**
 * PostsSearch represents the model behind the search form of `app\modules\main\models\Posts`.
 */
class PostsSearch extends Posts
{
    public $date_from_upload;
    public $date_to_upload;

    public $date_from_create;
    public $date_to_create;

    public $author_name;

    public $front;
    public $isArchive;

    /**
     * {@inheritdoc}
     */

    /* public function __construct($front = false)
    {
        $this->front = $front;
    } */

    public function rules()
    {
        return [
            [['id', 'country_id', 'created_at', 'updated_at', 'upload_at', 'author_id', 'show_it'], 'integer'],
            [['main_theme', 'sub_theme', 'body', Consts::STATUS_ATTR], 'safe'],
            [['author_name'], 'string', 'max' => 40],
            [['author_name'], 'string', 'min' => 3],
            [['date_from_upload', 'date_to_upload', 'date_from_create', 'date_to_create'], 'date', 'format' => 'php:d.m.Y'],
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
        if ($this->front and Yii::$app->user->isGuest) {
            $query = $this->frontQuery();
        }

        if ($this->front and !Yii::$app->user->isGuest) {
            $query = $this->frontQueryLogin();
        }

        if (!$this->front) {
            $query = $this->adminQuery();
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'updated_at' => SORT_DESC
                    ],
                ],
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 9,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'upload_at' => SORT_DESC
                    ],
                ],
            ]);
        }

        // add conditions that should always apply here

        /*  $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'upload_at' => SORT_DESC
                ],
            ],
        ]); */

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'country_id' => $this->country_id,
            //'tour_posts.created_at' => $this->tour_posts.created_at,
            'updated_at' => $this->updated_at,
            //'upload_at' => $this->upload_at,
            'author_id' => $this->author_id,
            'show_it' => $this->show_it,
        ]);

        $query->andFilterWhere(['like', 'main_theme', $this->main_theme])
            ->andFilterWhere(['like', 'sub_theme', $this->sub_theme])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'username', $this->author_name])
            ->andFilterWhere(['>=', 'upload_at', $this->date_from_upload ? strtotime($this->date_from_upload . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'upload_at', $this->date_to_upload ? strtotime($this->date_to_upload . ' 23:59:59') : null])
            ->andFilterWhere(['>=', 'tour_posts.updated_at', $this->date_from_create ? strtotime($this->date_from_create . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'tour_posts.updated_at', $this->date_to_create ? strtotime($this->date_to_create . ' 23:59:59') : null]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'author_name' => 'Автор',
        ];
    }

    protected function frontQuery()          // запрос для пользовательской части (фронта), без отображения "просмотров", для не залогиненных
    {
        $query = Posts::find()
            ->select([
                'tour_posts.id',
                'country_id',
                'upload_at',
                'tour_posts.author_id',
                'main_theme',
                'sub_theme',
                'tour_posts.created_at',
            ])->joinWith([
                'country',
                'author' => function ($query) {
                    $query->select(['id', 'username']);
                },
            ])->where(['show_it' => true, Consts::STATUS_ATTR => Consts::FLOW_ADMIN_PUBLISHED])->andWhere(['<=', 'upload_at', strtotime("now")])
            ->orderBy(['upload_at' => SORT_DESC]);

        return $query;
    }

    protected function frontQueryLogin()          // запрос для пользовательской части (фронта), с отображениеи "просмотров", для залогиненных
    {
        $query = Posts::find()
            ->select([
                'tour_posts.id',
                'country_id',
                'upload_at',
                'tour_posts.author_id',
                'main_theme',
                'sub_theme',
                'tour_posts.created_at',
            ])->joinWith([
                'country',
                'author' => function ($query) {
                    $query->select(['id', 'username']);
                },
                'views',
                'likes',
            ])->where(['show_it' => true, Consts::STATUS_ATTR => Consts::FLOW_ADMIN_PUBLISHED])->andWhere(['<=', 'upload_at', strtotime("now")])
            ->orderBy(['upload_at' => SORT_DESC]);

        //debug($query);
        return $query;
    }

    protected function adminQuery()          // запрос для админки
    {
        $query = Posts::find();

        if ($this->isArchive) {
            $query = $query->where(['post_status' => Consts::FLOW_ARCHIVE]);
        }

        if (!$this->isArchive) {
            $query = $query->where(['!=', 'post_status', Consts::FLOW_ARCHIVE]);
        }

        return $query;
    }
}
