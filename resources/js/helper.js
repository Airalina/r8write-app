import axios from "axios";

export const API = () => {
  let baseURL = "http://r8write-app-backend.test" || "";

  if (baseURL.substr(-1) === "/") {
    baseURL = baseURL.substr(0, baseURL.length - 1);
  }

  const instance = axios.create({
    baseURL: baseURL + "/api",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
    },
  });

  const jwt = localStorage.getItem("token");
instance.defaults.headers.common.Authorization = `Bearer ${jwt}`;

  // instance.interceptors.request.use(
  //     (cfg) => {
  //         const jwt = localStorage.getItem('token');
  //         const config: any = cfg;
  //         if (jwt) {
  //             config.headers.common.Authorization = `Bearer ${jwt}`;
  //         }
  //         return config;
  //     },
  //     (error) => {
  //         Promise.reject(error);
  //     }
  // );

  instance.interceptors.response.use(
    function (response) {
      return response;
    },
    function (error) {}
  );
  return instance;
};