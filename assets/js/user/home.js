
    //Detectamos evento 'click' sobre las diferentes categorías
    $('ul.js-ul-categories li').on('click', function () {

        //Obtenemos los elementos del DOM
        let element_li_category = $(this);

        console.log(element_li_category.attr('data-number'));
    });