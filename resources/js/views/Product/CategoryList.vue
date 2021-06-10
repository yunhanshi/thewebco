<template>
  <grid-view ref="categoryGridView" :columns="columns" :buttons="buttons" :pager="pager" :filters="filters" :resource="resource" />
</template>

<script>
import CategoryResource from '@/api/product/category.js';
import GridView from 'Components/GridView';
import ListBase from 'Components/GridView/Base';

const categoryResource = new CategoryResource();

export default {
  name: 'CategoryList',
  components: { GridView },
  extends: ListBase,
  data() {
    return {
      reload: true,
      filters: [
        { type: 'autocomplete', label: 'Name', queryKey: 'name@like', resource: categoryResource, span: 12 },
      ],
      buttons: [
        { type: 'success', label: 'New Category', clickEvent: this.handleAdd },
      ],
      columns: [
        { id: 'category-id', type: 'string', label: 'Category ID', prop: 'id', sortable: 'custom' },
        { id: 'category-name', type: 'link', label: 'Name', prop: 'name', minWidth: '140px', sortable: 'custom', clickEvent: this.handleEdit },
        { id: 'category-sort-order', type: 'string', label: 'Sort Order', prop: 'sort_order', sortable: 'custom' },
        { id: 'category-buttons', type: 'button', label: 'Options', prop: 'title',
          buttons: [
            { name: 'Edit Category', icon: 'el-icon-edit', type: 'primary', class: '', style: '', clickEvent: this.handleEdit },
            { name: 'Delete Category', icon: 'el-icon-delete', type: 'danger', class: '', style: '', clickEvent: this.handleDelete },
          ],
        },
      ],
      resource: categoryResource,
    };
  },
  created() {
  },
  methods: {
    getBasicRoute() {
      return 'product/category';
    },
    getResource() {
      return categoryResource;
    },
    reloadList() {
      this.$refs.categoryGridView.handleFilter();
    },
  },
};
</script>
