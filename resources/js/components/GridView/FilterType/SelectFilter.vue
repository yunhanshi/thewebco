<template>
  <el-select
    :key="filter.index"
    :value="value"
    :style="filter.style"
    :class="filter.class"
    :placeholder="filter.label"
    clearable
    class="filter-item"
    @input="$emit('update:value', $event)"
  >
    <el-option v-for="(item, i) in filter.options" :key="'filter-'+i" :label="item.key||item.label" :value="item.value" />
  </el-select>
</template>

<script>
export default {
  name: 'DatePickerFilter',
  props: {
    filter: { type: Object, default: () => {} },
    value: { type: [String, Number], default: () => '' },
  },
};
</script>
