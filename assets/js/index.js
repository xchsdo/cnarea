$(function () {
    initRegion($("#provinces"), $("#citys"), $("#area"), $("#street"), $("#villages"));
});
var initRegion = function (provinceDom, cityDom, AreaDom, StreetDom, VillagesDom) {
    var initFun = {
        init: function (dom) {
            if (dom == "provinces") {
                cityDom.find('option[data-add]').remove();
                AreaDom.find('option[data-add]').remove();
                StreetDom.find('option[data-add]').remove();
                VillagesDom.find('option[data-add]').remove();
            } else if (dom == "citys") {
                AreaDom.find('option[data-add]').remove();
                StreetDom.find('option[data-add]').remove();
                VillagesDom.find('option[data-add]').remove();
            } else if (dom == "area") {
                StreetDom.find('option[data-add]').remove();
                VillagesDom.find('option[data-add]').remove();
            } else if (dom == "street") {
                VillagesDom.find('option[data-add]').remove();
            }
        },
        isValnull: function (Dom, appendDom, type) {
            if ($("#alt")) {
                $("#alt").remove();
            }
            $("#index").addClass('d-none');
            if (Dom.val() == "") {
                appendDom.find("option").prop("selected", false);
                return;
            } else if (provinceDom.val() == "7100000000000") { // 台湾省 4级
                StreetDom.removeClass('d-none');
                VillagesDom.addClass('d-none');
            } else if (provinceDom.val() == "8100000000000" || provinceDom.val() == "8200000000000") { // 香港特别行政区, 澳门特别行政区 3级
                StreetDom.addClass('d-none');
                VillagesDom.addClass('d-none');
            } else {
                StreetDom.removeClass('d-none');
                VillagesDom.removeClass('d-none');
            }
            initFun.changeAjax(Dom.val(), appendDom, type);
        },
        changeAjax: function (DomVal, appendDom, type) {
            $.ajax({
                type: "post",
                url: "./api/api.php",
                data: { "id": DomVal, "type": type }, // type= 0省份, 1城市, 2区县, 3街道, 4村庄
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, item) {
                        if (!!item.id || !!item.name) {
                            appendDom.append("<option data-add value='" + item.id + "'>" + item.name + "</option>");
                        } else {
                            $("#alt").remove();
                            $("#index").removeClass('d-none');
                            $("#index").append("<span id='alt'>提示: 查询类型错误</span>");
                            return false;
                        }
                    });
                },
                complete: function (XHR, TS) {
                    XHR = null;
                }
            });
        },
        showAdd: function (Dom) {
            var value = provinceDom.find("option[data-add]:selected").text() + cityDom.find("option[data-add]:selected").text() + AreaDom.find("option[data-add]:selected").text() + StreetDom.find("option[data-add]:selected").text();
            if ($("#alt")) {
                $("#alt").remove();
                $("#index").removeClass('d-none');
            }
            if (!Dom.val()) {
                $("#index").append("<span id='alt'>选择的地址是: " + value + "</span>");
                return;
            } else {
                $("#index").append("<span id='alt'>选择的地址是: " + value + VillagesDom.find("option[data-add]:selected").text() + "</span>");
            }
        }
    }
    initFun.changeAjax("0", provinceDom, "0");  // 初始化省份
    provinceDom.on('change', function () {
        initFun.init('provinces');
        initFun.isValnull(provinceDom, cityDom, "1");
    })
    cityDom.on('change', function () {
        initFun.init('citys');
        initFun.isValnull(cityDom, AreaDom, "2");
    })
    AreaDom.on('change', function () {
        initFun.init('area');
        if (provinceDom.val() == "8100000000000" || provinceDom.val() == "8200000000000") { // 香港, 澳门 显示3级完整地址
            initFun.showAdd(AreaDom);
            $("#index").removeClass('d-none');
            return;
        }
        initFun.isValnull(AreaDom, StreetDom, "3");
    })
    StreetDom.on('change', function () {
        initFun.init('street');
        if (provinceDom.val() == "7100000000000") { // 台湾 显示4级完整地址
            initFun.showAdd(StreetDom);
            $("#index").removeClass('d-none');
            return;
        }
        initFun.isValnull(StreetDom, VillagesDom, "4");
    })
    // 显示完整地址
    VillagesDom.on('change', function () {
        initFun.showAdd(VillagesDom);
    })
}