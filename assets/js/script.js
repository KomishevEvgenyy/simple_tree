$(document).ready(function () {
    $("#create-root").click(function () {
        createRoot(0);
    });
    showRoot();
    $('#tree').on('click', '.add', function () {
        createNode($(this).closest('li').attr('id'));
    });
    $('#tree').on('click', '.delete', function () {
        deleteRoot($(this).closest('li').attr('id'));
    });
});

function createRoot(parent_id) {
    // метод для добавления корня. Принимает id родителя
    // parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    // отправка данных на сервер.
    $.ajax({
        url: 'app/main.php',
        type: 'post',
        data: ({parent_id: parent_id, text: text}),
        dataType: 'text',
        success: function (data) {
            let result = JSON.parse(data);
            $('#tree').append($("<ul style='list-style-type: none;'><li id='" + result.id + "' " +
                "parent_id='" + parent_id + "'><p class='mb-0 pl-3'>" + text + "</p>" +
                "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
        }
    });
}

function createNode(parent_id) {
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
            $('#' + parent_id).append($("<ul style='list-style-type: none;'><li id='" + result.id + "' " +
                "parent_id='" + parent_id + "'><p class='mb-0 pl-3'>" + text + "</p>" +
                "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
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
                    if (value.parent_id === 0) {
                        // Если parent_id равен 0 то элемент добавляется в блоке div с id=tree
                        $('#tree').append($("<ul style='list-style-type: none;'><li id='" + value['id'] + "' " +
                            "parent_id='" + value['parent_id'] + "'><p class='mb-0 pl-3'>" + value['text'] + "</p>" +
                            "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
                    }
                });
                $.each(result, function (i, item) {
                    if (item.parent_id > 0) {
                        // если parent_id больше 0 то элемент добавляется в элемент с id который равен parent_id
                        $('#' + item.parent_id).append($("<ul style='list-style-type: none;'><li id='" + item['id'] + "' " +
                            "parent_id='" + item['parent_id'] + "'><p class='mb-0 pl-3'>" + item['text'] + "</p>" +
                            "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
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
    let arr = [];
    let idElem = $('#' + id);
    $.each(idElem.find('li'), function (i, el) {
        // запись в массив все вложенные дочерние эдементы с значением id
        arr.push(el['id']);
    })
    let result = arr.filter(function (e) {
        return e
    })
    // удаление пустых строк
    result.unshift(id)
    // добавление родительского id к массиву
    $.ajax({
        url: 'app/main.php/',
        type: 'delete',
        data: JSON.stringify({'id': result}),
        success: function (data) {
            console.log(data);
            $('#' + id).closest('li').remove();
        }
    });
}

