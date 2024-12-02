$(document).ready(function() {
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'inline';

});
// var socket=io("localhost:3000");
class MySocket {
    emit(action,response){
        switch(response.action) {
            //label.js
            case 'createLabel':
                createLabelNode(response);
                break;
            case 'updateLabel':
                updateLabelNode(response);
                break;
            case 'labelTask':
                labelTaskNode(response);
                break;
            case 'deleteLabel':
                deleteLabelNode(response);
                break;

            //check-list.js
            case 'updateCheckList':
                updateCheckListNode(response);
                break;
            case 'deleteCheckList':
                deleteCheckListNode(response);
                break;
            case 'AddCheckListItem':
                AddCheckListItemNode(response);
                break;
            case 'UpdateCheckListItem':
                UpdateCheckListItemNode(response);
                break;
            case 'DoneCheckListItem':
                DoneCheckListItemNode(response);
                break;
            case 'DeleteCheckListItem':
                DeleteCheckListItemNode(response);
                break;

            //comments.js
            case 'createComments':
                createCommentsNode(response);
                break;

            //duedate.js
            case 'updateDueDate':
                updateDueDateNode(response);
                break;
            case 'complateDueDate':
                complateDueDateNode(response);
                break;
            case 'deleteDueDate':
                deleteDueDateNode(response);
                break;

            //attach.js
            case 'fileUpload':
                fileUploadNode(response);
                break;
            case 'deleteAttach':
                deleteAttachNode(response);
                break;

            //project-task.js
            case 'AddTask':
                AddTaskNode(response);
                break;
            case 'moveTask':
                moveTaskNode(response);
                break;
            case 'receiveTask':
                receiveTaskNode(response);
                break;
            case 'updateTitleTask':
                updateTitleTaskNode(response);
                break;
            case 'updateDescTask':
                updateDescTaskNode(response);
                break;
            case 'memberTask':
                memberTaskNode(response);
                break;
            case 'archiveTask':
                archiveTaskNode(response);
                break;
            case 'watchesTask':
                watchesTaskNode(response);
                break;
            // project-satus.js
            case 'addList':
                addListNode(response);
                break;
            case 'updateTitleList':
                updateTitleListNode(response);
                break;
            case 'archiveList':
                archiveListNode(response);
                break;
            case 'archiveBackList':
                archiveBackListNode(response);
                break;
            case 'moveList':
                moveListNode(response);
                break;
            case 'updateProject':
                updateProjectNode(response);
                break;
            case 'memberProject':
                memberProjectNode(response);
                break;
            case 'changeRoleProjectMember':
                changeRoleProjectMemberNode(response);
                break;
            case 'teamProject':
                teamProjectNode(response);
                break;
        }
    }
}

var socket=new MySocket();
