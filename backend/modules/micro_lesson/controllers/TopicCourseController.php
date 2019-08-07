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
        // 返回json格式
        \Yii::$app->response->format = 'json';

        try {
            if(Yii::$app->request->isPost){
                // 课程id
                $courseId = ArrayHelper::getValue(Yii::$app->request->post(), 'course_id');

                $model = $this->findModel($topic_id, $courseId);
                // 判断是否为新建
                if(!$model->isNewRecord){
                    $model->is_del = 0;
                }

                if($model->save()){
                    return new ApiResponse(ApiResponse::CODE_COMMON_OK, null, $model->toArray());
                }
            }
            
        } catch (Exception $ex) {
            return new ApiResponse(ApiResponse::CODE_COMMON_SAVE_DB_FAIL, $ex->getMessage(), $ex->getTraceAsString());
        }
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
        if($countNum > 0){
            return $this->redirect(['index', 'topic_id' => $topic_id]);
        }
    }
    
    /**
     * Finds the TopicCourse model based on its primary key value.
     * @param integer $id
     * @param integer $course_id
     * @return TopicCourse the loaded model
     */
    protected function findModel($topic_id, $course_id)
    {
        if (($model = TopicCourse::findOne(['topic_id' => $topic_id, 'course_id' => $course_id])) !== null) {
            return $model;
        }else{
            return new TopicCourse(['topic_id' => $topic_id, 'course_id' => $course_id]);
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
