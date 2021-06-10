<template>
  <el-date-picker
    :value="value"
    :range-separator="filter.rangeSeparator ? filter.rangeSeparator : 'to'"
    :start-placeholder="filter.label"
    :end-placeholder="filter.label"
    :class="filter.class"
    :style="filter.style"
    type="datetimerange"
    class="filter-item"
    value-format="yyyy-MM-dd HH:mm:ss"
    @input="$emit('update:value', $event);$emit('input', $event);"
  />
</template>

<script>
export default {
  name: 'DatePickerFilter',
  props: {
    filter: { type: Object, default: () => {} },
    value: { type: Array, default: () => [] },
    index: { type: String, default: () => '' },
  },
};
</script>
