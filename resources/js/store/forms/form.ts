import { FormTypes } from "./FormTypes";
import { Module, ActionTree, MutationTree } from "vuex";
import { RootState } from "../types";
import JobService from "../../services/JobService";

// initial state
const state: FormTypes = {
    fields: [],
    errors: null,
    success: false,
    loaded: true,
    action: '',
    formService: new JobService()
};

// getters
const getters = {
    error: (state: FormTypes, errorName: string) => state.errors[errorName],
};

// actions
const actions: ActionTree<FormTypes, RootState> = {
    submitForm({ state, commit }) {
        state.loaded = false;
        state.success = false;
        state.errors = null;

        state.formService.submit(
            state.action,
            state.fields
        ).then(response => {
            commit('setForm', response);
        }, (errors) => {
            commit('setErrors', errors);
        });
    }
};

const mutations: MutationTree<FormTypes> = {
    setForm(state: FormTypes, response: any) {
        state.fields.length = 0;
        state.success = true;
        state.loaded = true;
    },

    setErrors(state: FormTypes, errors: any) {
        state.errors = errors;
        state.loaded = true;
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
