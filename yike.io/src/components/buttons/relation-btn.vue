<template>
    <div @click="toggle">
        <template v-if="!item[actions[action]]">
            <slot name="on"></slot>
        </template>
        <template v-else>
            <slot name="off"></slot>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            relation: {
                type: String,
                required: true
            },
            action: {
                type: String,
                required: true
            },
            item: {
                type: Object,
                required: true,
                default() {
                    return {}
                }
            }
        },
        data() {
            return {
                // types: {
                //     thread: 'App\\Thread',
                //     user: 'App\\User',
                //     node: 'App\\Node'
                // },
                actions: {
                    like: 'is_praise',
                    follow: 'has_followed',
                    subscribe: 'is_follow'
                }
            }
        },
        methods: {
            toggle() {
                // let action = !this.item[this.actions[this.action]] ? this.action : `${this.action}`

                console.log(this.item);
                console.log(this.action);

                let url = '', params = {};
                // params.followable_type = this.types[this.relation];
                switch (this.action) {
                    case 'like': // 动态点赞
                        url = 'dynamic/praise';
                        params.dynamic_id = this.item.dynamic_id;
                        break;
                    case 'subscribe': // 关注话题
                        url = 'topic/follow';
                        params.topic_id = this.item.topic_id;
                        break;
                    default:
                        this.$message.error('尚未对接');
                        return false;
                        break;
                }

                this.$http.post(url, params).then((res) => {
                    if (res.status == 1) {
                        this.item[this.actions[this.action]] = !this.item[this.actions[this.action]];
                        this.$emit('after-toggle', this.item[this.actions[this.action]]);

                        this.$message.success(res.msg);
                    }else{
                        this.$message.error(res.msg);
                    }
                })
            }
        }
    }
</script>
