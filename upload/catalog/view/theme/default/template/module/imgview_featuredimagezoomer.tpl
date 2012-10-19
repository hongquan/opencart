<script type='text/javascript'>
jQuery(document).ready(function($){
    /* The product image has ID 'image' in OpenCart 1.5.5 */
    var large = $('#image').parent().attr('href')
    console.log(large)
    $('#image').addimagezoom({
        magnifiersize: [400, 400],
        largeimage: large
    })
})
</script>