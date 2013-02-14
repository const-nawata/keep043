<?php


//Attantion. Uncomment `extension=php_gd2.dll` in php.ini

?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>jQuery File Upload Example</title>
</head>
<body>

<div>
	<input id="fileupload" type="file" name="files[]" data-url="u/server/php/u2loader.php" multiple>
</div>
<!--   backup
<input id="fileupload" type="file" name="files[]" data-url="backup/" multiple>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
 -->
<script src="./js/jquery-1.9.1.js"></script>
<script src="./js/vendor/jquery.ui.widget.js"></script>
<script src="./js/jquery.iframe-transport.js"></script>
<script src="./js/jquery.fileupload.js"></script>
<script>

/*
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        add: function (e, data) {
            data.context = $('<button/>').text('Upload')
                .appendTo(document.body)
                .click(function () {
                    $(this).replaceWith($('<p/>').text('Uploading...'));
                    data.submit();
                });
        },
        done: function (e, data) {
            data.context.text('Upload finished.');
        }
    });
});
*/



/*
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        add: function (e, data) {
            data.context = $('<p/>').text('Uploading...').appendTo(document.body);
            data.submit();
        },
        done: function (e, data) {
            data.context.text('Upload finished.');
        }
    });
});
*/




$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
    });
});



</script>
</body>
</html>