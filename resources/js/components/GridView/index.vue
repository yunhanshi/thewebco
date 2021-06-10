<template>
  <div class="app-container">
    <div v-if="filters.length || buttons.length" class="filter-container">
      <el-form :inline="true" label-width="100px">
        <el-row v-for="(rowFilters, row) in filterRows" :key="row" :gutter="20">
          <el-col v-for="(filter, index) in rowFilters" :key="index" :span="filter.span?filter.span:8">
            <el-form-item :label="filter.label" label-position="right">
              <template v-if="[ 'input', 'select', 'autocomplete', 'multi-select' ].includes(filter.type)">
                <component :is="filter.type+'-filter'" :key="index" :value.sync="query.filter[filter.queryKey]" :filter="filter" clearable />
              </template>
              <template v-else-if="[ 'date-picker' ].includes(filter.type)">
                <component :is="filter.type+'-filter'" :key="index" :value.sync="tmp.datepickerData[filter.queryKey]" :filter="filter" clearable @input="handleDatepickerInput(filter.queryKey, $event)" />
              </template>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <div v-if="filters.length" class="filter-button">
            <el-button class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">Search</el-button>
            <el-button class="filter-item" type="warning" icon="el-icon-refresh-right" @click="handleReset">Reset</el-button>
          </div>
          <div class="option-button">
            <template v-for="(button, index) in buttons">
              <template v-if="button.options">
                <el-dropdown :key="'button-'+index">
                  <el-button :class="button.class" :style="button.style" :type="button.type" :icon="button.icon" @click="handleButtonEvent(button.clickEvent)">{{ button.label }}<i class="el-icon-arrow-down el-icon--right" /></el-button>
                  <el-dropdown-menu slot="dropdown">
                    <template v-for="(option, key) in button.options">
                      <el-dropdown-item :key="'button-'+index+'-'+key" :icon="option.icon" @click.native="handleButtonEvent(option.clickEvent)">{{ option.label }}</el-dropdown-item>
                    </template>
                  </el-dropdown-menu>
                </el-dropdown>
              </template>
              <template v-else>
                <el-button :key="'button-'+index" :class="button.class" :style="button.style" :type="button.type" :icon="button.icon" @click="handleButtonEvent(button.clickEvent)">{{ button.label }}</el-button>
              </template>
            </template>
          </div>
        </el-row>
      </el-form>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="data"
      border
      fit
      highlight-current-row
      style="width: 100%;"
      @sort-change="handleSortChange"
      @selection-change="handleSelectionChange"
    >
      <el-table-column v-if="selection" key="selection" type="selection" width="55" />
      <template v-for="(column, index) in columns">
        <component :is="column.type+'-column'" :key="'column-'+index" :column="column" :sortable="column.sortable" />
      </template>

    </el-table>

    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.page_size"
      @pagination="handleTurningPage"
    />
  </div>
</template>

<script>
import StringColumn from './ColumnType/StringColumn';
import LinkColumn from './ColumnType/LinkColumn';
import ButtonColumn from './ColumnType/ButtonColumn';
import ImageColumn from './ColumnType/ImageColumn';
import InputColumn from './ColumnType/InputColumn';
import Pagination from 'Components/Pagination';
import InputFilter from './FilterType/InputFilter';
import SelectFilter from './FilterType/SelectFilter';
import MultiSelectFilter from './FilterType/MultiSelectFilter';
import DatePickerFilter from './FilterType/DatePickerFilter';
import AutocompleteFilter from './FilterType/AutocompleteFilter';
import Resource from '@/api/resource';

const sortMap = {
  ascending: 'asc',
  descending: 'desc',
};

var FILTERS_PER_ROW = 3;

export default {
  name: 'ComplexTable',
  components: {
    Pagination,
    StringColumn,
    LinkColumn,
    ButtonColumn,
    ImageColumn,
    InputColumn,
    InputFilter,
    SelectFilter,
    MultiSelectFilter,
    DatePickerFilter,
    AutocompleteFilter,
  },
  /** 需要传入的参数 */
  props: {
    tableKey: {
      type: String,
      default: () => 'gridview',
    },
    /** 是否可选择数据行 */
    selection: {
      type: Boolean,
      default: () => false,
    },
    /** 需要绘制的筛选条件 */
    filters: {
      type: Array,
      default: () => [],
    },
    /** filter列数 */
    filterColumn: {
      type: Number,
      default: () => FILTERS_PER_ROW,
    },
    /** 需要绘制的功能按钮 */
    buttons: {
      type: Array,
      default: () => [],
    },
    /** 需要绘制的列 */
    columns: {
      type: Array,
      default: () => [],
    },
    /** 分页信息 */
    pager: {
      type: Object,
      default() {
        return { page: 1, pageSize: 10 };
      },
    },
    /** apiResource, 会调用list方法加载数据 */
    resource: {
      type: Resource,
      default: () => {},
    },
    /** 预设的查询参数 */
    preQuery: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      data: [],
      total: 0,
      /** query 保存查询条件，用来拼接请求的参数 */
      query: {
        filter: {}, // 筛选条件
        sort: {}, // 排序规则
        page: 1, // 当前页码
        page_size: 10, // 页容量
      },
      listLoading: true,
      selectedRows: [], // 已选择的行
      tmp: {
        datepickerData: {}, // 时间筛选组件临时数据
      },
    };
  },
  computed: {
    filterRows() {
      const filterColumn = this.filterColumn <= 0 ? FILTERS_PER_ROW : this.filterColumn;
      const rows = {};
      for (let i = 0; i < this.filters.length; i++) {
        const row = Math.floor(i / filterColumn);
        if (!rows[row]) {
          rows[row] = [];
        }
        rows[row].push(this.filters[i]);
      }

      return rows;
    },
  },
  created() {
    this.query.filter = { ...this.preQuery, ...this.query.filter };
    this.query.page = this.pager.page ? this.pager.page : 1;
    this.query.page_size = this.pager.pageSize ? this.pager.pageSize : 10;
    this.renderList(this.query);
  },
  methods: {
    async renderList(query) {
      this.$emit('clickFilter', query.filter);
      if (this.resource instanceof Resource) {
        this.listLoading = true;
        const { data, meta } = await this.resource.list(query);
        this.data = data;
        this.total = meta === undefined ? 0 : meta.total;
        this.query.page = meta === undefined ? 0 : meta.current_page;
        this.query.page_size = meta === undefined ? 0 : meta.per_page;
        this.listLoading = false;
      }
    },

    handleFilter() {
      this.renderList(this.query);
    },

    handleReset() {
      this.query = {
        filter: {},
        sort: {},
        page: 1,
        page_size: this.pager.pageSize ? this.pager.pageSize : 10,
      };
      this.renderList(this.query);
    },

    handleSelectionChange(val) {
      this.selectedRows = val;
    },

    handleSortChange(data) {
      if (sortMap[data.order]) {
        this.query['sort'] = {};
        this.query['sort'][data.prop] = sortMap[data.order];
      }
      this.renderList(this.query);
    },

    handleTurningPage(data) {
      this.query = { ...this.query, page: data.page, page_size: data.limit };
      this.renderList(this.query);
    },

    handleButtonEvent(clickEvent) {
      if (clickEvent) {
        clickEvent(this.selectedRows);
      }
    },
    handleDatepickerInput(queryKey, event) {
      if (event instanceof Array && event.length === 2) {
        this.query.filter[queryKey + '@gt'] = event[0];
        this.query.filter[queryKey + '@le'] = event[1];
      } else {
        delete this.query.filter[queryKey + '@gt'];
        delete this.query.filter[queryKey + '@le'];
      }
    },
    getPlaceholder(filter) {
      if (filter.placeholder) {
        return filter.placeholder;
      }
      return 'Please enter ' + filter.label;
    },
  },
};
</script>
