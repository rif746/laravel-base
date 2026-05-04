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

    Alpine.magic('route', () => {
        return (name, params = {}) => {
            return route(name, params, undefined, Ziggy);
        }
    });

    Alpine.magic('get', () => {
        return (url, config = {}) => {
            return axios.get(url, config);
        }
    });

    Alpine.magic('post', () => {
        return (url, config = {}) => {
            return axios.post(url, config);
        }
    });

    Alpine.magic('put', () => {
        return (url, config = {}) => {
            return axios.put(url, config);
        }
    });

    Alpine.magic('patch', () => {
        return (url, config = {}) => {
            return axios.patch(url, config);
        }
    });

    Alpine.magic('delete', () => {
        return (url, config = {}) => {
            return axios.delete(url, config);
        }
    });

    Alpine.magic('formErrors', () => {
        return (error) => {
            return Object.fromEntries(Object.entries(error?.response?.data
                ?.errors).map(([key, value]) => [key, value[0]]));
        }
    })
}