import { FormTypes } from "./FormTypes";
import { Module, ActionTree, MutationTree } from "vuex";
import { RootState } from "../types";
import JobService from "../../services/JobService";
import Vue from 'vue';

// initial state
const state: FormTypes = {
    fields: {},
    errors: [],
    success: false,
    loaded: true,
    action: '',
    formService: new JobService()
};

// getters
const getters = {
    error: (state: FormTypes) => (errorName: string) => state.errors[errorName]
};

// actions
const actions: ActionTree<FormTypes, RootState> = {
    submitForm({ state, commit }) {
        commit('resetForm');

        state.formService.submit(
            state.action,
            state.fields
        ).then(response => {
            commit('setForm', response);
        }, (errors) => {
            commit('setErrors', errors);
        });
    },

    setActionUrl({ commit }, actionUrl: string) {
        commit('setActionUrl', actionUrl);
    }
};

const mutations: MutationTree<FormTypes> = {
    resetForm(state: FormTypes) {
        state.loaded = false;
        state.success = false;
        state.errors = [];
    },
    setForm(state: FormTypes, response: any) {
        state.fields = {};
        state.success = true;
        state.loaded = true;
    },

    setErrors(state: FormTypes, errors: any) {
        state.errors = errors;
        state.loaded = true;
    },

    setActionUrl(state: FormTypes, actionUrl: string) {
        state.action = actionUrl;
    },

    updateForm(state: FormTypes, { name, value }) {
        Vue.set(state.fields, name, value);
    }
};

const namespaced = true;

export const formStore: Module<FormTypes, RootState> = {
    namespaced,
    state,
    getters,
    actions,
    mutations
};
