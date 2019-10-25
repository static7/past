            //地区联动
            layui.form.on('select(linkage_s)', function (data) {
                var region = layui.$(data.elem).data('next'),
                    level  = layui.$(data.elem).data('level');
                if (region && data.value) {
                    linkage(region, data.value,level);
                }
            });
            layui.$(function() {
                linkage('province_s', 0,1);
            });
            //联动
            function linkage(title, pid,level, value) {
                var selects = "<option value=''>请选择</option>";
                if (pid === null || pid === '' || pid === undefined) {
                    layui.$('.' + title + '').html(selects);
                    layui.form.render();
                    return false;
                }
                layui.$.get("{:Route::buildUrl('%s')}", {'parent_id': pid,'level':level}, function (data) {
                    if (data.code === 1) {
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


