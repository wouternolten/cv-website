<template>
  <form method="POST" enctype="multipart/form-data" @submit.prevent="submit">
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
import FormMixin from "./mixins/FormMixin";

export default {
  props: ["json_form"],
  mixins: [FormMixin],
  data() {
    return {
      action: "/jobs"
    };
  },
  computed: {
    totalForm() {
      return JSON.parse(this.json_form);
    }
  }
};
</script>
