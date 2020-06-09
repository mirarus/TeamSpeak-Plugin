function DataTable() {
    if (lang == 'tr') {
        $('#datatable_Details').DataTable({
            pageLength: 5,
            searching: false,
            bLengthChange: false,
            language: {"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"}
        });
        $('#datatable_Traffic').DataTable({
            bLengthChange: false,
            searching: false,
            paging: false,
            info: false,
            language: {"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"}
        });
        $('#datatable_Ban').DataTable({
            pageLength: 5,
            searching: false,
            bLengthChange: false,
            language: {"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json"}
        });
    } else if (lang == 'en') {
        $('#datatable_Details').DataTable({
            pageLength: 5,
            searching: false,
            bLengthChange: false,
        });
        $('#datatable_Traffic').DataTable({
            bLengthChange: false,
            searching: false,
            paging: false,
            info: false
        });
        $('#datatable_Ban').DataTable({
            pageLength: 5,
            searching: false,
            bLengthChange: false,
        });
    }
}
DataTable();

function ServerStop() {  
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Server_Stop'),
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function ServerStart() {  
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Server_Start'),
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function ServerRestart() {  
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Server_Restart'),
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function ServerEdit() {
    var edit__server_name = $('input[name="edit__server_name"]').val();
    var edit__server_weblist = $('select[name="edit__server_weblist"]').find('option:selected').val();
    var edit__server_host_message = $('input[name="edit__server_host_message"]').val();
    var edit__server_host_message_mode = $('select[name="edit__server_host_message_mode"]').find('option:selected').val();
    var edit__server_banner_link = $('input[name="edit__server_banner_link"]').val();
    var edit__server_banner_image_link = $('input[name="edit__server_banner_image_link"]').val();
    var edit__server_host_button_name = $('input[name="edit__server_host_button_name"]').val();
    var edit__server_host_button_link = $('input[name="edit__server_host_button_link"]').val();
    var edit__server_host_button_image_link = $('input[name="edit__server_host_button_image_link"]').val();
    var edit__server_welcome_message = $('textarea[name="edit__server_welcome_message"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Server_Edit'),
        data: {
            "server_name": edit__server_name,
            "server_weblist": edit__server_weblist,
            "server_host_message": edit__server_host_message,
            "server_host_message_mode": edit__server_host_message_mode,
            "server_banner_link": edit__server_banner_link,
            "server_banner_image_link": edit__server_banner_image_link,
            "server_host_button_name": edit__server_host_button_name,
            "server_host_button_link": edit__server_host_button_link,
            "server_host_button_image_link": edit__server_host_button_image_link,
            "server_welcome_message": edit__server_welcome_message
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function GiveAPermission() {  
    var authority__person = $('select[name="authority__person"]').val();
    var authority__authority = $('select[name="authority__authority"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Give_A_Permission'),
        data: {
            "person": authority__person,
            "authority": authority__authority
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function GetAPermission() {
    var authority__person = $('select[name="authority__person"]').val();
    var authority__authority = $('select[name="authority__authority"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Get_A_Permission'),
        data: {
            "person": authority__person,
            "authority": authority__authority
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function MessageSend() {
    var message__person = $('select[name="message__person"]').val();
    var message__message = $('textarea[name="message__message"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Message_Send'),
        data: {
            "person": message__person,
            "message": message__message
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function PokeSend() {
    var poke__person = $('select[name="poke__person"]').val();
    var poke__message = $('textarea[name="poke__message"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Poke_Send'),
        data: {
            "person": poke__person,
            "message": poke__message
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function Move() {
    var move__person = $('select[name="move__person"]').val();
    var move__channel = $('select[name="move__channel"]').val();
    var move__channel_password = $('select[name="move__channel_password"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Move'),
        data: {
            "person": move__person,
            "channel": move__channel,
            "channel_password,": move__channel_password
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function Kick() {
    var kick__person = $('select[name="kick__person"]').val();
    var kick__type = $('select[name="kick__type"]').val();
    var kick__message = $('textarea[name="kick__message"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Kick'),
        data: {
            "person": kick__person,
            "type": kick__type,
            "message": kick__message
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function Ban() {
    var ban__person = $('select[name="ban__person"]').val();
    var ban__time = $('input[name="ban__time"]').val();
    var ban__message = $('textarea[name="ban__message"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Ban'),
        data: {
            "person": ban__person,
            "time": ban__time,
            "message": ban__message
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function BanDelete(ban_delete__id) {
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Ban_Delete'),
        data: {
            "id": ban_delete__id
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}

function CreateChannel() {
    var create_channel__type = $('select[name="create_channel__type"]').val();
    var create_channel__name = $('input[name="create_channel__name"]').val();
    $.ajax({
        type: "POST",
        url: service_post(service_id, 'Create_Channel'),
        data: {
            "type": create_channel__type,
            "name": create_channel__name
        },
        beforeSend: function() {
            $.post(lang_api_sp('TeamSpeak', 'please_wait'), function(data) {
                Toastify({text: data, gravity: "top", position: 'right', close: false, duration: 500, backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)"}).showToast();
            });
        },
        success: function(reply){
            var obj = JSON.parse(reply);
            if (obj.status == true) {
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #00bcd4, #2196F3)"}).showToast();
                if (obj.ref == true) {
                    window.setTimeout(function(){
                        window.location.href = obj.url;
                    }, obj.time);
                }
            } else{
                Toastify({text: obj.error, gravity: "top", position: 'right', close: false, duration: 2500, backgroundColor: "linear-gradient(to right, #f54394, #f543ed)"}).showToast();
            }
        }
    });
}