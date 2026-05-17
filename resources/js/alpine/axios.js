import axios from 'axios';
import { route } from 'ziggy-js';
import { Ziggy } from '../ziggy';

window.route = (name, params = {}, absolute = false) => {
    return route(name, params, absolute, Ziggy);
}

axios.defaults.withCredentials = true;
axios.defaults.xsrfCookieName = 'XSRF-TOKEN';
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN';
window.axios = axios;

export default function alpineAxios(Alpine) {
    Alpine.magic('alpine', () => {
        return {
            route: (name, params = {}) => {
                return route(name, params, undefined, Ziggy);
            },
            get: (url, config = {}) => {
                return axios.get(url, config);
            },
            post: (url, config = {}) => {
                return axios.post(url, config);
            },
            put: (url, config = {}) => {
                return axios.put(url, config);
            },
            patch: (url, config = {}) => {
                return axios.patch(url, config);
            },
            delete: (url, config = {}) => {
                return axios.delete(url, config);
            },
            formErrors: (error) => {
                return Object.fromEntries(Object.entries(error?.response?.data
                    ?.errors).map(([key, value]) => [key, value[0]]));
            },
        }
    })
}