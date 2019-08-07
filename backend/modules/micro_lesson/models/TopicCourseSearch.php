<?php

namespace backend\modules\micro_lesson\models;

use common\models\vk\Course;
use common\models\vk\TopicCourse;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * TopicCourseSearch represents the model behind the search form of `common\models\vk\TopicCourse`.
 */
class TopicCourseSearch extends TopicCourse
{
    /**
     * 课程名
     * @var string 
     */
    public $course_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'topic_id', 'course_id', 'is_del'], 'integer'],
            [['course_name'], 'safe'],
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
        $query = TopicCourse::find();
        
        $query->leftJoin(['Course' => Course::tableName()], 'Course.id = course_id');

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
            'topic_id' => $this->topic_id,
            'is_del' => 0,
        ]);
        
        // 模糊查询
        $query->andFilterWhere(['like', 'Course.name', $this->course_name]);

        return $dataProvider;
    }
}
