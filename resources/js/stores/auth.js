

import {API} from "@/helper"
import {AxiosResponse} from "axios"

const useAuthStore = {
    namespaced: true,
    state: {
       authUser: JSON.parse(localStorage.getItem("user") || "{}") || null,
    },
    getters: {
        user(state) {
            return state.authUser;
        },
    },
    mutations: {
        getUser(state, payload) {
           //  = await API().get("/auth/me");
             API().get('/auth/me')
                .then(response => {const data = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });

            this.authUser = data; console.log('auth'); console.log(data);
            localStorage.setItem("user", JSON.stringify(this.authUser));

        },
        setToken(token) {
            localStorage.setItem("token", token);
            this.getUser();
        },
    },
};

export default useAuthStore;
