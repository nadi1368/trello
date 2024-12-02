/*
// ویرایش تاریخ و زمان دو دیت تسک
 */
function updateDueDateNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-due-date').html(response.due_date_list);// بروز رسانی دو دیت تسک در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید

}
function updateDueDate($this)
{
    $('#msg-duedata-input').fadeOut();
    var $ajax_url=$($this).data("ajax-url");
    var $date=$('#date-duedate-input').val();
    var $time=$('#time-duedate-input').val();
    if($date && $time)
    {
        $.ajax({
            url: $ajax_url,
            type: 'post',
            dataType: 'json',
            data: {'date': $date, 'time': $time},
            success: function (response) {
                if(response.success)
                {
                    response.action="updateDueDate";
                    response.th=$this;
                    closePopOver($this);
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
        $('#msg-duedata-input').fadeIn();
    }
}

/*
// کامل شدن و خارج کردن از حالت کامل شده برای تسک
 */
function complateDueDateNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-due-date').html(response.due_date_list);// بروز رسانی دو دیت تسک در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
}
function complateDueDate($this)
{
    var $ajax_url=$($this).data("ajax-url");
        $.ajax({
            url: $ajax_url,
            type: 'post',
            dataType: 'json',
            success: function (response) {
                if(response.success)
                {
                    response.action="complateDueDate";
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
// حذف دو دیت
 */
function deleteDueDateNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-due-date').html(response.due_date_list);// بروز رسانی دو دیت تسک در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید

}
function deleteDueDate($this)
{
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="deleteDueDate";
                response.th=$this;
                closePopOver($this);
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

