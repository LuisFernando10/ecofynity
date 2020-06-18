
    //Definimos las variables globales
    var ID_CATEGORY = ''; //Variable creada para controlar la clase que controla la animación de los productos al seleccionar la categoría

    //Detectamos evento 'click' sobre las diferentes categorías
    $('ul.js-ul-categories li').on('click', function () {

        //Obtenemos los elementos del DOM
        let element_li_category = $(this);
        let element_div_rea_container = $('div.js-product-real-container');
        let element_hide_html_general_container = $('div.js-hide-html-container-products');
        let element_hide_html_container_product = element_hide_html_general_container.find('div.js-product-category');
        let element_hide_html_product_name = element_hide_html_container_product.find('.js-h4-product-name');
        let element_hide_html_product_image = element_hide_html_container_product.find('.js-img-product-image');

        //Validamos si se seleccionó una categoría en específico o todas
        let id_category = element_li_category.attr('data-number');

        if (element_li_category.attr('data-filter') === '*') id_category = null;

        //Ejecutamos la petición Ajax
        $.ajax({
            type : 'POST',
            url : FULL_WEB_URL+'ajax/user/products.php',
            data : {
                id_category : id_category,
                action: 'SELECT-PRODUCTS'
            },
            beforeSend: function(){

            },
            success : function (response) {

                //Parseamos la respuesta a formato JSON
                let json_object = $.parseJSON(response);

                //Validamos si hubo o no éxito
                if (json_object.status === '200'){
                    
                    //Guardamos los productos retornados
                    let products_records = json_object.data_products;

                    //Vaciamos el contenerdor real
                    element_div_rea_container.html('');

                    //Recorremos los resultados obtenidos
                    products_records.forEach(row => {

                        //Removemos la clase anterior a los contenedores
                        element_hide_html_container_product.removeClass(ID_CATEGORY);

                        //Actualizamos los valores del HTML oculto
                        element_hide_html_container_product.addClass(row.id_categoria);
                        element_hide_html_product_image.attr('src', `${FULL_IMAGES_URL}galery/${row.imagen}`);
                        element_hide_html_product_name.text(row.nombre);

                        //Plasmamos el HTML oculto al contenedor real
                        element_div_rea_container.append(element_hide_html_general_container.html());

                        //Actualizamos la variable global
                        ID_CATEGORY = row.id_categoria;
                    });
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