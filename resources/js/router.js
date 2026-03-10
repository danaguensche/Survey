import { createRouter, createWebHistory } from "vue-router";

const routes = [{
        path: "/",
        name: "HomeRoute",
        component: () =>
            import ("./Pages/HomeRoute.vue"),
    },
    {
        path: "/survey",
        name: "SurveyRoute",
        component: () =>
            import ("./Pages/SurveyRoute.vue"),
    },
    {
        path: "/evaluation",
        name: "EvaluationRoute",
        component: () =>
            import ("./Pages/EvaluationRoute.vue"),
    },
    {
        path: '/:pathMatch(.*)*',
        name: "NotFoundRoute",
        component: () =>
            import ("./Pages/NotFoundRoute.vue"),
    }
];

export default createRouter({
    history: createWebHistory(),
    routes,
});