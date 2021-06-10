<template>
  <div class="login-container">
    <el-form ref="userForm" :model="userForm" :rules="loginRules" class="login-form" auto-complete="on" label-position="left">
      <h3 class="title">Sign Up</h3>
      <el-form-item prop="email" :error="errors.email ? errors.email[0] : ''">
        <span class="svg-container">
          <svg-icon icon-class="email" />
        </span>
        <el-input v-model="userForm.email" name="email" type="text" auto-complete="on" placeholder="Email" />
      </el-form-item>
      <el-form-item prop="name" :error="errors.name ? errors.name[0]:''">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input v-model="userForm.name" name="name" type="text" auto-complete="on" placeholder="Name" />
      </el-form-item>
      <el-form-item prop="password" :error="errors.password ? errors.password[0]:''">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          v-model="userForm.password"
          :type="pwdType"
          name="password"
          auto-complete="on"
          placeholder="Password"
          @keyup.enter.native="handleLogin"
        />
        <span class="show-pwd" @click="showPwd">
          <svg-icon icon-class="eye" />
        </span>
      </el-form-item>
      <el-form-item prop="password_confirmation" :error="errors.confirmation_password ? errors.confirmation_password[0] : ''">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          v-model="userForm.password_confirmation"
          :type="pwdType"
          name="confirm-password"
          auto-complete="on"
          placeholder="Confirm password"
          @keyup.enter.native="handleSignUp"
        />
        <span class="show-pwd" @click="showPwd">
          <svg-icon icon-class="eye" />
        </span>
      </el-form-item>
      <el-form-item>
        <el-button :loading="loading" type="success" style="width:100%;" @click.native.prevent="handleSignUp">Submit</el-button>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" style="width:100%;" @click.native.prevent="handleSignIn">Sign In</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { register } from '@/api/auth';

export default {
  name: 'Register',
  data() {
    var validatePass = (rule, value, callback) => {
      if (value !== this.userForm.password) {
        callback(new Error('Confirmation password is invalid'));
      } else {
        callback();
      }
    };
    return {
      userForm: {
        email: '',
        name: '',
        password: '',
        password_confirmation: '',
      },
      loginRules: {
        email: [
          { required: true, message: 'Please enter your email', trigger: 'blur' },
          { type: 'email', message: 'Please enter the correct email', trigger: ['blur', 'change'] },
          { min: 5, max: 50, message: '5 to 50 characters in length', trigger: ['blur', 'change'] },
        ],
        name: [
          { required: true, message: 'Please enter your full name', trigger: 'blur' },
          { min: 5, max: 20, message: '5 to 50 characters in length', trigger: ['blur', 'change'] },
        ],
        password: [
          { required: true, message: 'Please enter your password', trigger: 'blur' },
          { min: 5, max: 20, message: '5 to 20 characters in length', trigger: ['blur', 'change'] },
        ],
        password_confirmation: [
          { validator: validatePass, trigger: ['blur', 'change'] },
        ],
      },
      errors: {},
      loading: false,
      pwdType: 'password',
      redirect: undefined,
    };
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect;
      },
      immediate: true,
    },
  },
  methods: {
    showPwd() {
      if (this.pwdType === 'password') {
        this.pwdType = '';
      } else {
        this.pwdType = 'password';
      }
    },
    handleSignUp() {
      this.$refs.userForm.validate(valid => {
        if (valid) {
          this.loading = true;
          register(this.userForm)
            .then(response => {
              if (typeof response.code !== 'undefined' && response.code !== 200) {
                this.errors = response.data.errors;
              } else {
                this.$router.push({ path: this.redirect || '/', query: this.otherQuery }, onAbort => {});
              }
              this.loading = false;
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          console.log('error submit!');
          return false;
        }
      });
    },
    handleSignIn() {
      this.$router.push({ path: '/login' });
    },
    handleSendCode() {
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss">
  $bg:#3a503d;
  $light_gray:#eee;

  /* reset element-ui css */
  .login-container {
    .el-input {
      display: inline-block;
      height: 47px;
      width: 85%;
      input {
        background-color: transparent;
        border: 0px;
        -webkit-appearance: none;
        border-radius: 0px;
        padding: 12px 5px 12px 15px;
        color: $light_gray;
        height: 47px;
        &:-webkit-autofill {
          -webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
          -webkit-text-fill-color: #fff !important;
        }
      }
    }
    .el-form-item {
      border: 1px solid rgba(255, 255, 255, 0.1);
      background: rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      color: #454545;
    }
  }

</style>

<style rel="stylesheet/scss" lang="scss" scoped>
  $bg:#3a503d;
  $dark_gray:#889aa4;
  $light_gray:#eee;
  .login-container {
    position: fixed;
    height: 100%;
    width: 100%;
    background-color: $bg;
    .login-form {
      position: absolute;
      left: 0;
      right: 0;
      width: 520px;
      max-width: 100%;
      padding: 35px 35px 15px 35px;
      margin: 120px auto;
    }
    .tips {
      font-size: 14px;
      color: #fff;
      margin-bottom: 10px;
      span {
        &:first-of-type {
          margin-right: 16px;
        }
      }
    }
    .svg-container {
      padding: 6px 5px 6px 15px;
      color: $dark_gray;
      vertical-align: middle;
      width: 30px;
      display: inline-block;
    }
    .title {
      font-size: 26px;
      font-weight: 400;
      color: $light_gray;
      margin: 0px auto 40px auto;
      text-align: center;
      font-weight: bold;
    }
    .show-pwd {
      position: absolute;
      right: 10px;
      top: 7px;
      font-size: 16px;
      color: $dark_gray;
      cursor: pointer;
      user-select: none;
    }
  }
</style>
