<template>
  <el-table-column :key="column.key" :prop="column.prop" :label="column.label">
    <template slot-scope="{row}">
      <el-image
        :style="column.style"
        :class="column.class"
        :src="row[column.prop] || COMMONCONST.DEFAULT_HEAD_IMAGE"
        :fit="column.fit"
        @click="handleCick(row)"
      />
    </template>
  </el-table-column>
</template>

<script>
export default {
  name: 'ImageColumn',
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
