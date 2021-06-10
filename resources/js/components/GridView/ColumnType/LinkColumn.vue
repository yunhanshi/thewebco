<template>
  <el-table-column :key="column.key" :prop="column.prop" :sortable="column.sortable" :label="column.label">
    <template slot-scope="{row}">
      <span class="link-type" @click="handleCick(row)">{{ getCellValue(row, column) }}</span>
    </template>
  </el-table-column>
</template>

<script>
import ColumnTypeBase from 'Components/GridView/ColumnType/Base.vue';

export default {
  name: 'LinkColumn',
  extends: ColumnTypeBase,
  props: { column: { type: Object, default: () => {} }},
  methods: {
    handleCick(row) {
      if (this.column.clickEvent) {
        this.column.clickEvent(row);
      }
    },
  },
};
</script>
