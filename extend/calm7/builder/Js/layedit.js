        //编辑器
        //注意：layedit.set 一定要放在 build 前面，否则配置全局接口将无效。
        layui.layedit.set({
            uploadImage: {
                url: "{:Route::buildUrl('')}", // TODO 接口url
                type: 'post' //默认post
            }
        });
        var contents = layui.layedit.build('layedit', {height: 700});
        layui.form.verify({
            content_layedit: function(){
                layui.layedit.sync(contents);//同步编辑器内容
            }
        });

