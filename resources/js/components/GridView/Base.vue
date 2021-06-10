<script>
export default {
  name: 'ListBase',
  data() {
    return {
      pager: { page: 1, pageSize: 20 },
    };
  },
  methods: {
    // ----------- interface methods ----------------
    getBasicRoute() {
      // should be overrided by children
      return '';
    },
    getResource() {
      // should be overrided by children
      return null;
    },
    reloadList() {
      // should be overrided by children
      return {};
    },

    // -------------- reusable methods -----------------
    getEditRoute(basicRoute, row) {
      return '/' + basicRoute + '/edit/' + row.id;
    },
    getViewRoute(basicRoute, row) {
      return '/' + basicRoute + '/detail/' + row.id;
    },
    getAddRoute(basicRoute) {
      return '/' + basicRoute + '/add/';
    },
    handleView(row) {
      this.$nextTick(() => this.$router.push({
        path: this.getViewRoute(this.getBasicRoute(), row),
      }));
    },
    handleEdit(row) {
      this.$nextTick(() => this.$router.push({
        path: this.getEditRoute(this.getBasicRoute(), row),
      }));
    },
    handleAdd() {
      this.$nextTick(() => this.$router.push({
        path: this.getAddRoute(this.getBasicRoute()),
      }));
    },
    handleDelete(row) {
      this.$confirm('Are you sure to DELETE [' + row.name + ']?', 'notice', {
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        type: 'warning',
      }).then(async() => {
        const resource = this.getResource();
        if (!resource) {
          return;
        }
        const { code, msg, errors } = await resource.destroy(row.id);
        this.showResult(code, msg, errors);
        this.reloadList();
        this.$emit('delete', row);
      }).catch(() => {
        this.showResult(500, 'Internal Error, Delete Fail');
      });
    },
    showResult(code, msg, errors) {
      const success = code === 200;

      if (success) {
        this.$message({
          message: msg || 'Success',
          type: 'success',
        });
        return;
      }

      msg = msg || 'Fail';
      if (errors) {
        for (var key in errors) {
          msg += '<br/>' + errors[key];
        }
      }
      this.$message({
        dangerouslyUseHTMLString: true,
        message: msg,
        type: 'error',
      });
    },
    getId() {
      return parseInt(this.$route.params && this.$route.params.id);
    },
  },
};
</script>
