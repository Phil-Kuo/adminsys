
// 联动选择
$(function () {
    getDataForAjax();
})
$("#building").change(function () {
    getDataForAjax();
});

function getDataForAjax() {
    arch_id = $('#building').val()?$('#building').val():1;
    $.ajax({
        type:"get",
        url:"{:url('admin/TeleData/getArchitecture')}",
        dataType:'json',
        data:{
            id:arch_id,
        },
        success: function(data) {
            // console.log(data);
            data = eval(data);
            $('#location').empty();
            $("#location").html("<option value=''>请选择--</option>");
            for(var i = 0;i < data.length;i++){
                $('#location').append("<option value=" + data[i]['id'] + ">"
                    + data[i]['arch_name'] + "</option>");
            }
        }
    });
}

// 根据不同条件呈现不同的输入框
(function($){
    $.fn.conditionize = function(options){
        var settings  = $.extend({
            hideJS: true
        }, options);

        $.fn.showOrHide = function(listenTo, listenFor, $section){
            if ($(listenTo + ":checked").val() == listenFor) {
                $section.slideDown();
            }
            else {
                $section.slideUp();
            }
        }
        return this.each( function() {
            var listenTo = "[name=" + $(this).data('cond-option') + "]";
            var listenFor = $(this).data('cond-value');
            var $section = $(this);
        
            //Set up event listener
            $(listenTo).on('change', function() {
              $.fn.showOrHide(listenTo, listenFor, $section);
            });
            //If setting was chosen, hide everything first...
            if (settings.hideJS) {
              $(this).hide();
            }
            //Show based on current value on page load
            $.fn.showOrHide(listenTo, listenFor, $section);
          });
    }

}(jQuery));
$('.conditional').conditionize();

//Initialize Select2 Elements
$('.pid').select2();
