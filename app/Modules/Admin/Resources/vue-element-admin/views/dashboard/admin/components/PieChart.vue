<template>
    <div :class="className" :style="{height:height,width:width}"/>
</template>

<script>
    import echarts from 'echarts';

    require('echarts/theme/macarons') // echarts theme
    import resize from './mixins/resize'

    export default {
        mixins: [resize],
        props: {
            className: {
                type: String,
                default: 'chart'
            },
            width: {
                type: String,
                default: '100%'
            },
            height: {
                type: String,
                default: '300px'
            },
            // 组件传值
            select_type: {
                type: String,
                default: ''
            },
            server_data: {
                type: Object,
                default: {}
            },
        },
        // 监听
        watch: {
            //当 skill 属性的值发生改变时会执行下面的代码
            select_type: function (newValue, oldValue) {
                this.select_type = newValue;

                this.changeData();
            },
            //当 skill 属性的值发生改变时会执行下面的代码
            server_data: function (newValue, oldValue) {
                this.server_data = newValue;

                this.changeData();
            },
        },
        data() {
            return {
                chart: null,

                chart_legend: ['Industries', 'Technology', 'Forex', 'Gold', 'Forecasts'],
                chart_series: [
                    {value: 320, name: 'Industries'},
                    {value: 240, name: 'Technology'},
                    {value: 149, name: 'Forex'},
                    {value: 100, name: 'Gold'},
                    {value: 59, name: 'Forecasts'}
                ],
            }
        },
        mounted() {
            // this.$nextTick(() => {
            //     this.initChart();
            // });
        },
        beforeDestroy() {
            if (!this.chart) {
                return
            }
            this.chart.dispose()
            this.chart = null
        },
        methods: {
            changeData(){
                if (
                    // 磁盘
                    this.select_type == 'disk'
                    ||
                    // 内存
                    this.select_type == 'memory'
                ){

                }
                this.chart_legend = this.server_data[this.select_type] ? this.server_data[this.select_type].chart_legend : this.chart_legend;
                this.chart_series = this.server_data[this.select_type] ? this.server_data[this.select_type].chart_series : this.chart_series;

                this.initChart();
            },
            initChart() {
                this.chart = echarts.init(this.$el, 'macarons')

                this.chart.setOption({
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    legend: {
                        left: 'center',
                        bottom: '10',
                        data: this.chart_legend,
                    },
                    series: [
                        {
                            name: 'WEEKLY WRITE ARTICLES',
                            type: 'pie',
                            // roseType: 'radius',
                            radius: [15, 95],
                            center: ['50%', '38%'],
                            data: this.chart_series,
                            animationEasing: 'cubicInOut',
                            animationDuration: 2600
                        }
                    ]
                })
            }
        }
    }
</script>
