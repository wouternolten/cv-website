<template>
  <div>
    <div class="form-group row">
      <label for="tags" class="col-md-4 col-form-label text-md-right">Tags</label>
      <vue-tags-input
        v-model="tag"
        :tags="currentTags"
        :autocompleteItems="filteredItems"
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
      tag: "",
      currentTags: []
    };
  },
  created() {
    this.fields.tags.forEach(tag => {
      this.currentTags.push({
        text: tag
      });
    });
  },
  components: {
    VueTagsInput
  },
  props: ["filterTags"],
  computed: {
    ...mapGetters("formStore", {
      errors: "error"
    }),
    ...mapState({
      fields: state => state.formStore.fields
    }),
    filteredItems() {
      return this.filterTags
        .filter(tag => tag.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1)
        .map(tag => ({ text: tag }));
    }
  },
  methods: {
    parseTags(newTags) {
      this.updateForm({
        name: "tags",
        value: newTags.map(tag => tag.text)
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
