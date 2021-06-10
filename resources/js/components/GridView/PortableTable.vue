<template>
  <el-table
    :key="tableKey"
    :data="data"
    :border="border"
    fit
    highlight-current-row
    style="width: 100%;"
  >
    <template v-for="(column, index) in columns">
      <component :is="column.type+'-column'" :key="'column-'+index" :column="column" :sortable="column.sortable" />
    </template>

  </el-table>
</template>

<script>
import StringColumn from './ColumnType/StringColumn';
import LinkColumn from './ColumnType/LinkColumn';
import ButtonColumn from './ColumnType/ButtonColumn';
import ImageColumn from './ColumnType/ImageColumn';
import InputColumn from './ColumnType/InputColumn';

const sortMap = {
  ascending: 'asc',
  descending: 'desc',
};

export default {
  name: 'PortableTable',
  components: {
    StringColumn,
    LinkColumn,
    ButtonColumn,
    ImageColumn,
    InputColumn,
  },
  /** 需要传入的参数 */
  props: {
    tableKey: {
      type: String,
      default: () => 'portableList',
    },
    /** 需要绘制的功能按钮 */
    buttons: {
      type: Array,
      default: () => [],
    },
    /** 需要绘制的列 */
    columns: {
      type: Array,
      default: () => [],
    },
    /** 是否有纵向边框 */
    border: {
      type: Boolean,
      default: () => false,
    },
    rows: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      data: [],
      total: 0,
    };
  },
  computed: {
  },
  created() {
  },
  watch: {
    rows: function(val) {
      this.data = val;
    },
  },
  methods: {
    async renderList() {
    },
  },
};
</script>
