<?php

namespace backend\modules\micro_lesson\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\vk\Course;

/**
 * CourseSearch represents the model behind the search form of `common\models\vk\Course`.
 */
class CourseSearch extends Course
{
 
    /**
     * 关键字
     * @var string 
     */
    public $keyword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'suggest_time', 'learning_count', 'play_count', 'favorite_count', 'like_count', 'comment_count', 'is_recommend', 'is_publish', 'created_at', 'updated_at'], 'integer'],
            [['keyword', 'name', 'cover_url', 'introduction', 'url', 'teacher_name', 'teacher_avatar_url'], 'safe'],
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
        $query = Course::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'id'
        ]);

        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // 条件查询
        $query->andFilterWhere([
            'is_recommend' => $this->is_recommend,
            'is_publish' => $this->is_publish
        ]);
        // 模糊查询
        $query->andFilterWhere([
            'OR', 
            ['like', 'name', $this->keyword],
            ['like', 'teacher_name', $this->keyword]
        ]);

        return $dataProvider;
    }
}
