var Server;
$(document).ready(function () {
    Server = new FancyWebSocket('ws://' + PORT);
    Server.bind('open', function () {

    });
    Server.bind('close', function (data) {

    });
    Server.bind('message', function (payload) {

    });
    Server.connect();
});
function send(text) {
    Server.send('message', text);
}

var FancyWebSocket = function (url) {
    var callbacks = {};
    var ws_url = url;
    var conn;

    this.bind = function (event_name, callback) {
        callbacks[event_name] = callbacks[event_name] || [];
        callbacks[event_name].push(callback);
        return this;// chainable
    };

    this.send = function (event_name, event_data) {
        this.conn.send(event_data);
        return this;
    };

    this.connect = function () {
        if (typeof (MozWebSocket) == 'function')
            this.conn = new MozWebSocket(url);
        else
            this.conn = new WebSocket(url);

        // dispatch to the right handlers
        this.conn.onmessage = function (evt) {
            if (evt != '') {
                var JSONdata = JSON.parse(evt.data);
                var sesionData = JSONdata.id_usuario;
                var contacto = JSONdata.id_contacto;
                if (contacto == SESION) {
                    notificacion(evt.data);
                }
            }
            dispatch('message', evt.data);
        };

        this.conn.onclose = function () { dispatch('close', null) }
        this.conn.onopen = function () { dispatch('open', null) }
    };

    this.disconnect = function () {
        this.conn.close();
    };

    var dispatch = function (event_name, message) {
        if (message == null || message == "") {
            console.log('no envio');
        } else {
            var JSONdata = JSON.parse(message);
            var cont_conversa = $('#contacto').val();
            var messageData = JSONdata.mensaje;
            var sesionData = JSONdata.id_usuario;
            var dateTime = JSONdata.fecha_crea;
            var contacto = JSONdata.id_contacto;
            $(`#usuario${contacto} span`).html(JSONdata.cant_pend);
            if (SESION == sesionData && cont_conversa == contacto) {
                $('#containerMessages').append(`
                <div class="row justify-content-start">
                <div class="col-10">
                <p class="mb-0">${messageData}
                </p>
                <sub><small>${dateTime}</small></sub>
                </div>
                </div>
                <hr>`);
            } else {
                if (contacto == SESION && sesionData == cont_conversa) {
                    $('#containerMessages').append(`
                    <div class="row justify-content-end">
                        <div class="col-10">
                            <p class="mb-0">${messageData}
                            </p>
                            <sub><small>${dateTime}</small></sub>
                        </div>
                    </div>
                    <hr>`);
                }
            }
        }
    }
};


var notificacion = function (data) {
    data = JSON.parse(data);
    console.log(Notification.permission);
    Notification.requestPermission().then(perm => {
        if (perm === "granted") {

            var  options  =   {
                body:   `Tienes un mensaje en el chat de ${data.name}`,
                icon:   "https://www.acobarras.com/sidpa/public/img/img_sidpa/sidpa_ico.ico",
            };
            var notification = new Notification("Notificación Chat Sidpa",options);
        }
        //  else if (Notification.permission !== "denied") {
        //     Notification.requestPermission().then(function (permission) {
        //       // Si el usuario nos lo concede, creamos la notificación
        //       if (permission === "granted") {
        //         var notification = new Notification("¡Hola!");
        //       }
        //     });
        //   }
    });

    // $(`#usuario${data.id_usuario} span`).html(data.cant_pend);
    // notifyMe.create({
    //     position: 'topRight', //Positions are: ['topRight', 'topLeft', 'bottomLeft', 'bottomRight'],optional field with default value: 'topRight'
    //     type: 'info', // Types are: ['info', 'warning', 'success', 'error'], optional field with default value: 'info'
    //     title: 'Notificación Chat Sidpa', //required field
    //     text: `Tienes un mensaje en el chat de ${data.name}`, //required field
    //     timeout: 5000, //Remove notification after timeout expire, optional field without default value
    //     pauseOnHover: true, //Pause the timeout when mouse is over the notification, true or false, optional field with default value: false
    //     closeBtn: true, //Shows a close button, true or false, optional field with default value: true
    //     addClass: 'additional-class', // Add 'additional-class' into existing class names, optional field without default value
    //     click: function () { //Click event handler function, optional field without default value
    //         alert('clicked');
    //     }
    // });
}