<template>
  <grid-view ref="productGridView" :columns="columns" :buttons="buttons" :pager="pager" :filters="filters" :resource="resource" />
</template>

<script>
import ProductResource from '@/api/product/product.js';
import CategoryResource from '@/api/product/category.js';
import GridView from 'Components/GridView';
import ListBase from 'Components/GridView/Base';

const productResource = new ProductResource();
const categoryResource = new CategoryResource();

export default {
  name: 'ProductList',
  components: {
    GridView,
  },
  extends: ListBase,
  data() {
    return {
      filters: [
        { type: 'input', label: 'Name', queryKey: 'name@like', span: 12 },
        { type: 'autocomplete', label: 'Category', queryKey: 'category.name@like', resource: categoryResource, span: 12 },
      ],
      buttons: [
        { type: 'success', label: 'New Product', clickEvent: this.handleAdd },
      ],
      columns: [
        { id: 'product-id', type: 'string', label: 'Product ID', prop: 'id', sortable: 'custom' },
        { id: 'product-name', type: 'link', label: 'Name', prop: 'name', minWidth: '140px', sortable: 'custom', clickEvent: this.handleEdit },
        { id: 'product-category', type: 'string', label: 'Category', minWidth: '140px', prop: 'category' },
        { id: 'product-price', type: 'string', label: 'Price', prop: 'price', sortable: 'custom' },
        { id: 'product-sort-order', type: 'string', label: 'Sort Order', prop: 'sort_order', sortable: 'custom' },
        { id: 'product-buttons', type: 'button', label: 'Options', prop: 'title', minWidth: '100px',
          buttons: [
            { name: 'Edit Product', icon: 'el-icon-edit', type: 'primary', class: '', style: '', clickEvent: this.handleEdit },
            { name: 'Delete Product', icon: 'el-icon-delete', type: 'danger', clickEvent: this.handleDelete },
          ],
        },
      ],
      resource: productResource,
    };
  },
  created() {
  },
  methods: {
    getBasicRoute() {
      return 'product';
    },
    getResource() {
      return productResource;
    },
    reloadList() {
      this.$refs.productGridView.handleFilter();
    },
  },
};
</script>
