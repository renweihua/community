<template>
    <el-card class="box-card-component" style="margin-left:8px;">
        <div slot="header" class="box-card-header">
            <img src="https://wpimg.wallstcn.com/e7d23d71-cf19-4b90-a1cc-f56af8c0903d.png">
        </div>
        <div style="position:relative;">
            <pan-thumb :image="avatar" class="panThumb"/>
            <mallki class-name="mallki-text" text="小丑路人"/>
            <!-- 技能百分比 -->
            <div class="progress-item" v-for="(item, key) in skill" :style="key == 0 ? 'padding-top:35px;' : ''">
                <span>{{item.name}}</span>
                <el-progress v-if="item.value == 100" :percentage="item.value" status="success"/>
                <el-progress v-else :percentage="item.value"/>
            </div>
        </div>
    </el-card>
</template>

<script>
    import {mapGetters} from 'vuex'
    import PanThumb from '@/components/PanThumb'
    import Mallki from '@/components/TextHoverEffect/Mallki'

    export default {
        components: {PanThumb, Mallki},
        filters: {
            statusFilter(status) {
                const statusMap = {
                    success: 'success',
                    pending: 'danger'
                }
                return statusMap[status]
            }
        },
        // 组件传值
        props       : {
            skill   : {
                type    : Array,
                default : []
            }
        },
        // 监听
        watch: {
            //当 skill 属性的值发生改变时会执行下面的代码
            skill: function (newValue, oldValue) {
                this.skill = newValue;
            }
        },
        data() {
            return {
                statisticsData: {
                    article_count: 1024,
                    pageviews_count: 1024
                }
            }
        },
        created() {

        },
        computed: {
            ...mapGetters([
                'name',
                'avatar',
                'roles'
            ])
        }
    }
</script>

<style lang="scss">
    .box-card-component {
        .el-card__header {
            padding: 0px !important;
        }
    }
</style>
<style lang="scss" scoped>
    .box-card-component {
        .box-card-header {
            position: relative;
            height: 220px;

            img {
                width: 100%;
                height: 100%;
                transition: all 0.2s linear;

                &:hover {
                    transform: scale(1.1, 1.1);
                    filter: contrast(130%);
                }
            }
        }

        .mallki-text {
            position: absolute;
            top: 0px;
            right: 0px;
            font-size: 20px;
            font-weight: bold;
        }

        .panThumb {
            z-index: 100;
            height: 70px !important;
            width: 70px !important;
            position: absolute !important;
            top: -45px;
            left: 0px;
            border: 5px solid #ffffff;
            background-color: #fff;
            margin: auto;
            box-shadow: none !important;

            ::v-deep .pan-info {
                box-shadow: none !important;
            }
        }

        .progress-item {
            margin-bottom: 10px;
            font-size: 14px;
        }

        @media only screen and (max-width: 1510px) {
            .mallki-text {
                display: none;
            }
        }
    }
</style>
