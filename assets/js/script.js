// Второй вариант если не указывать поле id как auto increment
let lastId = 0;     // последний созданный id
$(document).ready(function () {
    $("#create-root").click(function () {
        $("#tree").append(createRoot($(this).siblings('p').attr('elem_id')));
    });
    $('#tree').on('click', '.add', function () {
        $(this).closest('li').append(createRoot($(this).siblings('p').attr('elem_id')));
    });
    $('#tree').on('click', '.delete', function () {
        $(this).closest('li').remove();
    });
});

function createRoot(parent_id) {
    // метод для добавления элемента. Принимает id родителя
    parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    let root = "<ul style='list-style-type: none;'>" +
        "<li><p id='root' elem_id='" + (++lastId) + "' parent_id='" + parent_id + "' class='mb-0 pl-3'>" + text + "</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button > " +
        "</li></ul>";
    // отправка данных на сервер.
    $.ajax({
        url: 'app/create.php',
        type: 'POST',
        data: ({create: 'create', id: lastId, parent_id: parent_id, text: text}),
        dataType: 'text',
        // success: function show(data) {
        // }
    });
    return root;
}
function showRoot(data) {
    $('#tree').find('#root').each(function (index, elem) {
        alert(elem);
    });
}
function editRoot(id) {
    // метод для редактирования данных
}
function deleteRoot(parent_id) {
    // метод для удаления корня
    // отправка данных на сервер.

    $.ajax({
        url: 'app/delete.php',
        type: 'delete',
        data: ({parent_id: parent_id}),
        dataType: 'text',
        // success: show(data)
    });
}

// Первый вариант
// let parentId;
// $(document).ready(function () {
//     let root = "<ul class='ul' style='list-style-type: none;'>" +
//         "<li value='0'><p id='root' class='mb-0 pl-3'>Root</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button>" +
//         "</li></ul>";
//     $("#create-root").click(function () {
//         $("#tree").append(root);
//         $("#create-root").remove();
//     });
//     $('#tree').on('click', '.add', function () {
//         $(this).closest('li').append(root);
//         let rootText = $('#root').text();
//         $.ajax({
//             url: 'app/create.php',
//             type: 'POST',
//             data: ({textAdd : rootText}),
//             dataType: 'text',
//             success: function(data){
//                 alert(data);
//             }
//         });
//     });
//     $('#tree').on('click', '.delete', function () {
//         $(this).closest('li').remove();
//         let rootText = $('#root').text();
//         $.ajax({
//             url: 'app/create.php',
//             type: 'POST',
//             data: ({textDel : rootText}),
//             dataType: 'text',
//             // success: function(data){
//             //     alert(data);
//             // }
//         });
//     });
// });




