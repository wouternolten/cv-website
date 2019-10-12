import Vue from "vue";
import { ActionTree, Module, MutationTree } from "vuex";

import { FormField, JobForm } from "../../interfaces/JobForm";
import { RootState } from "../types";
import { FormTypes } from "./FormTypes";

// initial state
const state: FormTypes = {
    fields: {},
    errors: [],
    success: false,
    loaded: true
};

// getters
const getters = {
    error: (state: FormTypes) => (errorName: string) => state.errors[errorName]
};

// actions
const actions: ActionTree<FormTypes, RootState> = {
    updateForm(
        { state, commit }: { state: FormTypes; commit: any },
        { name, value }: { name: string; value: string | string[] }
    ) {
        if (Array.isArray(value)) {
            if (!Array.isArray(state.fields[name])) {
                commit("setArray", name);
            }

            value.forEach(val => commit("addUnique", { name, value: val }));
        } else {
            commit("updateForm", { name, value });
        }
    },

    setValues({ dispatch }, form: JobForm) {
        form.job_form.forEach((formField: FormField) => {
            dispatch("updateForm", {
                name: formField.name,
                value: formField.value
            });
        });

        dispatch("updateForm", {
            name: "tags",
            value: form.tags || []
        });

        form.company_form.forEach((formField: FormField) => {
            dispatch("updateForm", {
                name: `company_${formField.name}`,
                value: formField.value
            });
        });
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

    setArray(state: FormTypes, name: string) {
        Vue.set(state.fields, name, []);
    },

    updateForm(state: FormTypes, { name, value }) {
        Vue.set(state.fields, name, value);
    },

    addUnique(
        state: FormTypes,
        { name, value }: { name: string; value: string }
    ) {
        if (!state.fields[name].includes(value)) state.fields[name].push(value);
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
