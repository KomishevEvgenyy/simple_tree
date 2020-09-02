let parentId;
$(document).ready(function () {
    let root = "<ul class='ul' style='list-style-type: none;'>" +
        "<li value='0'><p id='root' class='mb-0 pl-3'>Root</p><button class='delete btn btn-danger'>-</button> <button class='add btn btn-success'>+</button>" +
        "</li></ul>";
    $("#create-root").click(function () {
        $("#tree").append(root);
        $("#create-root").remove();
    });
    $('#tree').on('click', '.add', function () {
        $(this).closest('li').append(root);
        let rootText = $('#root').text();
        $.ajax({
           url: 'app/main.php',
           type: 'POST',
            data: ({textAdd : rootText, parentId: parentId}),
            dataType: 'text',
            success: function(data){

            }
        });
    });
    $('#tree').on('click', '.delete', function () {
        $(this).closest('li').remove();
        let rootText = $('#root').text();
        $.ajax({
            url: 'app/main.php',
            type: 'POST',
            data: ({textDel : rootText}),
            dataType: 'text',
            // success: function(data){
            //     alert(data);
            // }
        });
    });
});



