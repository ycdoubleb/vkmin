/**
 * 柱状图 横向显示
 * @param {type} win
 * @param {type} $
 * @returns {undefined}
 */
(function (win, $) {

    win.wskcharts = win.wskcharts || {};
    /**
     * 曲线图
     * @param {Object} config   配置
     * @param {Dom} dom         chart的容器
     * @param {Array} datas     [[name:string,value:[日期,value]],[],...]
     * @returns
     */
    var LineStackChart = function (config, dom, datas) {
        this.config = $.extend({
            title: '', //大标题
            'series.name':'',//值显示
            "axisLabel.formatter":"{value}",//y轴显示格式
        }, config);
        this.init(dom, datas);
        this.reflashChart(this.config.title, datas);
        var _this = this;
        $(win).resize(function () {
            _this.chart.resize();
        });
    }
    var p = LineStackChart.prototype;
    /** 制图画板 */
    p.canvas = null;
    /** 图表 */
    p.chart = null;
    /** 图表选项 */
    p.chartOptions = null;

    p.init = function (dom, datas) {
        this.canvas = dom;
        //重新计算图标的高度，高度由显示的数据相关

        this.chart = echarts.init(dom);
        this.chartOptions = {
            tooltip: {
                show: true,
                trigger: 'axis',
                axisPointer: {
                    animation: false
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                splitLine: {
                    show: false
                }
            },
            yAxis: {
                type: 'value',
                boundaryGap: [0, '100%'],
                splitLine: {
                    show: true
                },
                axisLabel: {
                    formatter: this.config['axisLabel.formatter']
                }
            },
            series: [{
                    name: this.config['series.name'],
                    type: 'line',
                    showSymbol: true,
                    symbolSize: 6,
                    hoverAnimation: false,
                    data: datas
                }]
        };

    };

    /**
     * 刷新图标
     * @param {Array} data 出错步骤数据
     * @returns 
     */
    p.reflashChart = function (data) {

        var keys = [];
        var values = [];

        for (var i = 0, len = data.length; i < len; i++)
        {
            keys.push(data[i]["name"]);
            values.push(data[i]["value"]);
        }

        this.chartOptions.yAxis.data = keys;
        this.chartOptions.series[0].data = values;
        this.chart.setOption(this.chartOptions, true);
    }

    win.wskcharts.LineStackChart = LineStackChart;
})(window, jQuery);