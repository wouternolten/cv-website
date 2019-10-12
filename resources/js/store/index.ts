import Vue from 'vue';
import Vuex, { StoreOptions } from 'vuex';
import createLogger from '../plugins/logger';
import { formStore } from './forms/form';
import { RootState } from './types';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

const store: StoreOptions<RootState> = {
    modules: {
        formStore
    },
    strict: debug,
    plugins: debug ? [createLogger()] : []
}

export default new Vuex.Store(store);
