<template>
  <form method="POST" enctype="multipart/form-data" @submit.prevent="submitForm">
    <div class="form-group row">
      <label for="function_name" class="col-md-4 col-form-label text-md-right">Function name</label>
      <div class="col-md-6">
        <input class="form-control" v-model="job.function_name" required="true" @input="updateForm" />
      </div>
    </div>

    <div class="form-group row">
      <label for="function_name" class="col-md-4 col-form-label text-md-right">Function name</label>
      <div class="col-md-6">
        <input class="form-control" v-model="job.function_name" required="true" @input="updateForm" />
      </div>
    </div>
  </form>
</template>

<script lang="ts">
import { mapActions, mapState } from "vuex";
import { Job } from "../../interfaces/Job";
import { Tag } from "../../interfaces/Tag";
import { Company } from "../../interfaces/Company";

export default {
  props: ["json_job", "json_tags", "json_company"],
  created() {
    this.setActionUrl(`${window.location.origin}/jobs/${this.job.id}`);
    console.log(Object.keys(this.job));
  },
  computed: {
    formKeys(): string[] {
      return Object.keys(this.job);
    },
    job(): Job {
      return JSON.parse(this.json_job);
    },
    tags(): Tag[] {
      return JSON.parse(this.json_tags);
    },
    company(): Company {
      return JSON.parse(this.json_company);
    }
  },
  methods: {
    ...mapActions({
      submitForm: "formStore/submitForm",
      setActionUrl: "formStore/setActionUrl"
    })
  }
};
</script>
