<template>
  <div class="app-container">
    <el-form ref="productForm" :rules="productRules" :model="productForm" label-width="250px">
      <el-form-item label="Product Name" prop="name">
        <el-input v-model="productForm.name" placeholder="Please enter Product Name" />
      </el-form-item>
      <el-form-item label="Price" prop="price">
        <el-input v-model.number="productForm.price" placeholder="Please enter Price" />
      </el-form-item>
      <el-form-item label="Category" prop="tax_id">
        <multi-select :value.sync="productForm.category_ids" :resource="categoryResource" :items="selectCategories" :selected-items="selectCategories" label="Category" multi />
      </el-form-item>
      <el-form-item label="Sort Order" prop="sortOrder">
        <el-input v-model.number="productForm.sort_order" placeholder="Please enter Sort Order" />
      </el-form-item>
      <el-form-item>
        <el-button @click="goBack">Go Back</el-button>
        <el-button type="primary" @click="confirmProductForm">Confirm</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import ProductResource from '@/api/product/product.js';
import CategoryResource from '@/api/product/category.js';
import MultiSelect from 'Components/Form/MultiSelect';

const productResource = new ProductResource();
const categoryResource = new CategoryResource();
const defaultProduct = {
  name: '',
  price: 0,
  category_ids: [],
  sort_order: 100,
};

export default {
  name: 'ProductForm',
  components: {
    MultiSelect,
  },
  data() {
    return {
      productForm: Object.assign({}, defaultProduct),
      productRules: {
        name: [
          { required: true, message: 'Please enter Product Name', trigger: 'blur' },
          { min: 5, max: 50, message: '5 to 50 characters in length', trigger: ['blur', 'change'] },
        ],
        price: [
          { required: true, message: 'Please enter Price', trigger: 'blur' },
        ],
      },
      reload: true,
      resource: productResource,
      categoryResource: categoryResource,
      selectCategories: [],
    };
  },
  created() {
    this.loadData();
  },
  methods: {
    isAdd() {
      return this.$route.name === 'product-add';
    },
    getId() {
      return Number(this.$route.params && this.$route.params.id);
    },
    goBack() {
      this.$router.push({ path: '/product/list' });
    },
    async loadData() {
      if (this.isAdd()) {
        return;
      }
      const id = this.getId();
      const { code, msg, data } = await productResource.get(id);
      if (code !== 200) {
        this.$message({
          message: msg || ('Fail to load product info'),
          type: 'error',
        });
      } else {
        this.productForm = {
          name: data.name || '',
          sort_order: data.sort_order || 100,
          price: data.price || 0,
        };
        this.selectCategories = data.category || [];
      }
    },
    confirmProductForm() {
      this.$refs.productForm.validate(async valid => {
        if (valid) {
          const type = this.isAdd() ? 'add' : 'edit';
          const { code, msg } = this.isAdd() ? await productResource.store(this.productForm) : await productResource.update(this.getId(), this.productForm);
          if (code !== 200) {
            this.$message({
              message: msg || ('Fail to ' + type + ' Product'),
              type: 'error',
            });
          } else {
            this.$message({
              message: msg || ('Success to ' + type + ' Product'),
              type: 'success',
            });
            this.goBack();
          }
        } else {
          console.log('error submit!');
          return false;
        }
      });
    },
  },
};
</script>
