            //分类联动
            layui.form.on('select(category_s)', function(data) {
                var category = layui.$(data.elem).data('category');
                if (category) {
                    categorys(category, data.value);
                }
            });
            layui.$(function() {
                categorys('category_1', 0);
            });
            function categorys(title, pid,value) {
                var selects = "<option>请选择</option>";
                if (pid === null || pid === '' || pid === undefined) {
                    layui.$('.' + title + '').html(selects);
                    layui.form.render();
                    return false;
                }
                layui.$.get("{:Route::buildUrl('%s')}", { 'parent_id': pid }, function(data) {
                    if (data.code===1){
                        layui.$.each(data.data, function (key, info) {
                            if (value == info.id) {
                                selects += "<option selected  value='" + info.id + "'>" + info.name + "</option>"
                            } else {
                                selects += "<option value='" + info.id + "'>" + info.name + "</option>"
                            }
                        });
                    }
                    layui.$('.' + title + '').html(selects);
                    layui.form.render();
                });
            }

