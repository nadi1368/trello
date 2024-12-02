function attach(){
    //file input field trigger when the drop box is clicked
    $("#dropBox").click(function(){
        $("#fileInput").click();
    });

    //prevent browsers from opening the file when its dragged and dropped
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    //call a function to handle file upload on select file
    $('input[type=file]').on('change', fileUpload);
}
function fileUploadNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-attach').html(response.attach_list);// بروز رسانی لیست فایل های آپلود شده در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
}
function fileUpload(event){
    //notify user about the file upload status
    $("#dropBox").html(event.target.value+" uploading...");

    var $ajax_url=$("#dropBox").data('ajax-url');

    //get selected file
    files = event.target.files;

    //form data check the above bullet for what it is
    var data = new FormData();

    //file data is presented as an array
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        if(
            !file.type.match('image.*') &&
            !file.type.match("application/msword") &&
            !file.type.match("application/excel") &&
            !file.type.match("application/x-excel") &&
            !file.type.match("application/zip") &&
            !file.type.match("application/x-compressed") &&
            !file.type.match("application/x-zip-compressed") &&
            !file.type.match("application/zip") &&
            !file.type.match("multipart/x-zip") &&
            !file.type.match("application/x-rar-compressed") &&
            !file.type.match("application/pdf")
        ) {
            //check file type
            $("#dropBox").html("شما مجاز به آپلود این فایل نمی باشید.");
        }else if(file.size > 3048576){
            //check file size (in bytes)
            $("#dropBox").html("متاسفانه حجم فایل شما زیاد است (>3 MB)");
        }else{
            //append the uploadable file to FormData object
            data.append('file', file, file.name);

            //create a new XMLHttpRequest
            var xhr = new XMLHttpRequest();

            //post file data for upload
            xhr.open('POST', $ajax_url, true);
            xhr.send(data);
            xhr.onload = function () {
                //get response and show the uploading status
                var response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.success) {
                    $("#dropBox").html("آپلود با موفقیت انجام شد.");
                    response.action="fileUpload";
                    socket.emit("send",response,function (callback) {});
                } else
                {
                    $("#dropBox").html(response.msg);
                }
            };
        }
    }
}
function deleteAttachNode(response)
{
    var modal_id='view_'+response.ajax_div;
    $('div[data-modal-task-id="'+modal_id+'"] '+'#list-attach').html(response.attach_list);// بروز رسانی لیست فایل های آپلود شده در مودال
    $(response.ajax_div).before(response.task_view).remove();// جای گزین کردن تسک با مقادیر جدید
}
function deleteAttach($this)
{
    var $ajax_url=$($this).data("ajax-url");
    $.ajax({
        url: $ajax_url,
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if(response.success)
            {
                response.action="deleteAttach";
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