<template>
  <div class="form-group row">
    <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>

    <vue-tags-input
      :value="fields['tags']"
      :tags="tags"
      :autocomplete-items="filteredItems"
      @tags-changed="newTags => setTags(newTags)"
    ></vue-tags-input>
  </div>
</template>

<script>
import VueTagsInput from "@johmun/vue-tags-input";
import { mapGetters, mapState, mapActions } from "vuex";

export default {
  components: {
    VueTagsInput
  },
  props: ["tags"],
  computed: {
    ...mapGetters("formStore", {
      errors: "error"
    }),
    ...mapState({
      fields: state => state.formStore.fields
    }),
    filteredItems() {
      return this.tags.filter(i => {
        return (
          i.text.toLowerCase().indexOf(this.fields["tags"].toLowerCase()) !== -1
        );
      });
    }
  },
  methods: {
    updateForm(newTags) {
      this.$store.commit("formStore/updateForm", {
        name: "tags",
        value: newTags
      });
    }
  }
};
</script>

<style lang="scss">
.vue-tags-input {
  flex: 0 0 50%;
  max-width: 100% !important;
  padding: 0 15px;
}

.ti-input {
  border-radius: 0.25rem;
}
</style>
