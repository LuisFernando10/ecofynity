
$('.select_categoria').click(function () {
    // eliminamos el estado active en todos las categorias
    $('.select_categoria').removeClass('active');

    var href = $(this).attr('data_contenedor');
    // añadimos el active al seleccioando
    $(this).addClass('active')

    if (href == 'todos')
        $('[data_categoria]').show();
    else{

        // ocultamos todos los productos
        $('[data_categoria]').hide();

        //mostramos solo los productos de esta categoria.
        $('[data_categoria='+href+']').show();
    }
})