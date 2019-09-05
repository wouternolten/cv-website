<template>
  <div class="form-group row">
    <label
      :for="formData.name"
      class="col-md-4 col-form-label text-md-right"
    >{{ formData.labelName }}</label>

    <div class="col-md-6">
      <select
        class="browser-default custom-select"
        v-model="fields[formData.name]"
        :name="formData.name"
      >
        <option value>-- Please select one --</option>
        <option v-for="(option, index) in formData.options" :key="index" :value="index">{{ option }}</option>
      </select>
    </div>
    <div v-if="hasError(formData.name)" class="text-danger">{{ getError(formData.name) }}</div>
  </div>
</template>

<script>
import FormMixin from "../mixins/FormMixin";

export default {
  props: ["formData"],
  mixins: [FormMixin]
};
</script>
