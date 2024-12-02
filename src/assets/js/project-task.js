/*
تسک های هر لیست
 */
$(document).ready(function() {
    $.fn.editable.defaults.mode = 'inline';
//اضافه کردن تسک جدید

    AddTask();// اضافه کردن تسک جدید
    closePopOverOutSideClick();// بستن پاپ آور هنگام کلیک خارج از محدوده
});

//اضافه کردن تسک جدید
function AddTaskNode(response)
{
    $target=$(response.ajax_div);
    $($target).append(response.data);
}
function AddTask()
{
    $('.add-new-list').editable({
        name:  'title',
        emptytext: 'درج کار جدید ...',
        emptyclass : '',
        ajaxOptions : {
            type : 'post',
            dataType: 'json',
        },
        success: function(response, AddTask) {
            if(response.success){
                response.action="AddTask";
                socket.emit("send",response,function (callback) {});

                $target=$(response.ajax_div);
                $($target).animate({scrollTop: $($target).height()}, 100);
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
    $('.add-new-list').on('shown', function(e, editable) {
        editable.input.$input.val('');
    });
}

function moveTaskNode(response)
{
    var new_pos_in_other_client;
    var index=$('#'+response.th).index();
    if(index!=response.new_position)
    {
        // تغیر موقعیت در سایر کلاینت هااا
        if(response.new_position==0)
        {
            new_pos_in_other_client=0;
            $('#'+response.th).insertBefore($('#'+response.th).parent().find("li").eq(new_pos_in_other_client));
        }
        else if(index>response.new_position)
        {
            new_pos_in_other_client=response.new_position-1;
            $('#'+response.th).insertAfter($('#'+response.th).parent().find("li").eq(new_pos_in_other_client));

        }else
        {
            new_pos_in_other_client=response.new_position;
            $('#'+response.th).insertAfter($('#'+response.th).parent().find("li").eq(new_pos_in_other_client));
        }
    }


}

function receiveTaskNode(response)
{

    var item=$('#'+response.th).clone(true);
    $('#'+response.th).remove();
    if($('#tasks-ul-'+response.new_status).find("li").length)
    {
        if(response.new_position==0)
        {
            $('#tasks-ul-'+response.new_status).find("li").eq(0).before(item);
        }else
        {
            $('#tasks-ul-'+response.new_status).find("li").eq(response.new_position-1).after(item);
        }
    }else
    {
        $('#tasks-ul-'+response.new_status).html(item);
    }

}
// حرکت تسک ها
$( function() {
    initMoveTask();
} );
function initMoveTask(){

    var $stop=true;
    var $start_board="";
    var $stop_board="";
    $( ".list ul" ).sortable({
        connectWith: ".connectedSortable",
        update: function(event, ui) {
            var $this=ui.item.attr("id");
            var $new_position=ui.item.index();
            var $ajax_url=$('#'+$this).data("ajax-url-move");
            if($stop || $start_board==$stop_board)
            {

                $.ajax({
                    url: $ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: {'new_position': $new_position},
                    success: function (response) {
                        if(response.success)
                        {
                            response.new_position=$new_position;
                            response.th=$this;
                            response.action="moveTask";
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
            }

        },
        start: function(event, ui) {
            $start_board=$(this).attr("id");
        },
        over: function(event, ui) {
            $stop_board=$(this).attr("id");
        },
        stop: function(event, ui) {
            $stop=true;
        },
        receive: function(event, ui) {
            var $this=ui.item.attr("id");
            var $new_position=ui.item.index();
            var $receive_list=$(this).attr("id");
            var $new_status=$(this).data("status");
            var $ajax_url=$('#'+$this).data("ajax-receive-url");
            $.ajax({
                url: $ajax_url,
                type: 'post',
                dataType: 'json',
                data: {'new_position': $new_position, 'new_status': $new_status},
                success: function (response) {
                    if(response.success)
                    {
                        response.new_position=$new_position;
                        response.th=$this;
                        response.new_status=$new_status;
                        response.action="receiveTask";
                        socket.emit("send",response,function (callback) {});

                    }else
                    {
                        alert('خطایی رخ داده است.');
                    }
                },

                error: function (e) {
                    alert('خطایی رخ داده است.');
                }

            });//ajax*/
        },
        out: function( event, ui ) {
            $stop=false;
        },
    }).disableSelection();
}
/**
// مشاهده جزئیات تسک در مودال
 */
$( function() {

    $( ".task" ).on( "click", function() {
        viewTask(this);

    });

} );

/**
 *  مشاهده جزئیات هر تسک در مدال
 * */

function viewTask($this)
{
    var $ajax_url=$($this).data("ajax-url-view");
    var $task_id=$($this).attr("id");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                $("#task-view").html(response.data);
                $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
                updateTask(); // ادیت ایبل عنوان و توضیحات تسک
                $('#dialog').modal({
                    backdrop: true,
                    keyboard: true  // to prevent closing with Esc button (if you want this too)
                });
                $('#dialog').modal('show');
                $('#task-view').attr('data-modal-task-id','view_#'+$task_id);
                AddCheckListItem();// چک لیست
                attach();// آپلود فایل
            }else
            {
                alert('خطایی رخ دادهfsdfsdf است.');
            }
        },

        error: function (e) {
            alert('خطایی رخ داده است.');
        }

    });//ajax*/
}

/**
 * تابعی که باید بعد از باز شدن مدال فراخوانی شود
 */
function updateTitleTaskNode(response)
{
    $(response.ajax_div+' .list-card-title').html(response.newValue);
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#task-title').editable('setValue', response.newValue);
}
function updateDescTaskNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#task-desc').editable('setValue', response.newValue);
}
function updateTask()
{
    /**
     * ویرایش عنوان تسک در مدال
     */
    $("#task-title").editable({
        name:  'title',
        showbuttons: false,
        ajaxOptions : {
            type : 'post',
            dataType: 'json',
        },
        success: function(response, newValue) {
            if(response.success){
                response.action="updateTitleTask";
                response.newValue=newValue;
                socket.emit("send",response,function (callback) {});
            }else
            {
                alert('خطایی رخ داده است.');
            }
        },
        error: function(response, newValue) {
            if(response.status === 500) {
                return 'Service unavailable. Please try later.';
            } else {
                return 'Errorr save Info';
            }
        },
    });

    /**
     * ویرایش توضیحات تسک در مدال
     */
    $("#task-desc").editable({
        name:  'desc',
        emptytext: 'ویرایش توضیحات...',
        emptyclass : '',
        ajaxOptions : {
            type : 'post',
            dataType: 'json',
        },
        success: function(response, newValue) {
            if(response.success){
                response.action="updateDescTask";
                response.newValue=newValue;
                socket.emit("send",response,function (callback) {});
            }else
            {
                alert('خطایی رخ داده است.');
            }
        },
        error: function(response, newValue) {
            if(response.status === 500) {
                return 'Service unavailable. Please try later.';
            } else {
                return 'Errorr save Info';
            }
        },
    });

/**
* فراخوانی تابع جستجوی ممبر هنگام تایپ در فرم جستجو
 */
    $('#search-member-input').keyup(function(e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 13)
            searchMember(true);
        else
            $(this).data('timer', setTimeout(searchMember, 500));
    });

    /**
   * فراخوانی تابه جستجو لیبل هنگام تایپ در فرم جستجو
     * تابع جستجو در فایل
     * label.js
    */
    $('#search-label-input').keyup(function(e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 13)
            searchLabel(true);
        else
            $(this).data('timer', setTimeout(searchLabel, 500));
    });

}

/*
// جستجوی یوزر
 */
function searchMember(force) {
    var searchString = $("#search-member-input").val();
    var $ajax_url=$("#search-member-input").data("ajax-url");
    if (!force && searchString.length < 3 && searchString.length > 0) return; //wasn't enter, not > 2 char
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        data: {'search_string': searchString},
        success: function (response) {
            if(response.success)
            {
                $('#result-member').html(response.data);
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
** اضافه و حذف ممبر برای تسک
 */
function memberTaskNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-member').html(response.member_list);// لیست ممبر ها در مودال
    $(response.ajax_div).before(response.task_view).hide();// جای گزین کردن تسک با مقادیر جدید
}
function memberTask($this)
{
    var $ajax_url=$($this).data("ajax-url");
    var $role=$($this).data("role");
    var $task_id=$($this).data("task-id");
    var $user_id=$($this).data("id");

    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                if($role=="select")
                {
                    $($this).data("role",'un-select');
                }else
                {
                    $($this).data("role",'select');
                }

                response.action="memberTask";
                response.ajax_div=$task_id;
                $($this).toggleClass('active');
                $($this).children('i').toggleClass('fa-check');
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
 * آرچیو کردن تسک
 */
var $index_task=0;
function archiveTaskNode(response)
{
    var modal_id='view_'+response.ajax_div;

    if(response.role=="archive")
    {
        $('div[data-modal-task-id="'+modal_id+'"] '+'#btn-archive-task').data("role",'restore');
        $(response.ajax_div).remove();// حذف اون تسک از برد
    }else
    {
        // برگرداندن آن تسک
        $('div[data-modal-task-id="'+modal_id+'"] '+'#btn-archive-task').data("role",'archive');
        if($(response.task_list_id+' li:nth-child('+response.index_task+')').length)
        {
            $(response.task_list_id+' li:nth-child('+response.index_task+')').before(response.task_view);
        }else
        {
            $(response.task_list_id).append(response.task_view)
        }
    }

    $('div[data-modal-task-id="'+modal_id+'"] '+'#btn-archive-task').toggleClass('button-danger button-default');
    $('div[data-modal-task-id="'+modal_id+'"] '+'#btn-archive-task').html(response.data);
}

function archiveTask($this)
{
    var $ajax_url=$($this).data("ajax-url");
    var $role=$($this).data("role");

    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="archiveTask";
                response.role=$role;
                if($role=="archive")
                {
                    // ذخیره ایندکس تسک در جهت برگرداندن آن تسک
                    $task_id=$($this).data("task-id");
                    $index_task=$($task_id).index()+1;
                }else
                {
                    response.index_task=$index_task;
                }
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

}

/*
// مشاهده و عدم مشاهده تسک
 */
function watchesTaskNode(response)
{
    var modal_id='view_'+response.ajax_div;

    if(response.role=="watch")
    {
        $('div[data-modal-task-id="'+modal_id+'"] '+'#btn-watch-task').data("role",'restore');
    }else
    {
        $('div[data-modal-task-id="'+modal_id+'"] '+'#btn-watch-task').data("role",'watch');
    }

    $('div[data-modal-task-id="'+modal_id+'"] '+'#btn-watch-task').toggleClass('button-success button-default');
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
    $('div[data-modal-task-id="'+modal_id+'"] '+'#btn-watch-task').html(response.data);
}
function watchesTask($this)
{
    var $ajax_url=$($this).data("ajax-url");
    var $role=$($this).data("role");
    var $task_id=$($this).data("task-id");

    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.role=$role;
                response.ajax_div=$task_id;
                response.action="watchesTask";

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

}

/*
 نمایش لیست ممبر ها
 جهت انتخاب برای هر تسک
 */
function showPopOver($this)
{
    var offset = $($this).position();
    var pop_over=$($this).data('pop-id');
    $.when($('.pop-over').fadeOut(500))
        .done(function() {
            $(pop_over)
                .fadeIn()
                .css({
                    left: offset.left+ $($this).innerWidth(),
                    top: offset.top + $($this).innerHeight()
                });

            setTimeout(function() { $(pop_over+' input').focus() }, 30);
        });
    closePopOverOutSideClick();
    return false;
}

/*
بسته شدن مدال هنگام کلیک خارج از محدوده
 */

$('body').on('click', function (e) {
    if($(e.target).parents('[id="main-body"]').length!== 0)
    {
        if ($(e.target).id !== 'dialog'
            && $(e.target).parents('[id="dialog"]').length === 0) {
            $('#dialog').modal('hide');
        }
    }
});

/*
بسته شدن منوی پاپ پاپ هنگامی که خارج از محدوده کلیک شود
 */
function closePopOverOutSideClick() {
    $('body').on('click', function (e) {

        if($(e.target).parents('[id="main-body"]').length!== 0)
        {
            if (!$(e.target).parents('[class="pop-over"]').length ) {
                if(!$(e.target).data('pop-id'))
                {
                    $('.pop-over').hide();
                }

            }
        }
    });
}

/*
           بسته شدن پاپ آپ با دکمه کلوز
            */
function closePopOver($this) {
    $($this).closest('.pop-over').fadeOut();
    return false;
}


