<?php

use common\widgets\charts\ChartAsset;
use frontend\assets\SiteAssets;
use yii\web\View;

/* @var $this View */

$this->title = Yii::$app->name;

SiteAssets::register($this);
ChartAsset::register($this);

?>

<div class="site-index">
    <!-- 宣传 -->
    <div class="banner">
        <div id="carousel" class="carousel slide">
            <?php if (count($banners) <= 0): ?>
            <div class="item">
                <img src="/imgs/banner/default.jpg">
            </div>
            <?php endif; ?>
            <div class="carousel-inner" role="listbox">
            <?php foreach ($banners as $index => $model): ?>
            <div class="item <?= $index == 0 ? 'active' : '' ?>">
                <div class="img-box" style="background:url('<?= $model->path ?>') no-repeat center top"></div>
                <?php if ($model->type == 2): ?>
                <!-- 如果是视频，即显示播放按钮 -->
                <div class="play-btn-box">
                    <img class="play-btn" src="/imgs/banner/play_icon.png" data-href="<?= $model->link ?>"/>
                </div>
                <?php endif; ?>
                <div class="carousel-caption" style="display:none;"></div>
            </div>
            <?php endforeach; ?>
            </div>
            <!-- 左右切换 -->
            <?php if (count($banners) > 1): ?>
            <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            <?php endif; ?>
        </div>
        <!-- 弹出播放视频 -->
        <div class="video-box">
            <video class="container" controls="controls"></video>
            <img class="close-btn" src="/imgs/banner/close_icon.png"/>
        </div>
    </div>

    <!-- 内容 -->
    <div class="container">

    </div>
</div>

<?php

$js = <<<JS
        
    //初始化轮播
    $('.carousel').carousel({
        interval: 3000
    });
    /* 播放按钮事件 */
    $('.carousel .play-btn').on('click',function(){
        playVideo($(this).attr('data-href'),true);
    });
    $('.carousel .play-btn').hover(
        function () {
            $('.carousel .active .img-box').addClass("blur");
        },
        function () {
            $('.carousel .active .img-box').removeClass("blur");
        }
    );
    /* 侦听视频播放完成事件 */
    $('.banner .video-box video').on('ended',function(){
        closeVideo();
    });
     
    /* 关闭视频按钮 */
    var close_btn_delay_id; 
    $('.banner .video-box .close-btn').on('click',function(){
        closeVideo();
    });
    /* 
     * 播放视频
     * @path {String} 视频路径
     * @autoplay {Boolean} 自动播放
     */
    function playVideo(path,autoplay){
        $('.carousel').carousel('pause');
        $('.banner .video-box video').hide();
        $('.banner .video-box').fadeIn(400,function(){
            $('.banner .video-box video').fadeIn(500);
            /* 鼠标移动显示关闭按钮 */
            $('.banner .video-box').mousemove(function(){
                clearTimeout(close_btn_delay_id);
                $('.banner .video-box .close-btn').fadeIn();
                close_btn_delay_id = setTimeout(function(){
                    $('.banner .video-box .close-btn').fadeOut();
                },2500);
            });;
        });
        $('.banner').css('height','562px');
        $('.banner .video-box video').attr('src', path);
        if(autoplay){
            $('.banner .video-box video').get(0).play();
        }
    }
    /* 退出视频播放 */
    function closeVideo(){
        clearTimeout(close_btn_delay_id);
        $('.banner .video-box video').get(0).pause();
        $('.banner .video-box').fadeOut(400);
        $('.banner').css('height','400px');
        $('.banner .video-box').off('mousemove');
        $('.banner .video-box .close-btn').fadeOut();
        $('.carousel').carousel('cycle')
    }
JS;
$this->registerJs($js, View::POS_READY);
?> 