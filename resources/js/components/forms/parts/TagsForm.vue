<template>
  <div>
    <div class="form-group row">
      <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>
      <vue-tags-input
        v-model="tag"
        :tags="getTags"
        :autocomplete-items="filteredItems"
        @tags-changed="newTags => parseTags(newTags)"
      ></vue-tags-input>
    </div>
  </div>
</template>

<script>
import VueTagsInput from "@johmun/vue-tags-input";
import { mapGetters, mapState, mapActions } from "vuex";

export default {
  data() {
    return {
      tag: ""
    };
  },
  components: {
    VueTagsInput
  },
  created() {
    this.updateForm({ name: "tags", value: this.tags || [] });
  },
  props: ["tags"],
  computed: {
    ...mapGetters("formStore", {
      errors: "error"
    }),
    ...mapState({
      fields: state => state.formStore.fields
    }),
    getTags() {
      return this.fields["tags"].map(tag => ({
        text: tag
      }));
    },
    filteredItems() {
      return this.tags.filter(i => {
        return (
          i.toLowerCase().indexOf(this.fields["tags"].toLowerCase()) !== -1
        );
      });
    }
  },
  methods: {
    parseTags(newTags) {
      const tagsText = newTags.map(tag => tag.text);
      this.updateForm({
        name: "tags",
        value: tagsText
      });
    },
    ...mapActions({
      updateForm: "formStore/updateForm"
    })
  }
};
</script>

<style lang="scss">
@import "~sass-mq";

.vue-tags-input {
  max-width: 100% !important;
  padding: 0 15px;

  @include mq($until: tablet) {
    width: 100%;
  }

  @include mq($from: tablet) {
    flex: 0 0 50%;
  }
}

.ti-input {
  border-radius: 0.25rem;
}
</style>
