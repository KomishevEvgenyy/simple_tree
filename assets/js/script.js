$(document).ready(function () {
    $("#create-root").click(function () {
        createRoot($(this).siblings('li').attr('id'));
    });
    showRoot();
    $('#tree').on('click', '.add', function () {
        createNode($(this).siblings('li').attr('id'));
    });
    $('#tree').on('click', '.delete', function () {
        deleteRoot($(this).siblings('li').attr('id'));
    });
});

function createRoot(parent_id) {
    // метод для добавления корня. Принимает id родителя
    parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    // отправка данных на сервер.
    $.ajax({
        url: 'app/main.php',
        type: 'POST',
        data: ({parent_id: parent_id, text: text}),
        dataType: 'text',
        success: function (data) {
            let result = JSON.parse(data);
            if (result.success) {
                $('#tree').append($("<ul id='" + result.id + "' parent_id='" + parent_id + "' " +
                    "style='list-style-type: none;'>" + "<li><p class='mb-0 pl-3'>" + text + "" +
                    "</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+" +
                    "</button > " + "</li></ul>"));
            }
        }
    });
}

function createNode(parent_id) {
// метод для добавления нового узла. Принимает id родителя
    parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    // отправка данных на сервер.
    $.ajax({
        url: 'app/main.php',
        type: 'post',
        data: ({parent_id: parent_id, text: text}),
        dataType: 'text',
        success: function (data) {
            let result = JSON.parse(data);
            if (result.success) {
                $('ul #' + result.id - 1).append($("<ul id='" + result.id + "' parent_id='" + parent_id + "' " +
                    "style='list-style-type: none;'>" + "<li><p class='mb-0 pl-3'>" + text + "" +
                    "</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+" +
                    "</button > " + "</li></ul>"));
            }
        }
    });
}

function showRoot() {
    // метод для вывода дерева на экран
    $.ajax({
        url: 'app/main.php',
        type: 'get',
        success: function (data) {
            if (data) {
                let result = JSON.parse(data);
                $.each(result, function (key, value) {
                    if (key === 0) {
                        $('#tree').append($("<ul parent_id='" + value['parent_id'] + "' " +
                            "style='list-style-type: none;'>" + "<li id='" + value['id'] + "'><p class='mb-0 pl-3'>" + value['text'] + "" +
                            "</p><button class='delete btn btn-danger'>-</button> " +
                            "<button class='add btn btn-success'>+</button > " + "</li></ul>"));
                    } else {
                        $('ul #' + value['id'] - 1).append($("<ul " +
                            "style='list-style-type: none;'>" + "<li id='" + value['id'] + "'><p parent_id='" + value['parent_id'] + "' " +
                            "class='mb-0 pl-3'>" + value['text'] + "" +
                            "</p><button class='delete btn btn-danger'>-</button> " +
                            "<button class='add btn btn-success'>+</button > " + "</li></ul>"));
                    }
                });
            } else return "";
        }
    });
}

function editRoot(id) {
    // метод для редактирования данных
}

function deleteRoot(id) {

    // метод для удаления элемента с его дочерними узлами
    $.ajax({
        url: 'app/main.php',
        type: 'delete',
        data: ({id: id}),
        dataType: 'text',
        success: function (data) {
            // if (result.success) {
            //     $(this).closest('li').remove();
            // }
        }
    });
}
