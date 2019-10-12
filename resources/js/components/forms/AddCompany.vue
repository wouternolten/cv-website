<template>
  <div>
    <div class="form-group row">
      <label class="col-md-4 col-form-label text-md-right" :for="company_id">Company</label>
      <div class="col-md-6">
        <select
          :value="fields[company_id]"
          class="browser-default custom-select"
          name="company_id"
          @input="updateForm"
        >
          <option
            v-for="company in companies"
            :value="company.id"
            :key="company.id"
          >{{ company.name }}</option>
          <option value="new_company">Add new company</option>
        </select>
      </div>
    </div>
    <template v-if="fields[company_id] === 'new_company'">
      <h4 class="title">New Company</h4>
      <component
        v-for="formData in companyFormNames"
        :key="formData.index"
        :is="`vue-${formData.type}-form`"
        :formData="formData"
      ></component>
    </template>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
  data() {
    return {
      company_id: "company_id"
    };
  },
  computed: {
    companyFormNames() {
      return this.objectMap(this.companyForm, formField => ({
        name: `company_${formField.name}`,
        labelName: formField.labelName,
        type: formField.type
      }));
    },
    ...mapState({
      fields: state => state.formStore.fields
    })
  },
  props: ["companies", "companyForm"],
  methods: {
    objectMap(object, mapFn) {
      return Object.keys(object).reduce(function(result, key) {
        result[key] = mapFn(object[key]);
        return result;
      }, {});
    },
    updateForm(e) {
      this.$store.commit("formStore/updateForm", {
        name: this.company_id,
        value: e.target.value
      });
    }
  }
};
</script>
