<template>
  <el-table-column :key="column.key" :label="column.label" :sortable="column.sortable" :prop="column.prop" :min-width="column.minWidth">
    <template slot-scope="{row}">
      <div v-html="getCellValue(row, column)" />
    </template>
  </el-table-column>
</template>

<script>

import ColumnTypeBase from 'Components/GridView/ColumnType/Base.vue';

export default {
  name: 'StringColumn',
  extends: ColumnTypeBase,
  props: { column: { type: Object, default: () => {} }},
};
</script>
