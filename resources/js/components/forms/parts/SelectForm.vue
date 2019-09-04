<template>
  <div class="form-group row">
    <label
      :for="formData.name"
      class="col-md-4 col-form-label text-md-right"
    >{{ formData.labelName }}</label>

    <div class="col-md-6">
      <select class="browser-default custom-select" v-model="selected" :name="formData.name">
        <option value>-- Please select one --</option>
        <option v-for="(option, index) in formData.options" :value="index">{{ option }}</option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  props: ["formData"],
  data() {
    return {
      selected: {}
    };
  }
};
</script>
