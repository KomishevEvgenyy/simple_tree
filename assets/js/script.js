let modalTimer;
$(document).ready(function () {

    /*
     * When the click event of the "Create Root" button is triggered, the createRoot function is called.
     * the id of the root is passed.
     * */
    $("#create-root").click(function () {
        createRoot($(this).closest('li').attr('id'));
    });

    //  Calling a function to get data from the database when the page is loaded, if any.
    showRoot();

    /*
     * When the click event of the "Root" node adding button is triggered, the createNode function is called.
     * the node id is passed.
     * */
    $('#tree').on('click', '.add', function () {
        createNode($(this).closest('li').attr('id'));
    });

    /*
     * When the click event of the "Root" node deletion button is triggered:
     * 1 - a modal window opens;
     * 2 - the timeCount () function is called, which starts a timer for 20 seconds, after which the window will be
     * closed automatically if you do not select one of the actions
     * 3 - the timer reset function is called
     * */
    $('#tree').on('click', '.delete', function () {
        $('#open-modal').modal('show');
        startCount();
        modalTimer = setTimeout(function () {
            if ($('#open-modal').hasClass('show')) {
                $('#open-modal').modal('hide');
                stopCount();
            }
        }, 20000);
    });

    /*
    * When the click event of the "Yes" node deletion confirmation button is triggered:
    * 1 - the deleteRoot() function is called to which the node id is passed
    * 2 - the modal window is closed
    * 3 - the timer reset function is called
    * */
    $('#open-modal').on('click', '#delRoot', function () {
        deleteRoot($('.delete').closest('li').attr('id'));
        $('#open-modal').modal('hide');
        stopCount();
    });

    /*
    * When the click event of the cancel button "No" or x is triggered:
    * 1 - the modal window is closed
    * 2 - the timer reset function is called
    * */
    $('#open-modal').on('click', '.exit', function () {
        $('#open-modal').modal('hide');
        stopCount();
    });
});

/*
 * Function for adding a root. Accepts the id of the parent. If parent_id does not exist, assign
 * variable parent_id value 0
 * */
function createRoot(parent_id) {
    parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    $.ajax({
        url: 'app/main.php',
        type: 'post',
        data: {parent_id: parent_id, text: text},
        dataType: 'text',
        //  Receiving data from the server as a JSON object and converting it to an array
        success: function (data) {
            let result = JSON.parse(data);
            //  Adding a root to a DOM element
            $('#tree').append($("<ul class='pt-2' style='list-style-type: none;'><li id='" + result.id + "' parent_id='" + parent_id + "'><p class='mb-0 pl-3'>" + text + "</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
        }
    });
}

// Function for adding a node. Accepts the id of the parent.
function createNode(parent_id) {
    parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    $.ajax({
        url: 'app/main.php',
        type: 'post',
        data: {parent_id: parent_id, text: text},
        dataType: 'text',
        //  Receiving data from the server as a JSON object and converting it to an array
        success: function (data) {
            let result = JSON.parse(data);
            //  Adding a node to a parent DOM element
            $('#' + parent_id).append($("<ul class='pt-2' style='list-style-type: none;'><li id='" + result.id + "' parent_id='" + parent_id + "'><p class='mb-0 pl-3'>" + text + "</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
        }
    });
}

//  Method for data received from the server side of the application. If there is no data, then it
//  outputs an empty string
function showRoot() {
    $.ajax({
        url: 'app/main.php',
        type: 'get',
        //  Receiving data from the server as a JSON object and converting it to an array
        success: function (data) {
            if (data) {
                let result = JSON.parse(data);
                //  Array element zero is rendered in the div element with id = tree as the root of the DOM tree
                $.each(result, function (key, value) {
                    if (value.parent_id === 0) {
                        // If parent_id is 0, then the element is added in the div tag with id = tree
                        $('#tree').append($("<ul class='pt-2' style='list-style-type: none;'><li id='" + value['id'] + "' " +
                            "parent_id='" + value['parent_id'] + "'><p class='mb-0 pl-3'>" + value['text'] + "</p>" +
                            "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button>" +
                            "<li></ul>"));
                    }
                });
                //  Array elements that are greater than the 0th element and are nodes are displayed in the parent
                //  li tag with id = parent
                $.each(result, function (i, item) {
                    if (item.parent_id > 0) {
                        // If parent_id is greater than 0, then the element is added in the li tag with an id equal to parent_id
                        $('#' + item.parent_id).append($("<ul class='pt-2' style='list-style-type: none;'><li id='" + item['id'] + "' " +
                            "parent_id='" + item['parent_id'] + "'><p class='mb-0 pl-3'>" + item['text'] + "</p>" +
                            "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button>" +
                            "<li></ul>"));
                    }
                });
            } else return "";
        }
    });
}

//  Method for removing the root element with its children if any
function deleteRoot(id) {
    let arr = [];
    let idElem = $('#' + id);
    $.each(idElem.find('li'), function (i, el) {
        //  Writing to the id array of all nested children
        arr.push(el['id']);
    })
    // Removing blank lines
    let result = arr.filter(function (e) {
        return e
    })
    //  Adding the parent id of an element to an array
    result.unshift(id)
    $.ajax({
        url: 'app/main.php/',
        type: 'delete',
        data: JSON.stringify({'id': result}),
        success: function (data) {
            //  Removing parent element with children if any
            $('#' + id).closest('ul').remove();
        }
    });
}

//  A global variable that stores the number of seconds elapsed since the link was clicked
let count = 20;
//  Global variable storing timer ID
let timer;

/*
 * The function does the following:
 * 1 - outputs the values ​​of the variable count to the element with id = "countTime"
 * 2 - decreases the values ​​of the variable by 1
 * 3 - starts a timer that calls the timeCount() function after 1 second
 * 4 - if count is exactly or less than zero, then call the stopCount () function
 * 5 - close the modal window.
 * */
function timeCount() {
    document.getElementById("countTime").innerHTML = count.toString();
    count--;
    timer = window.setTimeout(function () {
        timeCount()
    }, 1000);
    if (count <= 0) {
        stopCount();
        $('#open-modal').modal('hide');
    }
}

//  The function checks the expression! Timer according to the rule of lies, if it is true,
//  then calls the timeCount() function
function startCount() {
    if (!timer)
        timeCount();
}

/*
 * The function tests the expression timer according to the rule of falsehood If it is true:
 * 1 - calls the clearTimeOut() method to reset the timer
 * 2 - calls the clearTimeOut() method to reset the modalTimer()
 * 3 - sets timer and modalTimer to null
 * 4 - sets the counter value to 20 seconds.
 * */
function stopCount() {
    if (timer) {
        clearTimeout(timer);
        clearTimeout(modalTimer);
        timer = null;
        modalTimer = null
        count = 20;
    }
}