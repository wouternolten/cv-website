<template>
  <div class="form-group row">
    <label
      :for="formData.name"
      class="col-md-4 col-form-label text-md-right"
    >{{ formData.labelName }}</label>

    <div class="col-md-6">
      <input
        :id="getId(formData.name)"
        type="date"
        class="form-control"
        v-model="fields[formData.name]"
        :name="formData.name"
        :required="formData.required"
      />
    </div>
    <div v-if="hasError(formData.name)" class="text-danger">{{ getError(formData.name) }}</div>
  </div>
</template>

<script>
import FormMixin from "../mixins/FormMixin";

export default {
  props: ["formData"],
  mixins: [FormMixin],
  methods: {
    getId(name) {
      return `form-field-${name}`;
    }
  }
};
</script>
