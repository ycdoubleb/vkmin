<?php

use common\models\AdminUserr;
use common\modules\rbac\components\Helper;
use common\widgets\Menu;

/* @var $user AdminUserr */
?>
<aside class="main-sidebar">
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <?php if(!Yii::$app->user->isGuest): ?>
        <!--
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $user->avatar; ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= $user->nickname ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div> -->
        <?php endif; ?>

        <!-- search form 
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>-->
        <!-- /.search form -->
        <?php 
            $menuItems = [['label' => '导航菜单', 'options' => ['class' => 'header']]];
            if(Yii::$app->user->isGuest){
                $menuItems []= ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest];
            }else{
                $menuItems = array_merge($menuItems, [
                    ['label' => '清除缓存', 'icon' => 'eraser', 'url' => ['/system_admin/cache/']],
                    [
                        'label' => '系统',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '配置管理', 'icon' => 'circle-o', 'url' => ['/system_admin/config/index'],],
                            ['label' => '日常任务', 'icon' => 'circle-o', 'url' => ['/system_admin/crontab/index'],],
                            ['label' => '数据库备份', 'icon' => 'database', 'url' => ['/system_admin/db-backup/index']],
//                            ['label' => 'redis缓存管理', 'icon' => 'circle-o', 'url' => ['/rediscache_admin/acl/index']],
                            ['label' => 'Banner管理', 'icon' => 'circle-o', 'url' => ['/system_admin/banner/index']],
                            ['label' => '上传文件管理', 'icon' => 'circle-o', 'url' => ['/system_admin/uploadfile/index']]
                        ],
                    ],
                    [
                        'label' => '权限与组织管理',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            ['label' => '用户列表', 'icon' => 'circle-o', 'url' => ['/user_admin/default/index'],],
                            ['label' => '用户角色', 'icon' => 'circle-o', 'url' => ['/rbac/user-role/index'],],
                            ['label' => '角色列表', 'icon' => 'circle-o', 'url' => ['/rbac/role/index'],],
                            ['label' => '权限列表', 'icon' => 'circle-o', 'url' => ['/rbac/permission/index'],],
                            ['label' => '路由列表', 'icon' => 'circle-o', 'url' => ['/rbac/route/index'],],
                            ['label' => '分组列表', 'icon' => 'circle-o', 'url' => ['/rbac/auth-group/index'],],
                        ],
                    ],
                    [
                        'label' => '平台管理',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            
                        ],
                    ],
                    [
                        'label' => '平台配置',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                           
                        ],
                    ],
                    [
                        'label' => '统计',
                        'icon' => 'bars',
                        'url' => '#',
                        'items' => [
                            
                        ]
                    ],
                ]);
                
                foreach($menuItems as &$menuItem){
                    if(isset($menuItem['items'])){
                        $visible = false;
                        foreach($menuItem['items'] as &$item){
                            $item['visible'] = Helper::checkRoute($item['url'][0]);
                            if($item['visible']){
                                $visible = true;
                            }
                        }
                        unset($item);
                        $menuItem['visible'] = $visible;
                    }else if(isset($menuItem['url'])){
                        $menuItem['visible'] = Helper::checkRoute($menuItem['url'][0]);;
                    }
                }
            }
        ?>
        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree  sidebar-open sidebar-mini', 'data-widget'=> 'tree'],
                'items' => $menuItems,
            ]
        ); ?>

    </section>

</aside>
