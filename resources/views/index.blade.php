<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>控制中心 - 微信自动回复管理</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset(mix('/css/element.css', 'vendor/wechat-reply')) }}">
    <style>
        [v-cloak] {
            display: none
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #app {
            box-sizing: border-box;
            width: 900px;
            height: 100%;
            margin: 0 auto;
            padding: 60px 0 20px;
        }

        .navbar {
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            box-sizing: border-box;
            width: 100%;
            height: 60px;
            padding: 0 20px;
            background: #fff;
            box-shadow: 0 1px 5px 0 rgba(0, 0, 0, .2);
        }

        .nvabar__logout {
            color: #F56C6C;
        }

        .nvabar__logout:hover {
            color: #dd6161;
        }

        .top {
            padding: 20px 0;
            border-bottom: 1px solid #ccc;
        }

        .top h3 {
            margin: 0;
        }

        .pagination {
            padding-top: 20px;
            text-align: center;
        }

        .buttons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
        }

        .buttons .buttons__tips {
            font-size: 16px;
            color: #dcdfe6;
            vertical-align: sub;
            display: inline;
        }

        .buttons__search_input {
            width: 260px;
        }

        .el-form-item__label {
            color: #000;
            font-weight: 600;
        }

        .ruleEditDialog .el-form-item__content {
            display: flex;
            align-items: center;
            height: 40px;
        }

        .flexnone .el-form-item__content {
            align-items: flex-start;
            height: auto;
        }

        .ruleEditDialog__item {
            display: flex;
            align-items: center;
            height: 40px;
            width: 660px;
        }

        .ruleDetailDialog__replies {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .ruleDetailDialog__replies_text {
            width: 660px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .ruleEditDialog__select {
            width: 100px;
        }

        .ruleEditDialog__add,
        .ruleEditDialog__minus {
            margin-left: 10px;
        }

        .ruleEditDialog__replies {
            list-style: none;
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .ruleEditDialog__replies li {
            padding: 0 8px;
            cursor: pointer;
            color: #ccc;
        }

        .ruleEditDialog__replies li.enable {
            color: #000;
        }

        .ruleEditDialog__replies li.enable:hover {
            color: #409EFF;
        }
    </style>

    <script type="text/javascript" src="{{asset(mix('/js/vue.js', 'vendor/wechat-reply'))}}"></script>
    <script type="text/javascript" src="{{asset(mix('/js/element.js', 'vendor/wechat-reply'))}}"></script>
    <script type="text/javascript" src="{{asset(mix('/js/axios.min.js', 'vendor/wechat-reply'))}}"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a>
    to improve your experience.</p>
<![endif]-->

<div id="app" v-cloak>
    <div class="navbar">
        <a href="{{ route('wechat-reply.logout') }}">
            <el-button type="text" class="nvabar__logout">退出登录</el-button>
        </a>
    </div>

    <div class="top" style="display: flex;justify-content: space-between;">
        <h3>自动回复</h3>
        <el-button type="text" @click="skipMenuEidt">自定义菜单编辑</el-button>
    </div>
    <div class="bottom">

        <div class="buttons">
            <div>
                <el-input placeholder="请输入规则名称/关键词" v-model="searchName" size="mini" class="buttons__search_input">
                    <el-button slot="append" icon="el-icon-search" @click="getRules"
                               class="buttons__search_icon"></el-button>
                </el-input>
                <el-tooltip class="item" effect="dark" content="使用介绍：自动回复使用的关键词" placement="top-start">
                    <i class="el-icon-question buttons__tips"></i>
                </el-tooltip>
            </div>
            <el-button type="success" size="mini" @click="addRules">新增规则</el-button>
        </div>

        <el-table :data="rulesData" style="width: 100%">
            <div slot="empty">
                没有匹配结果，请重新输入关键字或
                <el-button type="text" @click="searchName='';getRules();">查看全部</el-button>
            </div>
            <el-table-column label="规则名称" prop="rule_name"></el-table-column>
            <el-table-column label="关键词" prop="keywords_string"></el-table-column>
            <el-table-column label="回复内容" width="180">
                <template slot-scope="scope">
                    ${scope.row.replies_count} 内容
                </template>
            </el-table-column>
            <el-table-column label="操作" width="140">
                <template slot-scope="scope">
                    <el-button size="mini" type="text" @click="showDetail(scope.row)">详情</el-button>
                    <el-button size="mini" type="text" @click="editRule(scope.row)">编辑</el-button>
                    <el-button size="mini" type="text" @click="delRule(scope.row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-pagination layout="prev, pager, next" :total="pagination.total" :current-page="pagination.page"
                       class="pagination">
        </el-pagination>
    </div>

    <!-- 详情弹框 S -->
    <el-dialog title="规则详情" :visible.sync="ruleDetailVisible" width="900px" class="ruleDetailDialog">
        <el-form label-width="100px">
            <el-form-item label="规则名称">${ruleDetail.rule_name}</el-form-item>
            <el-form-item label="关键词">
                ${ruleDetail.keywords | keywords2Str}
            </el-form-item>
            <el-form-item label="回复方式">${ruleDetail.reply_mode | replyMode2Str}</el-form-item>
            <el-form-item label="回复内容">
                <div v-for="(replies, index) in ruleDetail.replies" :key="index" class="ruleDetailDialog__replies">
                    <div v-if="replies.type === 'text'" class="ruleDetailDialog__replies_text">
                        ${replies.content}
                    </div>
                </div>
            </el-form-item>
        </el-form>
    </el-dialog>
    <!-- 详情弹框 E -->

    <!-- 编辑弹框 S -->
    <el-dialog :title="editType === 'add' ? '新增规则' : '编辑规则'" :model="ruleEdit" :visible.sync="ruleEditVisible"
               :close-on-click-modal="false" width="900px" class="ruleEditDialog">
        <el-form ref="ruleEditForm" :model="ruleEdit" label-width="100px">
            <el-form-item label="规则名称" prop="rule_name" :rules="formRules.rule_name">
                <div class="ruleEditDialog__item">
                    <el-input v-model="ruleEdit.rule_name" placeholder="输入规则名称，规则名最多60个字" size="mini"></el-input>
                </div>
            </el-form-item>
            <el-form-item v-for="(keyword, index) in ruleEdit.keywords" :key="index" label="关键词"
                          :prop="'keywords.'+index+'.keyword'" :rules="formRules.keyword">
                <div class="ruleEditDialog__item">
                    <el-input placeholder="请输入关键词" v-model="keyword.keyword" size="mini"
                              class="ruleEditDialog__keyword">
                        <el-select v-model="keyword.full_match" size="mini" slot="prepend"
                                   class="ruleEditDialog__select">
                            <el-option label="全匹配" :value="true"></el-option>
                            <el-option label="半匹配" :value="false"></el-option>
                        </el-select>
                    </el-input>
                </div>
                <el-button v-show="(ruleEdit.keywords.length - 1) === index" icon="el-icon-plus" size="mini" circle
                           @click="addKeyWord" class="ruleEditDialog__add"></el-button>
                <el-button v-show="ruleEdit.keywords.length > 1 && index !== 0" icon="el-icon-minus" size="mini"
                           type="danger" plain circle @click="minusKeyWord(index)"
                           class="ruleEditDialog__minus"></el-button>
            </el-form-item>
            <el-form-item label="回复内容" class="flexnone" :prop="'replies'" :rules="formRules.replies">
                <div>
                    <div v-for="(replies, index) in ruleEdit.replies" :key="index" class="ruleDetailDialog__replies">
                        <div v-if="replies.type === 'text'" class="ruleDetailDialog__replies_text">
                            ${replies.content}
                        </div>
                        <el-button icon="el-icon-edit" size="mini" circle plain class="ruleEditDialog__minus"
                                   @click="editReplies(replies.content, index)"></el-button>
                        <el-button icon="el-icon-delete" size="mini" type="danger" circle plain
                                   class="ruleEditDialog__minus" @click="minuReplies(index)"></el-button>

                    </div>
                    <el-popover placement="right" trigger="hover">
                        <el-button icon="el-icon-plus" size="mini" circle slot="reference"></el-button>
                        <ul class="ruleEditDialog__replies">
                            <li class="enable" @click="addReplies('text')">文字</li>
                            <li>图文</li>
                            <li>图片</li>
                            <li>音频</li>
                            <li>视频</li>
                        </ul>
                    </el-popover>
                </div>
            </el-form-item>
            <el-form-item label="回复方式">
                <div class="ruleEditDialog__item">
                    <el-radio-group v-model="ruleEdit.reply_mode">
                        <el-radio label="all">回复全部</el-radio>
                        <el-radio label="random">随机回复一条</el-radio>
                    </el-radio-group>
                </div>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" size="mini" @click="submit('ruleEditForm')">确定</el-button>
                <el-button size="mini" @click="ruleEditVisible = false">取消</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
    <!-- 编辑弹框 E -->

    <!-- 添加回复文字 S -->
    <el-dialog title="添加回复文字" :visible.sync="addTextVisible" :close-on-click-modal="false" width="900px"
               class="addTextDialog">
        <el-form ref="addTextForm" :model="textForm">
            <el-form-item label="" prop="textTmp" :rules="formRules.text_tmp">
                <el-input v-model="textForm.textTmp" type="textarea" :rows="10" resize="none" maxlength="5000"
                          show-word-limit
                          placeholder="请输入内容">
                </el-input>
            </el-form-item>
            <el-form-item style="margin-top: 20px;">
                <el-button type="primary" size="mini" @click="addTextOK('addTextForm')">确定</el-button>
                <el-button size="mini" @click="addTextClose">取消</el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
    <!-- 添加回复文字 E -->
</div>

<script>
    let instance = axios.create({
        baseURL: '/wechat-reply/api/',
        timeout: 5000
    });

    let app = new Vue({
        el: '#app',
        delimiters: ['${', '}'],
        data() {
            let validateReplies = function (rule, value, callback) {
                if (value.length === 0) {
                    callback(new Error('请编辑回复内容'));
                } else {
                    callback();
                }
            };
            return {
                searchName: '',
                ruleDetailVisible: false,
                ruleEditVisible: false,
                addTextVisible: false,
                editType: 'add',
                textForm: {
                    textTmp: '',
                },
                ruleDetail: {
                    rule_name: '',
                    reply_mode: 'random',
                    keywords: [
                        {
                            keyword: 'ceshi',
                            full_match: false,
                            weixin_rule_id: 1
                        }
                    ],
                    replies: [
                        {
                            type: 'text',
                            content: 'hahahh',
                            weixin_rule_id: 1
                        },
                    ]
                },
                ruleEdit: {},
                rulesData: [],
                formRules: {
                    rule_name: [
                        {required: true, message: '请输入规则名', trigger: 'change'},
                        {min: 1, max: 61, message: '规则名必须在1-60个字符内', trigger: 'change'}
                    ],
                    keyword: [
                        {required: true, message: '请输入关键词', trigger: 'change'},
                        {min: 1, max: 31, message: '关键词必须在1-30个字符内', trigger: 'change'}
                    ],
                    replies: [
                        {required: true, validator: validateReplies, trigger: 'blur'}
                    ],
                    text_tmp: [
                        {required: true, message: '请输入回复内容', trigger: ['blur', 'change']},
                        {min: 1, max: 301, message: '回复内容必须在1-300个字符内', trigger: ['blur', 'change']}
                    ]
                },
                pagination: {
                    total: 0,
                    page: 1,
                    perPage: 20,
                }
            }
        },
        filters: {
            keywords2Str(keywordArray) {
                return keywordArray.reduce(function (accumulator, currentValue, index) {
                    if (index === 0) {
                        return accumulator + currentValue.keyword;
                    } else {
                        return accumulator + ' , ' + currentValue.keyword;
                    }

                }, '')
            },
            replyMode2Str(mode) {
                if (mode === 'all') {
                    return '全部回复';
                } else {
                    return '随机回复一条';
                }
            }
        },
        created() {
            this.getRules();
        },
        methods: {

            //-------------------------- 关键词相关方法 ----------------------
            addKeyWord() {
                if (this.ruleEdit.keywords.length < 10) {
                    this.ruleEdit.keywords.push({
                        keyword: '',
                        full_match: false,
                        weixin_rule_id: 1
                    })
                } else {
                    this.$message({
                        message: '关键词最多只能创建10条',
                        type: 'warning'
                    })
                }
            },
            minusKeyWord(index) {
                this.ruleEdit.keywords.splice(index, 1);
            },

            //-------------------------- 回复内容相关方法 ---------------------
            addReplies(type) {
                if (this.ruleEdit.replies.length < 5) {
                    if (type === 'text') {
                        this.ruleEdit.replies.push({
                            type: 'text',
                            content: ''
                        })
                        this.textEdit = 'add';
                        this.textForm.textTmp = '';
                        this.addTextVisible = true;
                        this.$refs['addTextForm'] && this.$refs['addTextForm'].resetFields();
                    }
                } else {
                    this.$message({
                        message: '回复内容最多只能创建5条',
                        type: 'warning'
                    })
                }
            },
            minuReplies(index) {
                this.ruleEdit.replies.splice(index, 1);
            },
            editReplies(content, index) {
                this.textEdit = 'edit';
                this.textForm.textTmp = content;
                this.textIndex = index;
                this.addTextVisible = true;
            },
            addTextOK(formName) {
                var _this = this;
                _this.$refs[formName].validate(function (valid) {
                    if (valid) {
                        console.log(_this.textEdit);
                        if (_this.textEdit === 'edit') {
                            _this.ruleEdit.replies[_this.textIndex].content = _this.textForm.textTmp;
                            _this.addTextVisible = false;
                        } else {
                            _this.ruleEdit.replies[_this.ruleEdit.replies.length - 1].content = _this.textForm.textTmp;
                            _this.addTextVisible = false;
                        }

                    } else {
                        return false;
                    }
                });
            },
            addTextClose() {
                if (this.textEdit === 'edit') {
                    this.addTextVisible = false;
                } else {
                    this.ruleEdit.replies.splice(this.ruleEdit.replies.length - 1, 1);
                    this.addTextVisible = false;
                }
            },

            //-------------------------- 规则列表相关方法 ---------------------
            getRules() {
                let _this = this;
                instance({
                    method: 'get',
                    url: 'replies/rules',
                    params: {
                        name: this.searchName
                    }
                })
                    .then(function (res) {
                        var data = res.data.data;
                        if (res.data.status === 0) {
                            _this.rulesData = data.data;
                            _this.pagination.total = data.total;
                        }
                    })
            },
            submit(formName) {
                var _this = this;
                _this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if (_this.editType === 'add') {
                            instance({
                                method: 'post',
                                url: 'replies/rules',
                                data: {
                                    rule_name: _this.ruleEdit.rule_name,
                                    reply_mode: _this.ruleEdit.reply_mode,
                                    replies: _this.ruleEdit.replies,
                                    keywords: _this.ruleEdit.keywords
                                }
                            })
                                .then(function (res) {
                                    var data = res.data;
                                    if (data.status === 0) {
                                        _this.$message({
                                            message: '规则创建成功',
                                            type: 'success'
                                        });
                                        _this.getRules();
                                        _this.ruleEditVisible = false;
                                    }
                                })
                        } else {
                            instance({
                                method: 'put',
                                url: 'replies/rules',
                                data: {
                                    id: _this.ruleEdit.id,
                                    rule_name: _this.ruleEdit.rule_name,
                                    reply_mode: _this.ruleEdit.reply_mode,
                                    replies: _this.ruleEdit.replies,
                                    keywords: _this.ruleEdit.keywords
                                }
                            })
                                .then(function (res) {
                                    var data = res.data;
                                    if (data.status === 0) {
                                        _this.$message({
                                            message: '规则修改成功',
                                            type: 'success'
                                        });
                                        _this.getRules();
                                        _this.ruleEditVisible = false;
                                    }
                                })
                        }
                    } else {
                        return false;
                    }
                });

            },
            addRules() {
                this.editType = 'add';
                this.ruleEdit = {
                    rule_name: '',
                    reply_mode: 'all',
                    keywords: [
                        {
                            keyword: '',
                            full_match: true
                        }
                    ],
                    replies: []
                }
                this.ruleEditVisible = true;
                this.$refs['ruleEditForm'] && this.$refs['ruleEditForm'].resetFields();
            },
            editRule(row) {
                var _this = this;
                _this.editType = 'edit';
                instance({
                    method: 'get',
                    url: 'replies/rules/show',
                    params: {
                        id: row.id
                    }
                })
                    .then(function (res) {
                        var data = res.data;
                        _this.ruleEdit = data.data;
                        _this.ruleEditVisible = true;
                        _this.$refs['ruleEditForm'] && _this.$refs['ruleEditForm'].resetFields();
                    })

            },

            showDetail(row) {
                var _this = this;
                instance({
                    method: 'get',
                    url: 'replies/rules/show',
                    params: {
                        id: row.id
                    }
                })
                    .then(function (res) {
                        var data = res.data;
                        _this.ruleDetail = data.data;
                        _this.ruleDetailVisible = true;
                    })
            },

            delRule(row) {
                let _this = this;
                _this.$confirm('该操作将删除此条规则, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(function () {
                    instance({
                        method: 'delete',
                        url: 'replies/rules',
                        params: {
                            id: row.id
                        }
                    })
                        .then(function (res) {
                            let data = res.data;
                            if (data.status === 0) {
                                _this.$message({
                                    type: 'success',
                                    message: '删除成功!'
                                });
                                _this.getRules();
                            }
                        })
                }).catch(function () {
                    /*
                    _this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                     */
                });
            },
            //-------------------------- 自定义菜单编辑方法 ---------------------
            skipMenuEidt() {
                const _this = this
                instance({
                    method: 'get',
                    url: "{{ config('wechat_reply.access_token_url') }}"
                })
                    .then(function (res) {
                        if (res) {
                            window.open(`https://wei.jiept.com/Home/Menu/${res.data}`)
                        } else {
                            _this.$message.error('access_token为空无法自定义菜单')
                        }
                    })
            }
        }
    })
</script>
</body>
</html>
