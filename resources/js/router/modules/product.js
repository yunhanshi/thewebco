import Layout from '@/layout';

const productRoutes = {
  path: '/product',
  component: Layout,
  redirect: '/product/list',
  name: 'product',
  meta: {
    title: 'Product List',
    icon: 'dashboard',
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/product/ProductList'),
      name: 'order-list',
      meta: {
        title: 'Products',
      },
    },
    {
      path: 'edit/:id(\\d+)',
      component: () => import('@/views/product/ProductForm'),
      name: 'product-edit',
      meta: {
        title: 'Edit Product',
        noCache: true,
      },
      hidden: true,
    },
    {
      path: 'add',
      component: () => import('@/views/product/ProductForm'),
      name: 'product-add',
      meta: {
        title: 'Add Product',
        noCache: true,
      },
      hidden: true,
    },
    {
      path: 'category/list',
      component: () => import('@/views/product/CategoryList'),
      name: 'category-list',
      meta: {
        title: 'Categories',
        permissions: ['view categorylist'],
      },
    },
    {
      path: 'category/edit/:id(\\d+)',
      component: () => import('@/views/product/CategoryForm'),
      name: 'category-edit',
      meta: {
        title: 'Edit Category',
        noCache: true,
        permissions: ['view categoryedit'],
      },
      hidden: true,
    },
    {
      path: 'category/add',
      component: () => import('@/views/product/CategoryForm'),
      name: 'category-add',
      meta: {
        title: 'Add Category',
        noCache: true,
        permissions: ['view categoryadd'],
      },
      hidden: true,
    },
  ],
};
export default productRoutes;
