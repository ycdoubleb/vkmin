/**
 * 柱状图 横向显示
 * @param {type} win
 * @param {type} $
 * @returns {undefined}
 */
(function (win, $) {

    win.webservice = win.webservice || {};

    var BtnLoader = function () {};
    /**
     * 默认配置
     */
    BtnLoader.config = {
//        dataProvideFun: 'getSelecteDatas' //数据来源提供方法
    };

    /**
     * 初始配置
     * @param {object} config
     * @returns {void}
     */
    BtnLoader.init = function (config) {
        BtnLoader.config = $.extend(config);
    }

    /**
     * 设置加载状态
     * @param {jQueryDom} $dom
     * @param {boolen} bo
     * @returns {void}
     */
    BtnLoader.setLoading = function ($dom, bo) {
        if (bo) {
            if ($dom.find('span.icon').length == 0) {
                $dom.prepend($('<span class="icon hidden"><i class="glyphicon glyphicon-refresh"></i></span>'));
            }
            $dom.find('span.icon').removeClass('hidden');
            $dom.addClass('disabled');
        } else {
            $dom.find('span.icon').addClass('hidden');
            $dom.removeClass('disabled');
        }
    }

    /**
     * 
     * @param {jQuery.dom} $dom
     * @param {string} url 
     * @param {object} data
     * @returns {void}
     */
    /**
     * 提交数据
     * @param {object} config {dom,url,data,success,error,complete,method,showLoading}
     *  {jQuery.dom} $dom
     *  {string} url 
     *  {object} data
     *  {Function} success (data)        成功时回调
     *  {Function} error (data)          失败时回调，如程序出错后，无法正常返回结果时
     *  {Function} complete ()           无论成功失败都会调
     * @returns {undefined}
     */
    BtnLoader.submit = function (config) {
        var $dom = config.dom,
                url = config.url,
                data = config.data,
                success = config.success,
                error = config.error,
                complete = config.complete,
                showLoading = config.showLoading,
                method = config.method == 'GET' ? 'GET' : 'POST';

        var _this = $dom;
        //设置加载中...
        if (showLoading) {
            BtnLoader.setLoading(_this, true);
        }
        
        $.ajax({
            url: url,
            type: method == 'GET' ? 'GET' : 'POST',
            data: data,
            success: function (data, textStatus) {
                //设置成功回调函数，函数返回true，执行默认操作
                if (!success || (success && success(data))) {
                    if (data.code == '0') {
                        //成功
                        //$.notify({message: '操作成功！'}, {type: 'success'});
                        //重新刷新页面
                        location.reload();
                    } else {
                        //错误
                        $.notify({message: '操作失败！\n' + data.msg}, {type: 'danger'});
                    }
                }
            },
            error: function (e) {
                if (!error || (error && error(e))) {
                    $.notify({message: '网络错误！'}, {type: 'danger'});
                }
            },
            complete: function () {
                if (showLoading) {
                    BtnLoader.setLoading(_this, false);
                }
                if (!complete || (complete && complete())) {
                    $dom.parents('.modal').modal('hide');
                }
            }
        });
    };

    win.webservice.BtnLoader = BtnLoader;

    /**
     * 初始
     */
    $(win).on('load', function () {
        $('button[data-toggle=btnloader],a[data-toggle=btnloader]').on('click', function () {
            var datas = BtnLoader.config['dataProvideFun']();
            if (datas) {
                //检查是否需要强提示
                var confirm_str = $(this).attr('data-confirm');
                if (!confirm_str || confirm(confirm_str)) {
                    BtnLoader.submit({
                        dom: $(this),
                        url: $(this).attr('data-url'),
                        data: datas
                    });
                }
            } else {
                $.notify({message: '请先选择！'}, {type: 'warning'});
            }
            return false;
        });
    });
})(window, jQuery);