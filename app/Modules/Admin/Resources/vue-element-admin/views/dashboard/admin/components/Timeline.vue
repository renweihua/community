<template>
    <div class="block">
        <el-timeline>
            <el-timeline-item
                v-for="(item,index) of logs"
                :key="index"
                :timestamp="item.publish_date"
                :color="index === 0 ? 'rgb(11, 189, 135)' : ''"
                placement="top">
                <el-card>
                    <h4>{{ item.version_name }}[{{ item.version_number }}]</h4>
                    <!-- 直接解析 markdown 语法 -->
                    <vue-markdown class="markdown-body">{{ item.version_content }}</vue-markdown>
                </el-card>
            </el-timeline-item>
        </el-timeline>
    </div>
</template>

<script>
    // md解析器
    import VueMarkdown from 'vue-markdown';
    // markdown 样式引入
    import 'github-markdown-css';
    export default {
        components:{
            VueMarkdown
        },
        // 组件传值
        props       : {
            logs   : {
                type    : Array,
                default : [],
            }
        },
        // 监听
        watch: {
            //当 logs 属性的值发生改变时会执行下面的代码
            logs: function (newValue, oldValue) {
                this.logs = newValue;
            }
        },
        data() {
            return {
            }
        },
        created() {

        },
    }
</script>
