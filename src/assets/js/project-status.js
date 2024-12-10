/*
* لیست
* و وضعیت های پروژه
 */
function addListNode(response)
{
    $('#list-new').before(response.data);
    AddTask();
    UpdateList();
    initMoveTask();
}
$(document).ready(function() {
    $.fn.editable.defaults.mode = 'inline';
    UpdateList();// ویرایش عنوان لیست
//اضافه کردن لیست جدید
    $('#add-new-list').editable({
        name:  'username',
        emptytext: 'درج لیست جدید...',
        emptyclass : '',
        ajaxOptions : {
            type : 'post',
            dataType: 'json',
        },
        success: function(response, newValue) {
            if(response.success){
                response.action="addList";
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
        display: function(value, response){
            return false;
        },
    });
    $('#add-new-board').on('shown', function(e, editable) {
        editable.input.$input.val('');
    });
});

/**ویرایش کردن عنوان لیس
 */
function updateTitleListNode(response)
{
    $(response.list_id+' .list-title').editable('setValue', response.newValue);
}
function UpdateList()
{
    $('.list-title').editable({
        name:  'listTitle',
        showbuttons: false,
        ajaxOptions : {
            type : 'post',
            dataType: 'json',
        },
        success: function(response, newValue) {
            if(response.success){
                response.action="updateTitleList";
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
}
function archiveListNode(response)
{
    $(response.delete_div_id).fadeOut().remove();
    $('#menu_archive_list').html(response.data);
}
// آرشیو کردن لیست
$("body").on("click",".archive-this-list",function(){
    var $ajax_url=$(this).attr("href");
    var $delete_div_id=$(this).data('delete-list');
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="archiveList";
                response.delete_div_id=$delete_div_id;
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
});


//خارج کردن از حالت آرشیو


function archiveBackListNode(response)
{
    $('#'+response.list_id).parent().fadeOut();
    $(response.next_list_id).before(response.data);
    /*
     * initialize editable
     */
    AddTask();// in file project-task.js
    UpdateList();
    initMoveTask();
}

$("body").on("click",".restore-archive-this-list",function(){
    var $ajax_url=$(this).attr("href");
    var $id=$(this).attr("id");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.list_id=$id;
                response.action="archiveBackList";
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
});

// حرکت لیست ها
$( function() {
    initMoveStaus();
} );
function moveListNode(response)
{
    var new_pos_in_other_client;
    var index=$('#'+response.th).index();
    if(index!=response.new_position)
    {
        // تغیر موقعیت در سایر کلاینت هااا
        if(response.new_position==0)
        {
            new_pos_in_other_client=0;
            $('#'+response.th).insertBefore($('#'+response.th).parent().find(".list").eq(new_pos_in_other_client));
        }
        else if(index>response.new_position)
        {
            new_pos_in_other_client=response.new_position-1;
            $('#'+response.th).insertAfter($('#'+response.th).parent().find(".list").eq(new_pos_in_other_client));

        }else
        {
            new_pos_in_other_client=response.new_position;
            $('#'+response.th).insertAfter($('#'+response.th).parent().find(".list").eq(new_pos_in_other_client));
        }
    }


}
function initMoveStaus()
{
    var $stop=true;
    var $start_board="";
    var $stop_board="";
    $( ".lists" ).sortable({
        connectWith: ".list",
        update: function(event, ui) {
            var $this=ui.item.attr("id");
            var $new_position=ui.item.index();

            var $ajax_url=$('#'+$this).data("ajax-url");

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
                        response.action="moveList";
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
            //console.log("Resive => "+" Currect Panel - ul#" + $(this).attr("id")+" => New position: " +ui.item.index());
        },
        out: function( event, ui ) {
            $stop=false;
        },
    }).disableSelection();
}


/*
 نمایش منوی ایجاد تیم جدید
 */
function showPopOverTeam($this)
{
    var offset = $($this).position();
    var pop_over=$($this).data('pop-id');
    $.when($('.pop-over').fadeOut(500))
        .done(function() {
            $(pop_over)
                .fadeIn()
                .css({
                    right: '10px',
                    top: '50px',
                });

            setTimeout(function() { $(pop_over+' input').focus() }, 30);
        });

    return false;
}

/*
    سابمیت فرم ساخت تیم
 */
function CreateTeam() {
    var $value=$('#team-title_team').val();
    $('#msg-team-input').fadeOut();
    if($value)
    {
        $("#create-team-form").submit();
    }else {
        $('#msg-team-input').fadeIn();
        return false;
    }
}

/*
// منوی سمت راست
 */

/**
 ** نمایش فرم ویرایش پروژه
 */

var $html_menu=$('#pop-menu-menu').html();// ذخیره محتویات فرم منو برای نمایش هنگام زدن دکمه بک
var $html_update_project=$('#pop-menu-update-project').html();
var $html_member_project=$('#pop-menu-project-member').html();
var $html_team_project=$('#pop-menu-project-team').html();
var $html_activity_project = $('#pop-menu-project-activity').html();
function  showUpdateProjectForm($this)
{
    $('#pop-menu-menu').html($html_update_project);
    setTimeout(function() { $('#pop-menu-menu'+' input').focus() }, 30);
    /*
    **انتخاب رنگ برای پروژه
     */
    $('#update-project-form .mod-clickable').click(function(){
        $('#update-project-form .mod-clickable').children('i').removeClass('fa-check');
        $(this).children('i').addClass('fa-check');
        $('#color-board-input').val($(this).data('color'));
        return false;

    });
    return false;
}

/**
 // ویرایش پروژه
 */
function updateProjectNode(response)
{
    $(response.project_id).removeClass(response.old_color).addClass(response.new_color); // تغیر رنگ بک گراند پروژه
    $(response.project_id +' .trello-breadcrumb' +' li.active').html(response.project_name); // تغیر نام پروژه در breadcrumb
    $(response.project_id).closest('html').find('head').find('title').html(response.project_name);// تغیر عنوان سایت
}
function updateProject($this)
{
    $('#msg-board-input').fadeOut();
    var $ajax_url=$($this).data("ajax-url");
    var $title=$('#title-board-input').val();
    var $public=$('#public-board-input').val();
    var $color=$('#color-board-input').val();

    if($title && $color)
    {
        $.ajax({
            url: $ajax_url,
            type: 'post',
            dataType: 'json',
            data: {'title': $title, 'public': $public, 'color':$color},
            success: function (response) {
                if(response.success)
                {
                    response.action="updateProject";
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
        $('#msg-board-input').fadeIn();
    }
    return false;
}

/**
 * نمایش فرم ممبر ها
 */
function  showMemberProject($this)
{
    $('#pop-menu-menu').html($html_member_project);

    typeInSearchMemberProject();
    return false;
}

/**
 * جستجوی کاربر
 */

function typeInSearchMemberProject()
{

    $('#search-project-member-input').keyup(function(e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 13)
            searchProjectMember(true);
        else
            $(this).data('timer', setTimeout(searchProjectMember, 500));
    });
}

function searchProjectMember(force)
{
    var searchString = $("#search-project-member-input").val();
    var $ajax_url=$("#search-project-member-input").data("ajax-url");
    if (!force && searchString.length < 3 && searchString.length > 0) return; //wasn't enter, not > 2 char
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        data: {'search_string': searchString},
        success: function (response) {
            if(response.success)
            {
                $('#result-project-member').html(response.data);
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

/**
 * اضافه کردن کاربر به پروژه
 * یا حذف کاربر
 */
function memberProjectNode(response)
{
    if($(response.project_id).length)
    {
        $html_member_project=response.data;
        $(response.project_id+' #pop-menu-menu').html($html_member_project);
        typeInSearchMemberProject();
    }
}
function memberProject($this)
{
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="memberProject";
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

/**
 * تغییر رل کاربر پروژه
 */
function changeRoleProjectMemberNode(response)
{
    if($(response.project_id).length) {
        $html_member_project = response.data;
        $(response.project_id+' #pop-menu-menu').html($html_member_project);
        typeInSearchMemberProject();
    }
}
function changeRoleProjectMember($this)
{
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="changeRoleProjectMember";
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


/**
 * نمایش فرم تیم ها
 */
function  showTeamProject($this)
{
    $('#pop-menu-menu').html($html_team_project);
    typeInSearchTeamProject();
    return false;
}

/**
 * جستجوی کاربر
 */

function typeInSearchTeamProject()
{
    $('#search-project-team-input').keyup(function(e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 13)
            searchProjectTeam(true);
        else
            $(this).data('timer', setTimeout(searchProjectTeam, 500));
    });
}

function searchProjectTeam(force)
{
    var searchString = $("#search-project-team-input").val();
    var $ajax_url=$("#search-project-team-input").data("ajax-url");
    if (!force && searchString.length < 3 && searchString.length > 0) return; //wasn't enter, not > 2 char
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        data: {'search_string': searchString},
        success: function (response) {
            if(response.success)
            {
                $('#result-project-team').html(response.data);
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
/**
 * اضافه کردن تیم به پروژه
 * یا حذف تیم
 */
function teamProjectNode(response)
{
    if($(response.project_id).length) {
        $html_team_project=response.data;
        $(response.project_id+' #pop-menu-menu').html($html_team_project);
        typeInSearchTeamProject();
    }
}
function teamProject($this)
{
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="teamProject";
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

/** 
 * نمایش فرم فعالت ها
*/

function showActivityProject($this) {
    $('#pop-menu-menu').html($html_activity_project);
    return false;
}

function getActivitiesPage ($this, page) {
    $.ajax({
        url: 'ajax-get-activities',
        method: 'get',
        data: {
            page: page,
        },
        success: function (response) {
            $('#pop-menu-menu').html(response)
        },
        error: function () {
            console.error('Error:', error)
        }
    })
}

/*
* دکمه برگشت به منو
 */
function backMenu($this)
{
    $('#pop-menu-menu').html($html_menu);
    return false;
}