let modalTimer;
$(document).ready(function () {

    /*
     * При срабатывании события click кпопки добавления корня "Create Root" вызывается функция createRoot которому
     * передается id корня.
     * */
    $("#create-root").click(function () {
        createRoot($(this).closest('li').attr('id'));
    });

    //  вызов функции для получение данных из БД при загрузки страницы если такие имеются.
    showRoot();

    /*
     * При срабатывании события click кпопки добавления узла "Root" вызывается функция createNode которому
     * передается id узла.
     * */
    $('#tree').on('click', '.add', function () {
        createNode($(this).closest('li').attr('id'));
    });

    /*
     * При срабатывании события click кпопки удалении узла "Root":
     * 1 - открывается модальное;
     * 2 - вызывается функция timeCount которая запускает таймер 20 секунд, после которого окно будет закрыто
     * автоматически если не выбрать одно из действий
     * 3 - вызывается функция сброса таймера
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
    * При срабатывании события click кпопки подтверждения удаления узла "Yes":
    * 1 - вызывается функция deleteRoot которому передается id узла
    * 2 - закрывается подальное окно
    * 3 - вызывается функция сброса таймера
    * */
    $('#open-modal').on('click', '#delRoot', function () {
        deleteRoot($('.delete').closest('li').attr('id'));
        $('#open-modal').modal('hide');
        stopCount();
    });

    /*
    * При срабатывании события click кпопки отмены "No" или x:
    * 1 - закрывается подальное окно
    * 2 - вызывается функция сброса таймера
    * */
    $('#open-modal').on('click', '.exit', function () {
        $('#open-modal').modal('hide');
        stopCount();
    });
});

/*
 * Функция для добавления корня. Принимает id родителя. В случае эсли parent_id не существует присвоить
 * переменной parent_id значение 0
 * */
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
            //  добавление корня в элемент DOM
            $('#tree').append($("<ul style='list-style-type: none;'><li id='" + result.id + "' parent_id='" + parent_id + "'><p class='mb-0 pl-3'>" + text + "</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button><li></ul>"));
        }
    });
}

// Функция для добавления узла. Принимает id родителя.
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
            //  добавление узла в родительский элемент DOM
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
                //  нулевой элемент массива выводится в элементе div с id=tree как корень дерева DOM
                $.each(result, function (key, value) {
                    if (value.parent_id === 0) {
                        // Если parent_id равен 0 то элемент добавляется в тег div с id=tree
                        $('#tree').append($("<ul style='list-style-type: none;'><li id='" + value['id'] + "' " +
                            "parent_id='" + value['parent_id'] + "'><p class='mb-0 pl-3'>" + value['text'] + "</p>" +
                            "<button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button>" +
                            "<li></ul>"));
                    }
                });
                //  Элементы массива которые больше 0-го элемента и являются узлами, выводится в родительском теге li с id=родителя
                $.each(result, function (i, item) {
                    if (item.parent_id > 0) {
                        // если parent_id больше 0 то элемент добавляется в тег li с id который равен parent_id
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

//  Метод для удаления корневого элемента с его дочерними элементами если таковы есть
function deleteRoot(id) {
    let arr = [];
    let idElem = $('#' + id);
    $.each(idElem.find('li'), function (i, el) {
        //  запись в массив id всех вложенных дочерних эдементов
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

//  глобальная переменная, хранящая количество секунд, прошедших с момента нажатия ссылки
let count = 20;
//  глобальная переменная, хранящая идентификатор таймера
let timer;

/*
 * Функция, выполняет следующее:
 * 1 - выводит значения переменной count в элемент с id="countTime"
 * 2 - уменьшает значения переменной на 1
 * 3 - запускает таймер, который вызовет функцию timeCount() через 1 секунду
 * 4 - если count равне или меньше нуля то вызывать функцию stopCount()
 * 5 - закрыть модальное окно.
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

/*
 * Функция проверяет выражение !timer по правилу лжи, если оно истинно, то вызывает функцию timeCount()
 * */
function startCount() {
    if (!timer)
        timeCount();
}

/*
 * функция проверяет выражение timer по правилу лжию Если оно истинно:
 * 1 - вызывает метод clearTimeOut() для сброса таймера timer
 * 2 - вызывает метод clearTimeOut() для сброса таймера modalTimer
 * 3 - присваивает переменной timer и modalTimer значение null
 * 4 - устанавливает значение счетчика на 20 секунды.
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