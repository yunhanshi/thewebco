<template>
  <el-table-column :key="column.key" :label="column.label" :prop="column.prop" :sortable="column.sortable" :min-width="columnMinWidth" :align="getAlign(column)">
    <template slot-scope="{row}">
      <template v-for="(button, index) in column.buttons">
        <template v-if="button.icon">
          <el-tooltip v-if="getButtonVisible(row, button)" :key="index" :content="button.name" placement="top" :style="button.style" :disabled="getDisabled(row, button)">
            <el-button :type="button.type" :size="buttonSize" :icon="button.icon" circle @click="button.clickEvent(row)" />
          </el-tooltip>
        </template>
        <template v-else>
          <el-button v-if="getButtonVisible(row, button)" :key="index" :type="button.type" :size="buttonSize" round :style="button.style" :disabled="getDisabled(row, button)" @click="button.clickEvent(row)">
            {{ button.name }}
          </el-button>
        </template>
      </template>
    </template>
  </el-table-column>
</template>

<script>
export default {
  name: 'ButtonColumn',

  props: { column: { type: Object, default: () => {} }},

  computed: {
    buttonSize: function() {
      return this.column.buttonSize || 'mini';
    },
    columnMinWidth: function() {
      return this.column.minWidth;
    },
  },
  methods: {
    getAlign(column) {
      return column.align || 'center';
    },
    getDisabled(row, button) {
      if (!button.rowDisabled) {
        return false;
      }
      return row[button.rowDisabled];
    },
    getButtonVisible(row, button) {
      let visible = true;
      if (button.rowVisible) {
        visible = visible && Boolean(row[button.rowVisible]);
      }
      if (button.rowInvisible) {
        visible = visible && (Boolean(row[button.rowInvisible]) === false);
      }
      return visible;
    },
  },
};
</script>
