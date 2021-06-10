<template>
  <el-select
    v-model="value"
    :disabled="disabled"
    :multiple="multi"
    :clearable="clearable"
    :filterable="filterable"
    remote
    reserve-keyword
    :placeholder="label"
    :remote-method="querySearchAsync"
    :loading="loading"
    autocomplete="off"
    @change="selectTrigger(value)"
  >
    <el-option
      v-for="item in options"
      :key="item.value"
      :label="item.label"
      :value="item.value"
    />
  </el-select>
</template>

<script>
import Resource from '@/api/resource';

export default {
  name: 'MultiSelectFilter',
  model: {
    prop: 'value',
    event: 'update',
  },
  props: {
    filter: { type: Object, default: () => {} },
    resource: { type: Object, default: () => {} },
    displayKey: { type: String, default: () => '' },
    label: { type: String, default: () => '' },
    option: { type: Array, default: () => [] },
    multi: { type: Boolean, default: () => false },
    filterable: { type: Boolean, default: () => true },
    disabled: { type: Boolean, default: () => false },
    clearable: { type: Boolean, default: () => false },
  },
  data() {
    return {
      value: '',
      options: [],
      loading: false,
    };
  },
  watch: {
  },
  created() {
  },
  methods: {
    async querySearchAsync(queryString) {
      if (!(this.filter.resource instanceof Resource)) {
        return;
      }

      this.loading = true;
      const { data } = await this.filter.resource.autocomplete({ filter: { 'name@like': queryString }});
      this.options = data;
      this.loading = false;
    },
    selectTrigger(val) {
      this.$emit('update:value', val);
    },
  },
};
</script>
