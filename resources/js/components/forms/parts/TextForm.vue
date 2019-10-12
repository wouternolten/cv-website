<template>
  <div class="form-group row">
    <label
      :for="formData.name"
      class="col-md-4 col-form-label text-md-right"
    >{{ formData.labelName }}</label>

    <div class="col-md-6">
      <input
        :id="getId(formData.name)"
        type="text"
        class="form-control"
        :value="fields[formData.name]"
        :name="formData.name"
        :required="formData.required"
        @input="updateForm"
      />
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
    getId(name) {
      return `form-field-${name}`;
    },
    updateForm(e) {
      this.$store.commit("formStore/updateForm", {
        name: this.formData.name,
        value: e.target.value
      });
    }
  }
};
</script>
