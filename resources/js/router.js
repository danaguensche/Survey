import { createRouter, createWebHistory } from "vue-router";

const routes = [{
        path: "/home",
        component: () =>
            import ("./Pages/HomeRoute.vue"),
    },
    {
        path: "/test",
        component: () =>
            import ("./Pages/TestRoute.vue"),
    },
    {
        path: "/survey",
        component: () =>
            import ("./Pages/SurveyRoute.vue"),
    },
    {
        path: "/evaluation",
        component: () =>
            import ("./Pages/EvaluationRoute.vue"),
    }
];

export default createRouter({
    history: createWebHistory(),
    routes,
});