        //普通图片上传
        var picture_upload_mark = layui.upload.render({
            elem: '#picture_upload', // TODO 指向容器选择器
            url: "{:Route::buildUrl('')}", //TODO 上传地址
            accept: 'images',
            bindAction: '#picture_before', //TODO 指向一个按钮触发上传
            auto: false,
            multiple:false,
            field: "%s", // TODO 设定文件域的字段名
            choose: function (obj) {
                obj.preview(function (index, file, result) { /*预读本地文件，不支持ie8*/
                    layui.$('#picture_preview').attr('src', result).removeClass('layui-hide'); // TODO 图片链接（base64）
                    layui.$('#picture_delete').removeClass('layui-hide');
                });
            },
            done: function (res,index,upload) {
                // TODO 上传完毕回调
            },
            error: function (index,upload) {
                // TODO 请求异常回调
            }
        });
        //查看图片
        layui.$('#picture_preview').on('click',function(){
            layui.layer.photos({
                photos: '.picture_preview'
            });
        });
        //删除图片
        layui.$('#picture_delete').on('click',function(){
            layui.$('#picture_preview').attr('src','').addClass('layui-hide');
            layui.$(this).addClass('layui-hide');
        });



