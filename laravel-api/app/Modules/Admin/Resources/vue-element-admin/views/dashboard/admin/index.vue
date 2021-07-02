<template>
    <div class="dashboard-editor-container">
        <github-corner class="github-corner"/>

        <panel-group @handleSetLineChartData="handleSetLineChartData" :data="data"/>

        <!-- 数据加载完毕之后，自动展示 -->
        <h2 :style="server_data.php_os ? 'display:block' : 'display:none'"> 服务器状态信息 </h2>
        <el-row :gutter="32" :style="server_data.php_os ? 'display:block' : 'display:none'">
            <el-col :xs="24" :sm="24" :lg="8">
                <el-card shadow="never">
                    <div slot="header">
                        <span>CPU使用率（%）</span>
                        <p v-if="server_data.system == 1">
                            <small style="color: red;"> windows系统，CPU获取极为不准确！ </small>
                        </p>
                    </div>
                    <pie-chart select_type="cpu" :server_data="server_data"/>
                </el-card>
            </el-col>
            <el-col :xs="24" :sm="24" :lg="8">
                <el-card shadow="never">
                    <div slot="header">
                        <span>磁盘使用率（G）</span>
                        <p v-if="server_data.disk_info">
                            <span>共计：</span>
                            <small style="color: green;"> {{ server_data.disk_info.total }} G </small>
                        </p>
                    </div>
                    <pie-chart select_type="disk" :server_data="server_data"/>
                </el-card>
            </el-col>
            <el-col :xs="24" :sm="24" :lg="8">
                <el-card shadow="never">
                    <div slot="header">
                        <span>内存使用率（MB）</span>
                        <p v-if="server_data.memory_info">
                            <span>共计：</span>
                            <small style="color: blue;"> {{ server_data.memory_info.total }} MB </small>
                        </p>
                    </div>
                    <pie-chart select_type="memory" :server_data="server_data"/>
                </el-card>
            </el-col>
        </el-row>

        <el-row :gutter="32" style="margin-bottom:32px;">
            <el-col :xs="24" :sm="24" :lg="10">
                <el-card shadow="never">
                    <div slot="header">
                        <span>技能</span>
                    </div>
                    <box-card :skill="skill"/>
                </el-card>
            </el-col>
            <!--
            <el-col :xs="24" :sm="24" :lg="8">
                <div class="chart-wrapper">
                    <bar-chart/>
                </div>
            </el-col>
            -->
            <el-col :xs="24" :sm="24" :lg="14">
                <el-card shadow="never">
                    <div slot="header">
                        <span>版本历史记录</span>
                    </div>
                    <timeline :logs="version_logs"/>
                </el-card>
            </el-col>
        </el-row>

        <el-row :gutter="32">
            <el-col :xs="24" :sm="24" :lg="24">
                <el-card shadow="never">
                    <div slot="header">
                        <span>请求日志统计</span>
                    </div>
                    <chart height="400px" width="100%" />
                </el-card>
            </el-col>
        </el-row>

        <!--
        <el-row style="background:#fff;padding:16px 16px 0;">
            <line-chart :chart-data="lineChartData"/>
        </el-row>
        -->
    </div>
</template>

<script>
    import GithubCorner from '@/components/GithubCorner';
    import PanelGroup from './components/PanelGroup';
    import LineChart from './components/LineChart';
    import BarChart from './components/BarChart';
    import BoxCard from './components/BoxCard';
    import Chart from './components/LineMarker';
    import Timeline from './components/Timeline';
    import PieChart from './components/PieChart';
    import {statistics, versionLogs, getServerStatus} from "@/api/indexs";

    const lineChartData = {
        newVisitis: {
            expectedData: [100, 120, 161, 134, 105, 160, 165],
            actualData: [120, 82, 91, 154, 162, 140, 145]
        },
        messages: {
            expectedData: [200, 192, 120, 144, 160, 130, 140],
            actualData: [180, 160, 151, 106, 145, 150, 130]
        },
        purchases: {
            expectedData: [80, 100, 121, 104, 105, 90, 100],
            actualData: [120, 90, 100, 138, 142, 130, 130]
        },
        four: {
            expectedData: [130, 140, 141, 142, 145, 150, 160],
            actualData: [120, 82, 91, 154, 162, 140, 130]
        }
    };

    export default {
        name: 'DashboardAdmin',
        components: {
            GithubCorner,
            PanelGroup,
            LineChart,
            BarChart,
            BoxCard,
            Chart,
            Timeline,
            PieChart,
        },
        // 监听
        watch: {
            //当  属性的值发生改变时会执行下面的代码
            server_data: function (newValue, oldValue) {
                this.server_data = newValue;
            },
        },
        data() {
            return {
                lineChartData: lineChartData.newVisitis,
                skill:[], // 技能组
                data:{},
                version_logs: [], // 版本记录
                server_data: {}, // 服务器状态信息
            }
        },
        created() {
            // 首页 - 统计信息
            this.statistics();

            // 版本记录
            this.versionLogs();

            // 服务器状态信息
            setTimeout(() => {
                this.getServerStatus();
                this.timedRequestServerStatus();
            }, 3000);
        },
        methods: {
            // 首页 - 统计信息
            async statistics() {
                this.listLoading = true;
                const {data} = await statistics();
                this.data = data;

                // 技能
                this.skill = data.skill;

                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            // 版本记录
            async versionLogs(){
                const {data} = await versionLogs();
                this.version_logs = data;
            },
            // 服务器状态信息
            async getServerStatus(){
                const {data} = await getServerStatus();
                this.server_data = data;
            },
            // 定时：服务器状态信息
            timedRequestServerStatus(){
                // 定时多少秒触发一次：默认60秒【服务器状态信息】
                window.setInterval(() => {
                    setTimeout(this.getServerStatus, 0);
                }, 1000 * 60);
            },
            handleSetLineChartData(type) {
                this.lineChartData = lineChartData[type];
            },
        }
    }
</script>

<style lang="scss" scoped>
    .dashboard-editor-container {
        padding: 32px;
        background-color: rgb(240, 242, 245);
        position: relative;

        .el-row{
            margin-bottom:30px;
        }

        .github-corner {
            position: absolute;
            top: 0px;
            border: 0;
            right: 0;
        }

        .chart-wrapper {
            background: #fff;
            padding: 16px 16px 0;
            margin-bottom: 32px;
        }

        .chart-container{
            position: relative;
            width: 100%;
            height: calc(100vh - 100px);
        }
    }

    @media (max-width: 1024px) {
        .chart-wrapper {
            padding: 8px;
        }
    }
</style>
