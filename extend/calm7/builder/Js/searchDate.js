        //日期时间范围
        layui.$('.dateTime').each(function () {
            var datetime=layui.$(this).data('timeType'),
                range=layui.$(this).data('range');
            layui.laydate.render({
                elem: this,
                trigger: 'click',
                type: datetime ? datetime :'datetime',
                range: range ? range : true,
            });
        });

