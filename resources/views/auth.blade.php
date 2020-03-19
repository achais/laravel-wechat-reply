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
    <title>登录 - 微信自动回复管理</title>
    <meta name="description" content="微信自动回复管理">
    <link rel="stylesheet" href="{{ asset(mix('/css/element.css', 'vendor/wechat-reply')) }}">
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;

        }
        #app {
            width: 100%;
            height: 100%;

        }
        .login__box {
            position:absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
        .login__box_header {
            margin: 0;
            text-align:center;

        }
        .login__box_submit {
            width: 100%;
        }
    </style>
    <script type="text/javascript" src="{{asset(mix('/js/vue.js', 'vendor/wechat-reply'))}}"></script>
    <script type="text/javascript" src="{{asset(mix('/js/element.js', 'vendor/wechat-reply'))}}"></script>
</head>
<body>
<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a>
    to improve your experience.</p>
<![endif]-->

<div id="app" v-cloak>
    <div class="alert">
        <template>
            @foreach(['success', 'info', 'warning', 'error'] as $message)
                @if(session()->has($message))
                    <el-alert
                        title="{{session()->get($message)}}"
                        type="{{$message}}"
                        center>
                    </el-alert>
                @endif
            @endforeach
        </template>
    </div>
    <div class="body">
        <el-card class="login__box">
            <h3 slot="header" class="login__box_header">管理员登录</h3>
            <el-form ref="loginForm" :model="loginForm" :rules="loginRules" status-icon class="login__box_form">
                <el-form-item prop="user">
                    <el-input v-model="loginForm.user" placeholder="请输入用户名" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item prop="password">
                    <el-input type="password" v-model="loginForm.password" placeholder="请输入密码" @keyup.enter.native="submitForm('loginForm')" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="success" @click="submitForm('loginForm')" class="login__box_submit">立即登录</el-button>
                </el-form-item>
            </el-form>

            <form action="" method="POST" id="hideForm" style="display: none">
                {{ csrf_field() }}
                <label>
                    <input type="text" name="user" v-model="loginForm.user">
                </label>
                <label>
                    <input type="text" name="password" v-model="loginForm.password">
                </label>
            </form>
        </el-card>
    </div>
</div>
<script>
    let app = new Vue({
        el: '#app',
        data: {
            loginForm: {
                user: '',
                password: ''
            },
            loginRules: {
                user: [
                    {required: true, message: '请输入用户名', trigger: 'change'}
                ],
                password: [
                    {required: true, message: '请输入密码', trigger: 'change'}
                ],
            }
        },
        methods: {
            submitForm(formName) {
                this.$refs[formName].validate(function (valid) {
                    if (valid) {
                        window.document.getElementById('hideForm').submit();
                    } else {
                        console.log('规则验证失败');
                    }
                })
            }
        }
    })
</script>
</body>
</html>