<template>
  <div class="app-container">
    <el-form ref="categoryForm" :rules="categoryRules" :model="categoryForm" label-width="250px">
      <el-form-item label="Category Name" prop="name">
        <el-input v-model="categoryForm.name" placeholder="Please enter Category Name" />
      </el-form-item>
      <el-form-item label="Sort Order" prop="sortOrder">
        <el-input v-model.number="categoryForm.sort_order" placeholder="Please enter Sort Order" />
      </el-form-item>
      <el-form-item>
        <el-button @click="goBack">Go Back</el-button>
        <el-button type="primary" @click="confirmCategoryForm">Confirm</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import CategoryResource from '@/api/product/category.js';

const categoryResource = new CategoryResource();
const defaultCategory = {
  name: '',
  sort_order: 100,
};

export default {
  name: 'CategoryForm',
  data() {
    return {
      categoryForm: Object.assign({}, defaultCategory),
      categoryRules: {
        name: [
          { required: true, message: 'Please enter Category Name', trigger: 'blur' },
          { min: 2, max: 50, message: '2 to 50 characters in length', trigger: ['blur', 'change'] },
        ],
      },
      reload: true,
      resource: categoryResource,
    };
  },
  created() {
    this.loadData();
  },
  methods: {
    isAdd() {
      return this.$route.name === 'category-add';
    },
    getId() {
      return Number(this.$route.params && this.$route.params.id);
    },
    goBack() {
      this.$router.push({ path: '/product/category/list' });
    },
    async loadData() {
      if (this.isAdd()) {
        return;
      }
      const id = this.getId();
      const { code, msg, data } = await categoryResource.get(id);
      if (code !== 200) {
        this.$message({
          message: msg || ('Fail to load category info'),
          type: 'error',
        });
      } else {
        this.categoryForm = {
          name: data.name || '',
          sort_order: data.sort_order || 100,
        };
      }
    },
    confirmCategoryForm() {
      this.$refs.categoryForm.validate(async valid => {
        if (valid) {
          const type = this.isAdd() ? 'add' : 'edit';
          const { code, msg } = this.isAdd() ? await categoryResource.store(this.categoryForm) : await categoryResource.update(this.getId(), this.categoryForm);
          if (code !== 200) {
            this.$message({
              message: msg || ('Fail to ' + type + ' Category'),
              type: 'error',
            });
          } else {
            this.$message({
              message: msg || ('Success to ' + type + ' Category'),
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
