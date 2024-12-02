//جستجوی کاربر
$('#serac-user-input').keyup(function(e) {
    clearTimeout($.data(this, 'timer'));
    if (e.keyCode == 13)
        searchUser(true);
    else
        $(this).data('timer', setTimeout(searchUser, 500));
});

/*
// جستجوی یوزر
 */
function searchUser(force) {
    var searchString = $("#serac-user-input").val();
    var $ajax_url=$("#serac-user-input").data("ajax-url");
    if (!force && searchString.length < 3 && searchString.length > 0) return; //wasn't enter, not > 2 char
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        data: {'search_string': searchString},
        success: function (response) {
            if(response.success)
            {
                $('#result-search-user').html(response.data);
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
اضافه کردن کاربر به تیم
 */
function AddUser($this) {
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                $('#result-list-user').html(response.list_user);
                $('#result-search-user').html(response.search_user);
            }else
            {
                alert('خطایی رخ داده است.');
            }
        },

        error: function (e) {
            alert('خطایی رخ داده است.');
        }

    });//ajax*/
}

/*
حذف کردن کاربر از تیم
 */
function DeleteUser($this) {
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                $('#result-list-user').html(response.list_user);
                $('#result-search-user').html(response.search_user);
            }else
            {
                alert('خطایی رخ داده است.');
            }
        },

        error: function (e) {
            alert('خطایی رخ داده است.');
        }

    });//ajax*/
}

/*
تغیر رول یوزر تیم
 */
function changeRole($this) {
    var $ajax_url=$($this).data("ajax-url");
    var $val=$($this).val();
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        data:{'role':$val},
        success: function (response) {
            if(response.success)
            {
                $('#result-list-user').html(response.list_user);
                $('#result-search-user').html(response.search_user);
            }else
            {
                alert('خطایی رخ داده است.');
            }
        },

        error: function (e) {
            alert('خطایی رخ داده است.');
        }

    });//ajax*/

}

$('#createBoardModal .mod-clickable').click(function(){
    $('#createBoardModal .mod-clickable').children('i').removeClass('fa-check');
    $(this).children('i').addClass('fa-check');
    $('#color-board-input').val($(this).data('color'));
    return false;

});

/**
 * ساخت برد جدید در صفحه دیفالت
 * @param $this
 */
function createBoard($this)
{
    $('#msg-board-input').fadeOut();
    var $ajax_url=$($this).data("ajax-url");

    var $title=$('#title-board-input').val();
    var $team=$('#team-board-input').val();
    var $public=$('#public-board-input').val();
    var $color=$('#color-board-input').val();

    if($title && $public && $color)
    {
        $.ajax({
            url: $ajax_url,
            type: 'post',
            dataType: 'json',
            data: {'title': $title, 'team': $team, 'public': $public, 'color':$color},
            success: function (response) {
                if(response.success)
                {
                    window.location.href = response.url_new_board;
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
}

/**
 * ساخت تیم جدید در صفحه دیفالت
 * @param $this
 */
function createTeam($this)
{
    $('#msg-team-input').fadeOut();
    var $ajax_url=$($this).data("ajax-url");

    var $title=$('#title-team-input').val();

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
                    window.location.href = response.url_new_board;
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
        $('#msg-team-input').fadeIn();
    }
}