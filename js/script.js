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
//             url: 'app/main.php',
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
//             url: 'app/main.php',
//             type: 'POST',
//             data: ({textDel : rootText}),
//             dataType: 'text',
//             // success: function(data){
//             //     alert(data);
//             // }
//         });
//     });
// });
// Второй вариант если не указывать поле id как auto increment
var lastId = 0;     // последний созданный id

$(document).ready(function () {
    $("#create-root").click(function () {
        $("#tree").append(addRoot($(this).siblings('p').attr('elem_id')));
    });
    $('#tree').on('click', '.add', function () {
        $(this).closest('li').append(addRoot($(this).siblings('p').attr('elem_id')));
    });
    $('#tree').on('click', '.delete', function () {
        $(this).closest('li').remove();
    });
});

// метод для добавления элемента. Принимает id родителя
function addRoot(parent_id) {
    parent_id = parent_id ? parent_id : 0;
    let text = "Root";
    let root = "<ul class='ul' style='list-style-type: none;'>" +
        "<li><p id='root' elem_id='"+(++lastId)+"' parent_id='"+parent_id+"' class='mb-0 pl-3'>"+text+"</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button > " +
        "</li></ul>";

    // здесь вы можете дописать отправку данных на сервер.
    $.ajax({
            url: 'app/main.php',
            type: 'POST',
            data: ({id: lastId, parent_id: parent_id, text: text}),
            dataType: 'text',
            success: function(data){
                alert(data);
            }
        });

    return root;
}



