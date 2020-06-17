

    /**
     * @Description: Método para notificación de 'Exito'
     */
    function notify_success_notification(message, time = 4000){

        $.notify({
            icon: '<i class="material-icons text-white">check_circle</i>',
            message: message
        },{
            type: 'success',
            timer: time,
            placement: {
                from: 'top',
                align: 'right'
            }
        });
    }

    /**
     * @Description: Método para notificación de 'Error'
     */
    function notify_error_notification(message, time = 4000){

        $.notify({
            icon: '<i class="material-icons text-white">error</i>',
            message: message
        },{
            type: 'danger',
            timer: time,
            placement: {
                from: 'top',
                align: 'right'
            }
        });
    }

    /**
     * @Description: Método para notificación de 'Advertencia'
     */
    function notify_warning_notification(message, time = 4000){

        $.notify({
            icon: '<i class="material-icons text-white">warning</i>',
            message: message
        },{
            type: 'warning',
            timer: 4000,
            placement: {
                from: 'top',
                align: 'right'
            }
        });
    }

    /**
     * @Description: Método para validar texto com todos os caracteres
     */
    function validateFullString(index, length = 128, message = 'Este campo é obrigatório.') {

        let value = index.val();

        if (value === null || value === "" || value.length === 0){

            index.addClass('border-bottom border-danger');
            notify_warning_notification(message);
            return false;
        }
        else if (value.length > length){
            index.addClass('border-bottom border-danger');
            notify_warning_notification(message);
            return false;
        }
        else{
            index.removeClass('border-bottom border-danger');
            return true;
        }
    }

    function validateEmail(email) {
        let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function validateNumber(number) {
        let regExp = /^([0-9])*$/;
        return regExp.test(number);
    }

    /**
     * @Description: Método para marcar o borde de cor vermelho quando um processo errou
     */
    function markErrorBorder(index, message = 'Ocorreu um erro.') {

        index.addClass('border-bottom border-danger');
        notify_warning_notification(message);
        return false;
    }




    if(Url.getUrlGetParam('success')){
        $.notify({
            icon: "check_circle",
            title: "<b>Accion Realizada</b> ",
            message: Url.decoder(Url.getUrlGetParam('success'))
        }, {
            type: 'success',
            timer: 4000
        });
        Url.clean();
    }
    else if(Url.getUrlGetParam('error')){
        $.notify({
            icon: "cancel",
            title: "<b>Accion no realizada</b> ",
            message: Url.decoder(Url.getUrlGetParam('error'))
        }, {
            type: 'danger',
            timer: 4000
        });
        Url.clean();
    }
    else if(Url.getUrlGetParam('info')){
        $.notify({
            icon: "info",
            title: "<b>Información</b> ",
            message: Url.decoder(Url.getUrlGetParam('info'))
        }, {
            type: 'info',
            timer: 4000
        });
        Url.clean();
    }
    else if(Url.getUrlGetParam('backButton')){
        Url.clean();
    }

    //aqui se oculta un elemento un elemento como parametro es el selecto de clave jquery
    function elementHide(Element) {
        $('.' + Element).css('display', 'none');
    }

    //aqui se muestra un elemento un elemento como parametro es el selecto de clave jquery
    function elementShow(Element) {
        $('.' + Element).css('display', 'block');
    }

    /**
     * Este cdigo hace funcionar correctamente las paginaciones
     */
    $('document').ready(function() {

        $(".jsPreviousPageButon").click(function(){
            redirectToPage('previous');
        });

        $(".jsNextPageButon").click(function(){
            redirectToPage('next');
        });

        $(".jsLastPageButon").click(function(){
            //Se obtiene el numero de la ultima pagina
            lastPageNumber = $(this).attr("data-pagination-lastpage");

            redirectToPage('last', lastPageNumber);
        });

        $(".jsFirstPageButon").click(function(){
            redirectToPage('first');
        });

        //Incicializador de 'Date Time Picker'
        $('.datetimepicker').datetimepicker({
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        });
    });


    function redirectToPage(pageType, lastPage = 1){
        //Se obtiene la url completa
        var completeUrl = Url.getFullRaw();

        //Se carga el numero de la pagina
        var pageNumber = Url.getUrlGetParam('page');

        //Se setea en 1 si no existe la pagina
        if(isNaN(pageNumber) || pageNumber === undefined || pageNumber === false){
            pageNumber = 1;
        }

        //Se elimina el parametro pagina de la url
        var completeUrlWitouthPage = Url.getUrlDeleteOneParameter(completeUrl, 'page');

        //Se cargan los numeros de pagina siguiente y anterior
        var nextPage = parseInt(pageNumber) + parseInt(1);
        var previousPage = parseInt(pageNumber) - parseInt(1);

        //Se selecciona uno de los numeros de pagina dependiendo del tipo de peticion
        if(pageType === 'next'){
            var pageNumberCalculated = nextPage;
        }
        else if(pageType === 'previous'){
            var pageNumberCalculated = previousPage;
        }
        else if(pageType === 'first'){
            var pageNumberCalculated = 1;
        }
        else if(pageType === 'last'){
            var pageNumberCalculated = lastPage;
        }
        else{
            var pageNumberCalculated = 1;
        }

        //Si por alguna razon falla el calculo de la pagina, se pone en 1
        if(pageNumberCalculated <= 0){
            pageNumberCalculated = 1;
        }

        //Se crea la url final
        var finalUrl = Url.addParam(completeUrlWitouthPage, 'page=' + pageNumberCalculated);

        //Se hace la redireccion
        Url.redirect(finalUrl);
    }



    /**
     * @Description: Método que posiciona el 'cursor' al final de un 'input-textarea-div'
     */
    function position_cursor_at_end(element, type) {

        //Evaluamos si el 'tipo' es un elemento con valor
        if (type !== 'div'){

            //Obtenemos el valor
            value = element.val();

            //Foqueamos, limpiamos y foquemaos nuevamente el 'input-tetarea' para ubiacarlo al final
            element.focus().val("").val(value);

            //Movemos el scroll en los casos del 'textarea'
            element.scrollTop(element[0].scrollHeight);
        }
        else
            return false
    }


    $('.modal_productos').click(function (){
        $("#modal_producto").modal("show");
        $('.modal-title').text('CARGANDO .........');
        $('.desc-img').text('');
        $('.desc-precio').text('');
        $('.img-modal').attr('src','');
        var id_producto =$(this).parents('.padre_producto').find('[data_id_producto]').attr('data_id_producto');

        $.ajax({
            type: 'POST',
            url: FULL_WEB_URL+'ajax/user/products.php',
            dataType: 'json',
            data: {
                action:'SEARCH_PRODUCTO',
                id_producto:id_producto
            },
            success: function (response) {

                //Nos validamos o estado da petiçao
                if (response.status === '200'){
                    var data_producto = response.data;
                    console.log(data_producto)
                    $('.modal-title').text(data_producto['nombre']);
                    $('.desc-img').text(data_producto['descripcion']);
                    $('.desc-precio').text(data_producto['precio']);
                    $('.img-modal').attr('src',FULL_ASSETS_URL + 'img/galery/' +  data_producto['imagen']);

                }
                else
                    notify_error_notification(response.message);
            }
        });

    });

    $('.modal_productos_galeria').click(function (){
        $("#modal_producto").modal("show");
        $('.modal-title').text('CARGANDO .........');
        $('.desc-img').text('');
        $('.desc-precio').text('');
        $('.img-modal').attr('src','');
        var id_producto =$(this).parents('.padre_producto').find('[data_id_producto]').attr('data_id_producto');

        $.ajax({
            type: 'POST',
            url: FULL_WEB_URL+'ajax/user/products.php',
            dataType: 'json',
            data: {
                action:'SEARCH_GALERIA',
                id_producto:id_producto
            },
            success: function (response) {

                //Nos validamos o estado da petiçao
                if (response.status === '200'){
                    var data_producto = response.data;
                    console.log(data_producto)
                    $('.modal-title').text(data_producto['nombre']);
                    $('.img-modal').attr('src',FULL_ASSETS_URL + 'img/galery/' +  data_producto['imagen']);

                }
                else
                    notify_error_notification(response.message);
            }
        });

    });

    $('.closed-modal').click(function () {
        $("#modal_producto").modal("hide");
    });


