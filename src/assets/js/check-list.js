/*
// ایجاد یا ویرایش عنوان چک لیست
 */
function updateCheckListNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-cheklist').html(response.index);// بروز رسانی چک لیست در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
    AddCheckListItem();
}
function updateCheckList($this)
{
    $('#msg-check-list-input').fadeOut();
    var $ajax_url=$($this).data("ajax-url");
    var $title=$('#title-check-list-input').val();
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
                    response.action="updateCheckList";
                    response.th=$this;
                    closePopOver(response.th);
                    $('#create-check-list-btn').fadeOut();
                    $('#update-check-list-btn').fadeIn();

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
        $('#msg-check-list-input').fadeIn();
    }
}



/*
// حذف چک لیست
 */
function deleteCheckListNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-cheklist').html(response.index);// بروز رسانی چک لیست در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید

}
function deleteCheckList($this)
{
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="deleteCheckList";
                response.th=$this;
                closePopOver(response.th);
                $('#create-check-list-btn').fadeIn();
                $('#update-check-list-btn').fadeOut();
                $('#title-check-list-input').val('');
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
}

/*
 // اضافه کردن ایتم به چک لیست
 */
function AddCheckListItemNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#item-checklist').html(response.list_item);// بروز رسانی چک لیست در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
    UpdateCheckListItem();
}
function AddCheckListItem()
{
    $('#add-new-check-list-item').editable({
        name:  'title',
        emptytext: 'درج ایتم جدید...',
        emptyclass : '',
        ajaxOptions : {
            type : 'post',
            dataType: 'json',
        },
        success: function(response, newValue) {
            if(response.success){
                response.action="AddCheckListItem";
                socket.emit("send",response,function (callback) {});
            }else
            {
                alert(response.success);
            }
        },
        error: function(response, newValue) {
            if(response.status === 500) {
                return 'Service unavailable. Please try later.';
            } else {
                return 'Errorr save Info';
            }
        },
        display: function(value, response){
            return false;
        },
    });
    $('#add-new-check-list-item').on('shown', function(e, editable) {
        editable.input.$input.val('');
    });
    UpdateCheckListItem();
}
function UpdateCheckListItemNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#item-checklist').html(response.list_item);// بروز رسانی چک لیست در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
    UpdateCheckListItem();
}
function UpdateCheckListItem()
{

    $('.check-list-item-update').editable({
        name:  'title',
        emptytext: 'Add an item...',
        emptyclass : '',
        ajaxOptions : {
            type : 'post',
            dataType: 'json',
        },
        success: function(response, newValue) {
            if(response.success){
                response.action="UpdateCheckListItem";
                socket.emit("send",response,function (callback) {});
            }else
            {
                alert(response.success);
            }
        },
        error: function(response, newValue) {
            if(response.status === 500) {
                return 'Service unavailable. Please try later.';
            } else {
                return 'Errorr save Info';
            }
        },
        display: function(value, response){
            return false;
        },
    });
    $('.check-list-item-update').on('shown', function(e, editable) {
        editable.input.$input.val('');
    });
}

/*
// انجام شده و خارج کردن از حالت انجام شده ایتم های چک لیست
 */
function DoneCheckListItemNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#item-checklist').html(response.list_item);// بروز رسانی چک لیست در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
    UpdateCheckListItem();
}
function DoneCheckListItem($this)
{
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="DoneCheckListItem";
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
}
function DeleteCheckListItemNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#item-checklist').html(response.list_item);// بروز رسانی چک لیست در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
    UpdateCheckListItem();
}
function DeleteCheckListItem($this)
{
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="DeleteCheckListItem";
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
}

