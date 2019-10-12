<template>
  <div class="form-group row">
    <label
      :for="formData.name"
      class="col-md-4 col-form-label text-md-right"
    >{{ formData.labelName }}</label>

    <div class="col-md-6">
      <select
        class="browser-default custom-select"
        :value="fields[formData.name]"
        :name="formData.name"
        @input="updateForm"
      >
        <option value>-- Please select one --</option>
        <option v-for="(option, index) in formData.options" :key="index" :value="index">{{ option }}</option>
      </select>
    </div>
    <div v-if="errors(formData.name)" class="text-danger">{{ errors(formData.name) }}</div>
  </div>
</template>

<script>
import { mapGetters, mapState } from "vuex";

export default {
  props: ["formData"],
  computed: {
    ...mapGetters("formStore", {
      errors: "error"
    }),
    ...mapState({
      fields: state => state.formStore.fields
    })
  },
  methods: {
    updateForm(e) {
      this.$store.commit("formStore/updateForm", {
        name: this.formData.name,
        value: e.target.value
      });
    }
  }
};
</script>
