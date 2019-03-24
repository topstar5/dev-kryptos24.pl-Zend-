jQuery(document).ready(function () {
    var iconPickerOpt = {cols: 5, searchText: "Buscar...", labelHeader: '{0} de {1} Pags.', footer: false};
    var options = {
        hintCss: {'border': '1px dashed #13981D'},
        placeholderCss: {'background-color': 'gray'},
        opener: {
            as: 'html',
            close: '<i class="fa fa-minus"></i>',
            open: '<i class="fa fa-plus"></i>',
            openerCss: {'margin-right': '10px'},
            openerClass: 'btn btn-success btn-xs'
        }
    };
    var editor = new MenuEditor('myEditor', {listOptions: options, iconPicker: iconPickerOpt, labelEdit: 'Edit'});
    editor.setForm($('#frmEdit'));
    editor.setUpdateButton($('#btnUpdate'));

    var data = $('#data').val();
    var json = JSON.parse(data);
    editor.setData(json);

    $("#btnUpdate").click(function(){
        var data = {};
        $('#frmEdit').find('.item-menu').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        console.log('ttt', data);
        if (validateFields(data)) {
            updateData(data);
            editor.update();
        }

    });
    $('#btnAdd').click(function(){
        var data = {};
        $('#frmEdit').find('.item-menu').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        console.log('ttt', data);
        if (validateFields(data)) {
            insertData(data);
            editor.add();
        }
    });

    function updateData(data) {
        var baseUrl = $('#base_url').val();
        $.ajax({
            dataType: 'json',
            url:        baseUrl + '/update-row',
            type:       'POST',
            data: { data: JSON.stringify(data) },
            async:      true,
            success: function(data, status) {
                console.log('success', data);
                window.location.reload();
            },
            error : function(xhr, textStatus, errorThrown) {
                alert('Ajax request failed.' + textStatus);
            }
        });
    }

    function insertData(data) {
        var baseUrl = $('#base_url').val();
        $.ajax({
            dataType: 'json',
            url:        baseUrl + '/insert-row',
            type:       'POST',
            data: { data: JSON.stringify(data) },
            async:      true,
            success: function(data, status) {
                // alert(data);
                console.log('success', data);
                window.location.reload();
            },
            error : function(xhr, textStatus, errorThrown) {
                alert('Ajax request failed.' + textStatus);
            }
        });
    }

    function validateFields(data) {
        if (data['path'].match(/\/(.+)/) || data['path']==='javascript:;') {
            return true;
        } else {
            alert('URL must start from / or must be javascript:;');
            return false;
        }
    }
});