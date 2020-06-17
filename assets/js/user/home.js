
    //Detectamos evento 'click' sobre las diferentes categorías
    $('ul.js-ul-categories li').on('click', function () {

        //Obtenemos los elementos del DOM
        let element_li_category = $(this);
        let element_hide_html_general_container = $('div.js-hide-html-container-products');
        let element_hide_html_container_product = element_hide_html_general_container.find('div.js-product-category');
        let element_hide_html_product_name = element_hide_html_container_product.find('.js-h4-product-name');

        //Ejecutamos la petición Ajax
        $.ajax({
            type : 'POST',
            url : FULL_WEB_URL+'ajax/user/products.php',
            data : {
                id_category : element_li_category.attr('data-number'),
                action: 'SELECT-PRODUCTS'
            },
            beforeSend: function(){

            },
            success : function (response) {

                //Parseamos la respuesta a formato JSON
                let json_object = $.parseJSON(response);

                console.log(json_object);

                //Validamos si hubo o no éxito
                if (json_object.status === '200'){

                }
                else{

                    //notify_error_notification(json_object.message);
                    return false;
                }
            },
            complete: function () {

            }
        });
    });