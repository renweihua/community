import {
    asyncRoutes,
    constantRoutes
} from '@/router';

import {
    getMenus
} from '@/api/login';

import Layout from '@/layout';

/**
 * Use meta.role to determine if the current user has permission
 * @param roles
 * @param route
 */
function hasPermission(roles, route) {
    if (route.meta && route.meta.roles) {
        return roles.some(role => route.meta.roles.includes(role))
    } else {
        return true
    }
}

/**
 * Filter asynchronous routing tables by recursion
 * @param routes asyncRoutes
 * @param roles
 */
export function filterAsyncRoutes(routes, roles) {
    const res = []

    routes.forEach(route => {
        const tmp = {...route}
        if (hasPermission(roles, tmp)) {
            if (tmp.children) {
                tmp.children = filterAsyncRoutes(tmp.children, roles)
            }
            res.push(tmp)
        }
    })

    return res
}

/**
 * 后台查询的菜单数据拼装成路由格式的数据
 * @param routes
 */
export function generaMenu(routes, data) {
    data.forEach(item => {
        // 如果不存在路由页面，那么不追究到路由中
        if (item.vue_component.length <= 0) return;

        const menu = {
            path: item.vue_path,
            component: (item.vue_component === 'Layout') ? Layout : (resolve) => require([`@/views/${item.vue_component}`], resolve),
            children: [],
            name: item.vue_name,
        };

        if (item.vue_path != '/') menu.meta = {
            icon: item.vue_icon,
            title: item.vue_name,
            id: item.menu_id
        };

        // 首页
        if (item.vue_path == 'dashboard') menu.meta.affix = true;

        // 是否跳转
        if (item.vue_redirect) menu.redirect = item.vue_redirect;

        // 隐藏的菜单栏目
        if (item.is_hidden == 1) menu.hidden = true;

        // 子菜单
        if (item._child) {
            generaMenu(menu.children, item._child);
        }

        routes.push(menu);
    });
}

const state = {
    routes: [],
    addRoutes: []
};

const mutations = {
    SET_ROUTES: (state, routes) => {
        state.addRoutes = routes;
        state.routes = constantRoutes.concat(routes);
    }
}

const actions = {
    generateRoutes({commit}, roles) {
        return new Promise(resolve => {
            getMenus(state.token).then((response) => {
                const {
                    data
                } = response;

                var pushRouter = [];
                generaMenu(pushRouter, data);
                commit('SET_ROUTES', pushRouter);
                resolve(pushRouter);
            }).catch(error => {
                console.log(error);
                resolve([]);
            });
        });
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}
