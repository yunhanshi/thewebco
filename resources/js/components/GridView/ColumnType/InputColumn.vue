<template>
  <el-table-column :key="column.key" :label="column.label" :sortable="column.sortable" :min-width="column.minWidth">
    <template slot-scope="{row}">
      <el-input v-model="row[column.prop]" :type="getType(column)" class="inline-input" :placeholder="column.placeholder" @change="handleChange(column, row)">
        <el-button v-if="column.button && getButtonVisible(row, column)" slot="append" :icon="getIcon(row, column)" :disabled="getDisabled(row, column)" @click="column.button.clickEvent(row)">{{ getButtonText(row, column) }}</el-button>
      </el-input>
    </template>
  </el-table-column>
</template>

<script>

import ColumnTypeBase from 'Components/GridView/ColumnType/Base.vue';

export default {
  name: 'InputColumn',
  extends: ColumnTypeBase,
  props: { column: { type: Object, default: () => {} }},
  methods: {
    getType(column) {
      return column.inputType || 'text';
    },
    getIcon(row, column) {
      if (this.getButtonText(row, column)) {
        return '';
      }
      return column.button.icon;
    },
    getButtonText(row, column) {
      if (!column.button.rowText) {
        return column.button.text;
      }
      return row[column.button.rowText] || column.button.text;
    },
    getDisabled(row, column) {
      if (!column.button.rowDisabled) {
        return false;
      }
      return row[column.button.rowDisabled];
    },
    getButtonVisible(row, column) {
      if (!column.button.rowVisible) {
        return true;
      }
      return row[column.button.rowVisible];
    },
    handleChange(column, row) {
      if (typeof column.onChange === 'function') {
        column.onChange(row);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
  input {
    display: none !important;
  }
  input + div {
    text-align: center;
  }
</style>
