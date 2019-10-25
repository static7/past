        //上传文件
        var upload_file=layui.upload.render({
            elem: '#file_upload_demo', // TODO 指向容器选择器
            url: "{:Route::buildUrl('')}", //TODO 上传地址
            accept: 'file',
            auto: false,
            bindAction: '#file_before', //TODO 指向一个按钮触发上传
            field: "%s", // TODO 设定文件域的字段名
            choose: function(obj){
               var file_list=obj.pushFile();
                /*读取本地文件*/
                obj.preview(function(index, file){
                    layui.$('#file_info').html('文件名称: '+file.name+' 文件大小: '+(file.size/1024/1024).toFixed(2)+'MB');
                    layui.$('#file_delete').removeClass('layui-hide');
                });
            },
            before: function(object){
                 // TODO 文件提交上传前的回调
            },
            done: function (res,index,upload) {
                // TODO 上传完毕回调
            },
            error: function (index,upload) {
                // TODO 请求异常回调
            }
        });
        //删除文件
        layui.$('#file_delete').on('click',function(){
            layui.$('#file_info').html('无文件信息');
            layui.$(this).addClass('layui-hide');
        });


