ClassicEditor.create( document.querySelector( '#editor' ), {
    toolbar: {
        items: [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            '|',
            'indent',
            'outdent',
            '|',
            'imageUpload',
            'blockQuote',
            'insertTable',
            'mediaEmbed',
            'undo',
            'redo'
        ]
    },

    language: 'ru',
    
    image: {
        toolbar: [
            'imageTextAlternative',
            'imageStyle:full',
            'imageStyle:side'
        ],
        types: ['jpeg', 'png']
    },

    table: {
        contentToolbar: [
            'tableColumn',
            'tableRow',
            'mergeTableCells'
        ]
    },

    licenseKey: '',
    
    cloudServices: {
        tokenUrl: 'https://71817.cke-cs.com/token/dev/hJgjgyB8ThWqC368npr0ctU9xZopG8kYptAg1gI0R5b5DR2rkbHrAwZ8r7kl',
        uploadUrl: 'https://71817.cke-cs.com/easyimage/upload/'
    }
}).then( editor => {
    window.editor = editor;
}).catch( error => {
    console.error( 'Oops, something gone wrong!' );
    console.error( 'Please, report the following error in the https://github.com/ckeditor/ckeditor5 with the build id and the error stack trace:' );
    console.warn( 'Build id: yw4wblmhcu6-8o65j7c6blw0' );
    console.error( error );
});