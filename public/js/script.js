console.log('HEYY')

$('.rating input').change(function(e) {
    var value = $(this).val()
    $('#inputnote').val(value)
    $('.rating input').removeClass('jaune')
    $('.rating input').each(function(e){
        var inputval = $(this).val()
        // console.log(value, inputval, inputval <= value)
        if (inputval <= value) {
            $(this).addClass('jaune')
        }
    })
})



