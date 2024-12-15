/**
 نمایش فرم ساخت لیبل جدید
 */
var $html_select_lebel;// ذخیره محتویات فرم سلکت لیبل برای نمایش هنگام زدن دکمه بک
var $html_update_label;// ذخیره محتویات فرم ویرایش لیبل برای نمایش هنگام زدن دکمه بک در فرم حذف لیبل
function  showCreateLabelForm($this)
{
    $html_select_lebel=$('#pop-menu-label').html();
    var $form=$('#pop-menu-create-label').html();
    $('#pop-menu-label').html($form);

    setTimeout(function() { $('#pop-menu-label'+' input').focus() }, 30);

    $('#create-label-form .mod-clickable').click(function(){
        $('#create-label-form .mod-clickable').children('i').removeClass('fa-check');
        $(this).children('i').addClass('fa-check');
        $('#color-label-input').val($(this).data('color'));
        return false;

    });
    return false;
}

/**
 // ساخت لیبل جدید
 */
function createLabelNode(response) {

    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-label').html(response.label_list);// بروز رسانی لیست لیبل های تسک در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید

}

function createLabel($this)
{
    $('#msg-label-input').fadeOut();
    var $ajax_url=$($this).data("ajax-url");
    var $title=$('#title-label-input').val();
    var $color=$('#color-label-input').val();
    if($title && $color)
    {
        $.ajax({
            url: $ajax_url,
            type: 'post',
            dataType: 'json',
            data: {'title': $title,'color': $color},
            success: function (response) {
                if(response.success)
                {
                    response.action="createLabel";
                    $('#title-label-input').val('');
                    $('#pop-menu-label').html($html_select_lebel); // بازگشت به پاپ اور انتخاب لیبل
                    $('#result-label').html(response.data);// بروز رسانی لیست لیبل ها در فرم جستجو
                    setTimeout(function() { $('#pop-menu-label'+' input').focus() }, 30);
                    $('#search-label-input').keyup(function(e) {
                        clearTimeout($.data(this, 'timer'));
                        if (e.keyCode == 13)
                            searchLabel(true);
                        else
                            $(this).data('timer', setTimeout(searchLabel, 500));
                    });
                    socket.emit("send",response,function (callback) {});


                }else
                {
                    alert('خطایی رخ داده است.');
                }
            },

            error: function () {
                alert('خطایی رخ داده است.');
            }

        });//ajax
    }else
    {
        $('#msg-label-input').fadeIn();
    }
    return false;
}

/*
نمایش پاپ آور ویرایش
 */
function showLabelUpdateForm($this)
{
    $html_select_lebel=$('#pop-menu-label').html();
    var $form=$('#pop-menu-update-label').html();
    $('#pop-menu-label').html($form);
    $('#id-label-input-update').val($($this).data('id'));
    $('#title-label-input-update').val($($this).data('title'));
    $('#color-label-input-update').val($($this).data('color'));
    $('#update-label-form .mod-clickable').children('i').removeClass('fa-check');
    $('#update-label-form .mod-clickable[data-color="'+$($this).data('color')+'"]').children('i').addClass('fa-check');

    setTimeout(function() { $('#pop-menu-label'+' input').focus() }, 30);
    $('#update-label-form .mod-clickable').click(function(){
        $('#update-label-form .mod-clickable').children('i').removeClass('fa-check');
        $(this).children('i').addClass('fa-check');
        $('#color-label-input-update').val($(this).data('color'));
        return false;

    });
    return false;
}
/**
 // بروز رسانی لیبل
 //    فرم پاپ آور
 */
function updateLabelNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-label').html(response.label_list);// بروز رسانی لیست لیبل های تسک در مودال
    $('.mod-card-front[data-label-id="'+response.label_id+'"]').before(response.label_view).remove();// بروز رسانی این لیبل در تمامی تسک ها
}
function updateLabel($this)
{
    $('#msg-label-input-update').fadeOut();
    var $ajax_url=$($this).data("ajax-url");
    var $id=$('#id-label-input-update').val();
    var $title=$('#title-label-input-update').val();
    var $color=$('#color-label-input-update').val();
    if($title && $color)
    {
        $.ajax({
            url: $ajax_url,
            type: 'post',
            dataType: 'json',
            data: {'label_id': $id,'title': $title,'color': $color},
            success: function (response) {
                if(response.success)
                {
                    response.action="updateLabel";
                    response.label_id=$id;
                    $('#title-label-input').val('');
                    $('#pop-menu-label').html($html_select_lebel); // بازگشت به پاپ اور انتخاب لیبل
                    $('#result-label').html(response.data);// بروز رسانی لیست لیبل ها در فرم جستجو
                    setTimeout(function() { $('#pop-menu-label'+' input').focus() }, 30);
                    $('#search-label-input').keyup(function(e) {
                        clearTimeout($.data(this, 'timer'));
                        if (e.keyCode == 13)
                            searchLabel(true);
                        else
                            $(this).data('timer', setTimeout(searchLabel, 500));
                    });
                    socket.emit("send",response,function (callback) {});
                }else
                {
                    alert('خطایی رخ داده است.');
                }
            },

            error: function () {
                alert('خطایی رخ داده است.');
            }

        });//ajax
    }else
    {
        $('#msg-label-input-update').fadeIn();
    }
    return false;
}

/**
 * فرم نمایش تائید حذف لیبل
 */
function  showDeleteLabelForm($this)
{
    $html_update_label=$('#pop-menu-label').html();
    var $label_title=$('#title-label-input-update').val();
    $('#title-label-input-delete').val($label_title);

    var $label_id=$('#id-label-input-update').val();
    $('#id-label-input-delete').val($label_id);


    var $form=$('#pop-menu-delete-label').html();
    $('#pop-menu-label').html($form);

    setTimeout(function() { $('#pop-menu-label'+' input').focus() }, 30);
    return false;
}

/*
حذف کلیی لیبل
 */
function deleteLabelNode(response)
{
    $('#title-label-input').val('');
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-label').html(response.label_list);// بروز رسانی لیست لیبل های تسک در مودال
    $('.mod-card-front[data-label-id="'+response.label_id+'"]').remove();// حذف این لیبل در تمامی تسک ها
}
function deleteLabel($this)
{
    var $ajax_url=$($this).data("ajax-url");
    var $id=$('#id-label-input-delete').val();

    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        data: {'label_id': $id},
        success: function (response) {
            if(response.success)
            {
                response.action="deleteLabel";
                response.label_id=$id;
                $('#pop-menu-label').html($html_select_lebel); // بازگشت به پاپ اور انتخاب لیبل
                $('#result-label').html(response.data);// بروز رسانی لیست لیبل ها در فرم جستجو
                setTimeout(function() { $('#pop-menu-label'+' input').focus() }, 30);
                $('#search-label-input').keyup(function(e) {
                    clearTimeout($.data(this, 'timer'));
                    if (e.keyCode == 13)
                        searchLabel(true);
                    else
                        $(this).data('timer', setTimeout(searchLabel, 500));
                });
                socket.emit("send",response,function (callback) {});
            }else
            {
                alert('خطایی رخ داده است.');
            }
        },

        error: function () {
            alert('خطایی رخ داده است.');
        }

    });//ajax
    return false;
}
/*
انتخاب لیبل برای تسک
* یا خارج شدن از حالت انتخاب یک لیبل
 */

function labelTaskNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-label').html(response.label_list);// بروز رسانی لیست لیبل های تسک در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
}
function labelTask($this)
{
    var $ajax_url=$($this).data("ajax-url");

    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="labelTask";
                $('#result-label').html(response.data);// بروز رسانی لیست لیبل ها در فرم جستجو
                $("#search-label-input").val(''); // خالی کردن اینپوت جستجو

                socket.emit("send",response,function (callback) {});

            }else
            {
                alert('خطایی رخ داده است.');
            }
        },

        error: function (e) {
            alert('خطایی رخ داده است.');
        }

    });//ajax
    return false;
}


/*
// جستجوی لیبل
 */
function searchLabel(force) {
    var searchString = $("#search-label-input").val();
    var $ajax_url=$("#search-label-input").data("ajax-url");
    if (!force && searchString.length < 3 && searchString.length > 0) return; //wasn't enter, not > 2 char
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        data: {'search_string': searchString},
        success: function (response) {
            if(response.success)
            {
                $('#result-label').html(response.data);
            }else
            {
                alert('خطایی رخ داده است.');
            }
        },

        error: function (e) {
            alert('خطایی رخ داده است.');
        }

    });//ajax
}

/*
* دکمه برگشت در فرم ساخت لیبل جدید
 */
function backCreateLabel($this)
{
    $('#pop-menu-label').html($html_select_lebel);
    $("#search-label-input").val('');
    searchLabel(true);// بروز رسانی مجدد لیست لیبل ها

    setTimeout(function() { $('#pop-menu-label'+' input').focus() }, 30);
    $('#search-label-input').keyup(function(e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 13)
            searchLabel(true);
        else
            $(this).data('timer', setTimeout(searchLabel, 500));
    });
    return false;
}

/*
* دکمه برگشت در فرم ویرایش لیبل
 */
function backUpdateLabel($this)
{

    var $label_title=$('#title-label-input-delete').val();
    $('#pop-menu-label').html($html_update_label);
    $('#title-label-input-update').val($label_title);

    setTimeout(function() { $('#pop-menu-label'+' input').focus() }, 30);
    $('#update-label-form .mod-clickable').click(function(){
        $('#update-label-form .mod-clickable').children('i').removeClass('fa-check');
        $(this).children('i').addClass('fa-check');
        $('#color-label-input-update').val($(this).data('color'));
        return false;

    });
    return false;
}