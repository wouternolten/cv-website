<template>
  <form method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
    <component
      v-for="formData in totalForm.job_form"
      :key="formData.index"
      :is="`vue-${formData.type}-form`"
      :formData="formData"
    ></component>
    <vue-add-company :companies="totalForm.companies" :companyForm="totalForm.company_form"></vue-add-company>
    <div class="form-group row">
      <div class="col-md-4"></div>
      <div class="col-md-6">
        <button type="submit" class="btn btn-primary">Add job</button>
      </div>
    </div>
  </form>
</template>

<script>
import { mapActions } from "vuex";
export default {
  props: ["json_form"],
  created() {
    this.setActionUrl(`${window.location.origin}/jobs`);
  },
  computed: {
    totalForm() {
      return JSON.parse(this.json_form);
    }
  },
  methods: {
    ...mapActions({
      submitForm: "formStore/submitForm",
      setActionUrl: "formStore/setActionUrl",
      testSetErrors: "formStore/testSetErrors"
    })
  }
};
</script>
