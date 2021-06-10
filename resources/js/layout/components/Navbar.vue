<template>
  <div class="navbar">
    <div class="title">
      <div class="navItems">
        <el-image src="/favicon/favicon.png" class="logo" />
        <el-link class="navItem" type="info" @click="goProduct">Products</el-link>
        <el-link class="navItem" type="info" @click="goCategory">Categories</el-link>
      </div>
    </div>
    <div class="right-menu">
      <el-dropdown class="name-container right-menu-item hover-effect" trigger="click">
        <div class="name-wrapper">
          <span>Hello, {{ name }} <i class="el-icon-arrow-down" /></span>
        </div>
        <el-dropdown-menu slot="dropdown">
          <a target="_blank" href="https://github.com/yunhanshi/thewebco">
            <el-dropdown-item>GitHub</el-dropdown-item>
          </a>
          <el-dropdown-item divided>
            <span style="display:block;" @click="logout">Log Out</span>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  computed: {
    ...mapGetters([
      'sidebar',
      'name',
      'avatar',
      'device',
      'userId',
    ]),
  },
  methods: {
    async logout() {
      await this.$store.dispatch('user/logout');
      this.$router.push(`/login?redirect=${this.$route.fullPath}`);
    },
    goProduct() {
      if (this.$route.path !== '/product/list') {
        this.$router.push({ path: '/product/list' });
      }
    },
    goCategory() {
      if (this.$route.path !== '/product/category/list') {
        this.$router.push({ path: '/product/category/list' });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.navbar {
  height: 50px;
  overflow: hidden;
  position: relative;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0,21,41,.08);
  .title {
    display: inline-block;
    font-size: 15px;
    line-height: 50px;
    margin-left: 10px;
    .navItems {
      display: flex;
      .logo {
        height: 50px;
        width: 50px;
        margin-left: 20px;
      }
      .navItem {
        margin-left: 20px;
      }
    }
  }
  .right-menu {
    float: right;
    height: 100%;
    line-height: 50px;

    &:focus {
      outline: none;
    }

    .name-container {
      margin-right: 30px;

      .name-wrapper {
        position: relative;
        cursor: pointer;
        span {
          font-size: 15px;
        }
      }
    }
  }
}
</style>
