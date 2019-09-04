<template>
  <div>
    <div class="form-group row">
      <label class="col-md-4 col-form-label text-md-right" for="company_id">Company</label>
      <div class="col-md-6">
        <select v-model="selected" class="browser-default custom-select" value="company_id">
          <option
            v-for="company in companies"
            :value="company.id"
            :key="company.id"
          >{{ company.name }}</option>
          <option value="new_company">Add new company</option>
        </select>
      </div>
    </div>
    <template v-if="selected === 'new_company'">
      <h4 class="title">New Company</h4>
      <component
        v-for="formData in companyForm"
        :key="formData.index"
        :is="`vue-${formData.type}-form`"
        :formData="formData"
      ></component>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selected: ""
    };
  },
  props: ["companies", "companyForm"]
};
</script>
