<style lang='less'>
.structure {
  margin: 20px;
  > :nth-child(2) {
    margin-top: 20px;
  }
}
</style>

<template>
  <div class="structure">
      <el-button
      size="mini"
      type="success"
      icon="el-icon-plus"
      @click="openCreateView(pureForm, 'create')"
      >新增客户端</el-button>
    <el-table
      ref="multipleTable"
      :data="tableData"
      :border="true"
      tooltip-effect="dark"
      style="width: 100%;"
      @selection-change="handleSelectionChange"
    >
      <el-table-column type="selection" width="55"></el-table-column>
      <el-table-column prop="id" label="ID" sortable width="100"></el-table-column>
      <el-table-column label="名称">
        <template slot-scope="scope">{{ scope.row.name }}</template>
      </el-table-column>
      <el-table-column label="设备类型">
        <template slot-scope="scope">{{ scope.row.type }}</template>
      </el-table-column>
      <el-table-column label="隧道">
        <template slot-scope="scope">{{ scope.row.channel }}</template>
      </el-table-column>
      <el-table-column label="设备状态">
        <template slot-scope="scope"> <el-tag :type="isOnline(scope.row.is_online)">{{ scope.row.is_online ==1 ? '在线' : '离线' }} </el-tag></template>
      </el-table-column>
      <el-table-column label="描述">
        <template slot-scope="scope">{{ scope.row.description }}</template>
      </el-table-column>
      <el-table-column label="操作" width="300" right>
        <template slot-scope="scope">
          <el-popover placement="right" width="auto" trigger="click" v-model="scope.row.visible">
            <p>警告：删除后将无法恢复！</p>
            <div style="text-align: right; margin: 0">
              <el-button
                type="warning"
                size="mini"
                @click="handleDelete(scope.row.id)"
              >确定</el-button>
              <el-button size="mini" type="primary" @click="scope.row.visible = false">取消</el-button>
            </div>
            <el-button size="mini" type="danger" icon="el-icon-delete" slot="reference">删除</el-button>
          </el-popover>
          <el-button
            size="mini"
            type="primary"
            icon="el-icon-edit-outline"
            @click="openCreateView(scope.row, 'update')"
          >编辑</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog width="40%" :title="title" :visible.sync="dialogFormVisible">
      <el-form label-position="left" :model="form">
        <el-row :gutter="20">
        <el-col :span="16">
          <el-form-item label="名称" :label-width="formLabelWidth">
          <el-input v-model="form.name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="设备类型" :label-width="formLabelWidth">
          <el-input v-model="form.type" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="隧道" :label-width="formLabelWidth">
        {{ form.channel }}
        </el-form-item>
         <el-form-item label="描述" :label-width="formLabelWidth">
          <el-input v-model="form.description" autocomplete="off"></el-input>
        </el-form-item>
        </el-col>
        <el-col :span="6">
        </el-col>
      </el-row>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">取 消</el-button>
        <el-button type="primary" @click="handleCreate">确 定</el-button>
      </div>
    </el-dialog>

    <!-- <el-pagination
      @size-change="handleSizeChange"
      @current-change="handleCurrentChange"
      :current-page="page.currentPage"
      :page-sizes="page.choosePagesize"
      :page-size="page.pagesize"
      style="margin-top:20px;"
      layout="total, sizes, prev, pager, next, jumper"
      :total="page.count">
    </el-pagination> -->
  </div>
</template>

<script>
import api from '@/api/client'
import tableData from '@/mixins/tableData'
export default {
  mixins: [tableData],
  data() {
    return {
      api: api,
      tableData: [],
      roles: [],
      form: {
        id: '',
        name: '',
        type: '',
        description: '',
        channel: '',
        is_online: ''
      },
    }
  },
  created() {

  },
  methods: {
    openCreateView(row, type) {
      this.dialogFormVisible = true
      this.form = Object.assign({}, row)
      this.form.status += ''
      switch (type) {
        case 'create':
          this.title = '新增'
          this.submit = type
          break
        case 'update':
          this.title = '修改'
          this.submit = type
          break
      }
    },
    handleCreate() {
      let that = this
      console.log(this.submit)
      switch (this.submit) {
        case 'create':
          api.create(this.form).then(res => {
            this.retrieve()
            that.dialogFormVisible = false;
          })
          break
        case 'update':
          api.update(this.form).then(res => {
            this.retrieve()
            that.dialogFormVisible = false
          })
          break
      }
    }
  }
};
</script>
