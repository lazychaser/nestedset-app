(function ($, window, document, undefined) {
    
    var input = $('.editor-value');

    if (!input.length) return;

    var editorInput = $('<div></div>').insertAfter(input);

    var editor = ace.edit(editorInput[0]),
        session = editor.getSession();

    editor.setTheme('ace/theme/chrome');
    session.setValue(input.val());
    session.setMode('ace/mode/markdown');

    session.on('change', function () { input.val(session.getValue()); });

})(jQuery, window, window.document);