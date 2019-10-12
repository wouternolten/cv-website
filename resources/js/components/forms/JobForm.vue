<template>
  <form enctype="multipart/form-data" :method="formMethod" :action="action">
    <input v-if="realMethod !== 'POST'" type="hidden" name="_method" :value="realMethod" />
    <input type="hidden" name="_token" :value="csrf" />
    <component
      v-for="formData in totalForm.job_form"
      :key="formData.index"
      :is="`vue-${formData.type}-form`"
      :formData="formData"
    ></component>
    <vue-tags-form :filterTags="totalForm.all_tags"></vue-tags-form>
    <vue-add-company :companies="totalForm.companies" :companyForm="totalForm.company_form"></vue-add-company>
    <div class="form-group row">
      <div class="col-md-4"></div>
      <div class="col-md-6">
        <button type="submit" class="btn btn-primary">{{ button_text }}</button>
      </div>
    </div>
    <div class="form-group row">
      <div class="col-md-4"></div>
      <div class="col-md-6">Form data: {{ fields }}</div>
    </div>
  </form>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
  props: ["json_form", "action", "button_text", "method", "csrf"],
  created() {
    this.setValues(this.totalForm);
  },
  computed: {
    totalForm() {
      return JSON.parse(this.json_form);
    },
    ...mapState({
      fields: state => state.formStore.fields
    }),
    formMethod() {
      return this.method === "GET" ? "GET" : "POST";
    },
    realMethod() {
      return this.method;
    }
  },
  methods: {
    ...mapActions({
      setValues: "formStore/setValues"
    })
  }
};
</script>
