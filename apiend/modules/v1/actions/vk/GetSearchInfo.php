<?php

namespace apiend\modules\v1\actions\vk;

use apiend\models\Response;
use apiend\modules\v1\actions\BaseAction;
use common\models\vk\Course;
use common\models\vk\Topic;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * 获取专题数据
 */
class GetSearchInfo extends BaseAction
{

    /**
     * 设置接口检验必须的参数
     * @var type 
     */
//    protected $requiredParams = ['keyword', 'search_type'];

    public function run()
    {
        $params = [
          'keyword' => $this->getSecretParam('keyword', ''),
          'search_type' => $this->getSecretParam('search_type', ''),
          'page' => $this->getSecretParam('page', 1),
          'limit' => $this->getSecretParam('limit', 6)
        ];
        /**
         * 返回 专题数据，专题里面的课程数据
         */
        $data = [
            'results' => $this->getSearchResultList($params),
        ];

        return new Response(Response::CODE_COMMON_OK, null, $data);
    }

    /**
     * 获取搜索结果列表
     * @param array $paramse
     * @return array
     */
    private function getSearchResultList($paramse)
    {
        // 名称或关键字
        $name = urldecode(ArrayHelper::getValue($paramse, 'keyword', ''));
        // 搜索类型
        $searchType = urldecode(ArrayHelper::getValue($paramse, 'search_type', ''));
        // 当前页
        $page = ArrayHelper::getValue($paramse, 'page', 1);
        // 返回个数
        $limit = ArrayHelper::getValue($paramse, 'limit', 6);
        
        // 查询课程
        $query = $this->createCourseQuery($name)->addSelect([new Expression("1 search_typ")]);
        // 查询专题
        $topQuery = $this->createTopicQuery($name)->addSelect([new Expression("2 search_typ")]);
        // 合并查询
        $query->union($topQuery);
        // 合并查询结果
        $resultQuery = (new Query())->from(['unionQuery' => $query])
            ->filterWhere(['unionQuery.search_typ' => explode(',', $searchType)]);
        // 查询的总数量
        $totalCount = $resultQuery->count();
        // 分页
        $resultQuery->offset(($page - 1) * $limit)->limit($limit);
        
        return [
            'page' => $page,
            'total_count' => $totalCount,
            'list' => $resultQuery->all(),
        ];
    }
    
    /**
     * 创建获取课程查询
     * @param string $name
     * @return Query
     */
    private function createCourseQuery($name)
    {
        $query = (new Query())
            ->from(['Course' => Course::tableName()])
            ->select([
                'Course.id', 'Course.cover_url AS thumb', 
                'Course.name', 'Course.learning_count'
            ])->where(['is_publish' => 1])
            ->andFilterWhere(['like', 'Course.name', $name]);
        
        return $query;
    }
    
    /**
     * 创建获取专题查询
     * @param string $name
     * @return Query
     */
    private function createTopicQuery($name)
    {
        $query = (new Query())
            ->from(['Topic' => Topic::tableName()])
            ->select([
                'Topic.id', 'Topic.cover_url AS thumb', 
                'Topic.name', 'Topic.learning_count'
            ])->filterWhere(['like', 'Topic.name', $name]);
        
        return $query;
    }
}
