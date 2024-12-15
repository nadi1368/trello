function createCommentsNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-comment').html(response.comment_list);// بروز رسانی لیست کامنت های تسک در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
}
function createComments($this)
{
    $('#msg-comments-input').fadeOut();
    var $ajax_url=$($this).data("ajax-url");
    var $title=$('#title-comment-input').val();
    if($title)
    {
        $.ajax({
            url: $ajax_url,
            type: 'post',
            dataType: 'json',
            data: {'title': $title},
            success: function (response) {
                if(response.success)
                {
                    response.action="createComments";
                    $('#title-comment-input').val('');
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
        $('#msg-comments-input').fadeIn();
    }
}
