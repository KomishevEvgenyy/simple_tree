$(document).ready(function () {
    // вызов события click при добавлении корня (нажатии на кнопку с id=create-tree)
    $("#create-root").click(function () {
        createRoot($(this).closest('li').attr('id'));
    });
    showRoot();
    // вызов события click при добавлении узла (нажатии на кнопку с class=add)
    $('#tree').on('click', '.add', function () {
        createNode($(this).closest('li').attr('id'));
    });
    // вызов события click при удалении узла (нажатии на кнопку с class=delete)
    $('#tree').on('click', '.delete', function () {
        $('#open-modal').modal('show');
        $('#open-modal').click('#delRoot', function () {
            deleteRoot($('.delete').closest('li').attr('id'));
            $('#open-modal').modal('hide');
        });
        setTimeout(function () {
            if($('#open-modal').hasClass('show')){
                $('#open-modal').modal('hide');
            }
        }, 5000);
        //deleteRoot($(this).closest('li').attr('id'));
    });
});


// метод для добавления корня. Принимает id родителя. В случае эсли parent_id не существует присвоить переменной parent_id 0
function createRoot(parent_id) {
    parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    $.ajax({
        url: 'app/main.php',
        type: 'post',
        data: {parent_id: parent_id, text: text},
        dataType: 'text',
        //  получение данных из сервера в виде объекта JSON и приобразование его в массив
        success: function (data) {
            let result = JSON.parse(data);
            $('#tree').append($("<ul style='list-style-type: none;'><li id='" + result.id + "' parent_id='" + parent_id + "'><p class='mb-0 pl-3'>" + text + "</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
        }
    });
}

// метод для добавления узла. Принимает id родителя.
function createNode(parent_id) {
    parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    $.ajax({
        url: 'app/main.php',
        type: 'post',
        data: {parent_id: parent_id, text: text},
        dataType: 'text',
        //  получение данных из сервера в виде объекта JSON и приобразование его в массив
        success: function (data) {
            let result = JSON.parse(data);
            $('#' + parent_id).append($("<ul style='list-style-type: none;'><li id='" + result.id + "' parent_id='" + parent_id + "'><p class='mb-0 pl-3'>" + text + "</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
        }
    });
}

//  Метод для данных полученых из серверной части приложение. Если данные отсутствуют то выводит пустую строку
function showRoot() {
    $.ajax({
        url: 'app/main.php',
        type: 'get',
        //  получение данных из сервера в виде объекта JSON и приобразование его в массив
        success: function (data) {
            if (data) {
                let result = JSON.parse(data);
                //  нулевой элемент массива выводится в теге div с id=tree как корень дерева DOM
                $.each(result, function (key, value) {
                    if (value.parent_id === 0) {
                        // Если parent_id равен 0 то элемент добавляется в блоке div с id=tree
                        $('#tree').append($("<ul style='list-style-type: none;'><li id='" + value['id'] + "' " +
                            "parent_id='" + value['parent_id'] + "'><p class='mb-0 pl-3'>" + value['text'] + "</p>" +
                            "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button>" +
                            "<li></ul>"));
                    }
                });
                //  Элементы массива которые больше 0 элемента и являются узлами, выводится в родительском теге li с id=родителя
                $.each(result, function (i, item) {
                    if (item.parent_id > 0) {
                        // если parent_id больше 0 то элемент добавляется в элемент с id который равен parent_id
                        $('#' + item.parent_id).append($("<ul style='list-style-type: none;'><li id='" + item['id'] + "' " +
                            "parent_id='" + item['parent_id'] + "'><p class='mb-0 pl-3'>" + item['text'] + "</p>" +
                            "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button>" +
                            "<li></ul>"));
                    }
                });
            } else return "";
        }
    });
}

// Метод для удаления корневого элемента с его дочерними элементами если таковы есть
function deleteRoot(id) {
    let arr = [];
    let idElem = $('#' + id);
    $.each(idElem.find('li'), function (i, el) {
        // запись в массив id всех вложенных дочерних эдементов
        arr.push(el['id']);
    })
    // удаление пустых строк
    let result = arr.filter(function (e) {
        return e
    })
    //  добавление родительского id элемента в массив
    result.unshift(id)
    $.ajax({
        url: 'app/main.php/',
        type: 'delete',
        data: JSON.stringify({'id': result}),
        success: function (data) {
            //  удаление родительского элемента с дочерними если таковы имеются
            $('#' + id).closest('ul').remove();
        }
    });
}

//  Метод для закрытия модального окна через 20 секунд. loginModal1 id основного тега div в котором лежит модальное окно
// setTimeout(function(){
//     document.getElementById('loginModal1').style.display = 'none';
// }, 20000);

// $('#container').on('click', '#deleteElement', function () {
//     $('#open-modal').modal('show');
//     setTimeout(function () {
//         if($('#open-modal').hasClass('show')){
//             $('#open-modal').modal('hide');
//         }
//     }, 3000);
// });

// $('#container').on('click', '#deleteElement', function () {
//    setTimeout(function () {
//        $('open-modal').modal('hide');
//    }, 5000)
// });