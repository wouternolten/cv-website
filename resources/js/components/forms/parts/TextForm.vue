<template>
  <div class="form-group row">
    <label for class="col-md-4 col-form-label text-md-right">{{ formData.labelName }}</label>

    <div class="col-md-6">
      <input id type="text" class="form-control" :required="formData.required" />
    </div>
  </div>
</template>

<script>
export default {
  props: ["formData"]
};
</script>
