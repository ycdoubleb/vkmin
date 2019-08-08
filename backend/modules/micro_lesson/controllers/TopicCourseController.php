<?php

namespace backend\modules\micro_lesson\controllers;

use backend\modules\micro_lesson\models\CourseSearch;
use backend\modules\micro_lesson\models\TopicCourseSearch;
use common\models\api\ApiResponse;
use common\models\vk\Topic;
use common\models\vk\TopicCourse;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TopicCourseController implements the CRUD actions for TopicCourse model.
 */
class TopicCourseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TopicCourse models.
     * @return mixed
     */
    public function actionIndex()
    {
        // 所有参数
        $params = Yii::$app->request->queryParams;
        // 专题id
        $topicId = ArrayHelper::getValue($params, 'topic_id');
        
        $topicModel = $this->findTopicModel($topicId);
        $searchModel = new TopicCourseSearch(['topic_id' => $topicId]);
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'topicModel' => $topicModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 展示课程列表
     * @param int $topic_id
     * @return mixed
     */
    public function actionCourseList($topic_id)
    {
        $searchModel = new CourseSearch(['is_publish' => 1]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->renderAjax('____course_list', [
            'topicId' => $topic_id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 添加课程
     * @param int $topic_id
     * @param int $course_id
     * @return mixed
     */
    public function actionAdd($topic_id)
    {
        if(Yii::$app->request->isPost){
            // 课程id
            $courseIds = ArrayHelper::getValue(Yii::$app->request->post(), 'course_id');
            
            $this->saveTopicCourse($topic_id, $courseIds);
            
        }
        
        $this->redirect(['index', 'topic_id' => $topic_id]);
    }
    
    /**
     * 移出课程
     * @param int $topic_id
     * @param string $ids
     * @return object to index
     */
    public function actionMoveout($topic_id, $ids)
    {
        $countNum = TopicCourse::updateAll(['is_del' => 1], ['id' => explode(',', $ids)]);
        return $this->redirect(['index', 'topic_id' => $topic_id]);
    }
    
    /**
     * 保存专题课程
     * @param int $topic_id
     * @param array $course_ids
     */
    protected function saveTopicCourse($topic_id, $course_ids)
    {
        // 查询存在的课程
        $topicCourse = TopicCourse::findAll(['topic_id' => $topic_id, 'course_id' => $course_ids]);
        // 获取存在的课程id
        $existCourseIds = ArrayHelper::getColumn($topicCourse, 'course_id');
        $rows = [];  
        foreach ($course_ids as $id) {
            if(!in_array($id, $existCourseIds)){
                $rows[] = [
                    'topic_id' => $topic_id,
                    'course_id' => $id
                ];
            }
        }
        // 非空插入
        if(!empty($rows)){
            Yii::$app->db->createCommand()
                ->batchInsert(TopicCourse::tableName(), array_keys($rows[0]),$rows)
                ->execute();
        }
        // 非空更新已存在的数据
        if(!empty($existCourseIds)){
            TopicCourse::updateAll(['is_del' => 0], ['topic_id' => $topic_id, 'course_id' => $existCourseIds]);
        }
    }

    /**
     * Finds the Topic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Topic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findTopicModel($id)
    {
        if (($model = Topic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
