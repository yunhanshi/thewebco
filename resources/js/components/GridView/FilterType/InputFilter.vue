<template>
  <el-input
    :key="index"
    :style="filter.style"
    :class="filter.class"
    :placeholder="filter.label"
    :value="value"
    clearable
    class="filter-item"
    @input="$emit('update:value', $event)"
  />
</template>

<script>
export default {
  name: 'InputFilter',
  props: {
    filter: { type: Object, default: () => {} },
    value: { type: String, default: () => '' },
    index: { type: String, default: () => '' },
  },
};
</script>
