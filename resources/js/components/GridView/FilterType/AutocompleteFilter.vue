<template>
  <el-autocomplete
    :key="index"
    :style="filter.style"
    :class="filter.class"
    :placeholder="filter.label"
    :value="value"
    :fetch-suggestions="querySearchAsync"
    clearable
    class="filter-item"
    @input="$emit('update:value', $event)"
  />
</template>

<script>
import Resource from '@/api/resource';

export default {
  name: 'AutocompleteFilter',
  props: {
    filter: { type: Object, default: () => {} },
    value: { type: [String, Number], default: () => '' },
    index: { type: String, default: () => '' },
  },
  methods: {
    async querySearchAsync(queryString, cb) {
      if (this.filter.resource instanceof Resource) {
        const result = await this.filter.resource.autocomplete({ filter: { 'name@like': queryString }});
        const data = result.data || [];
        const options = data.map(item => {
          return { value: `${item.label}` };
        });
        cb(options);
      }
    },
  },
};
</script>
