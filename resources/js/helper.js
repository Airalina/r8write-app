import axios from "axios";

export const API = () => {
  let baseURL = "http://r8write-app.test" || "";

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
  const permissionsDB = localStorage.getItem("permissions");

  instance.interceptors.response.use(
    function (response) {
      return response;
    },
    function (error) {}
  );
  return instance;
};

export const hasPermission = (permission) => {
  const scopes = localStorage.getItem("permissions"); // permissions list 
  const permissions = scopes.split(','); 
  if (scopes) {
    const findPermission = permissions.find(element => element == permission);
    return findPermission ? true : false;
  }
  return false;
};