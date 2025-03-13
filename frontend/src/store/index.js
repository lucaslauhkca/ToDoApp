import { createStore } from 'vuex';
import { API_BASE_URL } from "../config";

export default createStore({
    state: {
        token: localStorage.getItem('token') || '',
        user: null
    },
    mutations: {
        setToken(state, token) {
            state.token = token;
            localStorage.setItem('token', token);
        },
        removeToken(state) {
            state.token = '';
            localStorage.removeItem('token');
        },
        setUser(state, user) {
            state.user = user;
        }
    },
    actions: {
        async login({ commit }, credentials) {
            const response = await fetch(`${API_BASE_URL}/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(credentials)
            });
            const data = await response.json();
            if (response.ok) {
                commit('setToken', data.token);
            }
            return response;
        },
        async logout({ commit }) {
            await fetch(`${API_BASE_URL}/logout`, { method: 'POST', headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } });
            commit('removeToken');
        }
    }
});
