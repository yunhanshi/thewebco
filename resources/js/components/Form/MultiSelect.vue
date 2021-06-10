<template>
  <el-select
    v-model="selected"
    :disabled="disabled"
    :multiple="multi"
    :clearable="clearable"
    :filterable="filterable"
    remote
    reserve-keyword
    :placeholder="label"
    :remote-method="querySearchAsync"
    :loading="loading"
    autocomplete="off"
    @change="selectTrigger(selected)"
  >
    <!-- non-group -->
    <template v-if="group == false">
      <el-option
        v-for="item in options"
        :key="item.value"
        :label="translatable?$t(item.label):item.label"
        :value="item.value"
      />
    </template>
    <!-- group -->
    <template v-else-if="group == true">
      <el-option-group v-for="g in options" :key="g.label" :label="$t(g.label)">
        <el-option v-for="item in g.children" :key="item.value.value" :label="$t(item.label)" :value="item.value" />
      </el-option-group>
    </template>
  </el-select>
</template>

<script>
import Resource from '@/api/resource';

export default {
  name: 'MultiSelect',
  model: {
    prop: 'value',
    event: 'update',
  },
  props: {
    resource: { type: Object, default: () => {} },
    displayKey: { type: String, default: () => '' },
    label: { type: String, default: () => '' },
    value: { type: [String, Number, Array], default: '' },
    option: { type: Array, default: () => [] },
    items: { type: [Array, Object], default: () => [] },
    selectedItems: { type: [Array, Object, Number], default: () => [] },
    multi: { type: Boolean, default: () => false },
    filterable: { type: Boolean, default: () => true },
    preFilter: { type: Object, default: () => {} },
    disabled: { type: Boolean, default: () => false },
    clearable: { type: Boolean, default: () => false },
    translatable: { type: Boolean, default: () => true },
    group: { type: Boolean, default: () => false },
  },
  data() {
    return {
      options: [],
      selected: this.value,
      loading: false,
    };
  },
  watch: {
    value() {
      this.selected = this.value;
    },
    option() {
      this.initOption();
    },
    items() {
      this.options = this.composeOptions(this.items);
    },
    selectedItems() {
      this.selected = this.composeSelected(this.selectedItems);
      this.selectTrigger(this.selected);
    },
  },
  created() {
    this.initOption();
  },
  methods: {
    initOption() {
      this.options = [];
      this.option.forEach((item) => {
        this.options.push(item);
      });
    },
    async querySearchAsync(queryString) {
      this.loading = true;
      if (this.resource instanceof Resource) {
        let filter = { 'name@like': queryString };
        if (this.preFilter instanceof Object) {
          filter = Object.assign(filter, this.preFilter);
        }
        const { data } = await this.resource.autocomplete({ filter: filter });
        this.options = data;
      }
      this.loading = false;
    },
    selectTrigger(val) {
      this.$emit('update:value', val);
      this.$emit('change', val);
    },
    composeSelected(items) {
      if (Array.isArray(items) && this.multi) {
        // multi
        if (!items || items.length === 0) {
          return [];
        }
        var res = [];
        items.forEach((item) => {
          res.push(item.id);
        });
        return res;
      }

      // single
      if (!items || items.length === 0) {
        return '';
      }
      var item = items;
      if (typeof item === 'object') {
        return item.id;
      }

      return item;
    },
    composeOptions(items) {
      if (!items) {
        return [];
      }

      var res = [];
      if (Array.isArray(items)) {
        // multi
        if (!items || items.length === 0) {
          return [];
        }
        items.forEach((item) => {
          res.push({
            value: item.id,
            label: this.displayKey ? item[this.displayKey] : item.name,
          });
        });
        return res;
      }

      // single
      var item = items;
      if (typeof item === 'object') {
        return [{
          value: item.id,
          label: this.displayKey ? item[this.displayKey] : item.name,
        }];
      }

      return [item];
    },
  },
};
</script>
