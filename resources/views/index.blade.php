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
    <meta name="description" content="微信自动回复管理">
    <link rel="stylesheet" href="{{ asset(mix('/css/element.css', 'vendor/wechat-reply')) }}">
    <style>
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

    </div>
</div>
<script>
    let app = new Vue({
        el: '#app',
        data: {
        },
        methods: {
        }
    })
</script>
</body>
</html>