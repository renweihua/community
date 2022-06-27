<template>
    <div class="box">
        <div class="box-heading border-bottom"><h5>版本记录</h5></div>
        <div class="box-body">
            <!-- Another update -->
            <vue-timeline-update
              :date="new Date('2022-06-25 16:41:00')"
              title="v1.3.4 - 小丑路人社区"
              description="Hyperf服务端上线"
              category="v1.3.4"
              icon="code"
              color="yellow"
            />

            <vue-timeline-update
              :date="new Date('2022-01-12 20:12:04')"
              title="v1.3.0 - 小丑路人社区"
              description="登录账户绑定第三方账户。"
              category="v1.3.0"
              icon="code"
              color="yellow"
            />

            <vue-timeline-update
              :date="new Date('2022-01-10 14:18:04')"
              title="v1.2.0 - 小丑路人社区"
              description="私服账户注册、绑定、管理。"
              category="v1.2.0"
              icon="code"
              color="blue"
            />

            <vue-timeline-update
              :date="new Date('2022-01-02 12:45:00')"
              title="v1.1.0 - 小丑路人社区"
              description="新增签到功能."
              category="v1.1.0"
              icon="code"
              color="turquoise"
            />

            <!-- Yet another update -->
            <vue-timeline-update
              :date="new Date('2021-02-12')"
              title="v1.0.0 - 小丑路人社区"
              description="小丑路人社区."
              category="v1.0.0"
              icon="code"
              color="white"
              is-last
            />
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {}
        },
        methods: {},
    }
</script>

<style scoped>
    .box-heading{
        font-size: 1.6rem;
    }
    .gb-vue-timeline-update--dark{
        color: #234990;
    }
</style>
