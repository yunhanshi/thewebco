<script>
import { getByKeys } from 'Utils/objects.js';

const COMPOSE_CANDIDATE_KEYS = ['name_text', 'name'];

export default {
  name: 'ColumnTypeBase',
  methods: {
    /**
     * 获取表格单元内容
     */
    getCellValue(row, column) {
      const value = getByKeys(row, column.prop, '');
      return this.handleCellValue(column.prop, value, row, column);
    },

    /**
     * 获取指定列的组装key。
     * 适用于复合字段，比如字段的value为数组或者对象，根据组装key，取每个数组元素或对象指定字段组装成展示内容。
     */
    getComposeKey(column) {
      return typeof column.composeKey === 'string' ? column.composeKey : null;
    },

    /**
     * 获取组装内容的分隔符
     */
    getComposeSeparator(column) {
      return typeof column.composeSeparator === 'string' ? column.composeSeparator : '<br/>';
    },

    /**
     * 处理表格单元内容
     */
    handleCellValue(prop, value, row, column) {
      if (typeof column.onCell === 'function') {
        return column.onCell(prop, value, row);
      }

      if (!value) {
        return value;
      }

      if (Array.isArray(value)) {
        return this.handleListCellValue(prop, value, row, column);
      }

      if (typeof value === 'object') {
        return this.handleObjectCellValue(prop, value, row, column);
      }

      return value;
    },

    /**
     * 处理列表类型的表格单元内容
     */
    handleListCellValue(prop, value, row, column) {
      const separator = this.getComposeSeparator(column);

      var res = '';
      for (var i = 0; i < value.length; i++) {
        if (res) {
          res += separator;
        }

        res += this.getComposeValue(value[i], column);
      }
      return res;
    },

    /**
     * 处理对象类型的表格单元内容
     */
    handleObjectCellValue(prop, value, row, column) {
      return this.getComposeValue(value, column);
    },

    /**
     * 组装展示内容
     */
    getComposeValue(value, column, candidateKeys) {
      const composeKey = this.getComposeKey(column);

      if (composeKey) {
        return value[composeKey];
      }

      candidateKeys = candidateKeys || COMPOSE_CANDIDATE_KEYS;
      for (var i = 0; i < candidateKeys.length; i++) {
        const key = candidateKeys[i];
        if (typeof value[key] !== 'undefined') {
          return value[key];
        }
      }

      return value;
    },
  },
};
</script>
