function chat_send_message () {
    msg = $("#message").val () ;
    if (msg == '') {
        return ;
    }

    data = {
        "message": msg,
        "type": "message",
        "sender": to
    }

    $.ajax({
        type: "POST",
        url: "/anneli/api/db.php?table=chat&action=insert|notify",
        data: data,
        success: function (data) {
            console.log (data)
            if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Message not sent.',
                    text: data.error
                  })
    
            }
            console.log ("Message sent")
            chat_message (true, msg)
            ui ("message").scrollIntoView (true)
            ui ("message").value = ""
        },
        error: function (data) {
            console.log (data)
            Swal.fire({
                icon: 'error',
                title: 'Message not sent.',
                text: data.responseText
              })
              
        },
        dataType: "json"
      });
    // $.ajax({
    //     type: "POST",
    //     url: "/anneli/api/db.php?action=notify",
    //     data: data,
    //     success: function (data) {
    //         console.log (data)
    //         if (data.error) {
    //             Swal.fire({
    //                 icon: 'error',
    //                 title: 'Message not sent.',
    //                 text: data.error
    //               })
    
    //         }
    //     },
    //     error: function (data) {
    //         console.log (data)
    //     },
    //     dataType: "json"
    //   });
}

function chat_message (own, message) {
    c = uic ("a")
    if (own) {
        for (i of [
            'text-end', 'btn-lg', 'list-group-item', 'list-group-item-action'
        ])
            c.classList.add (i)
        c.innerHTML = message + '&nbsp;<sup class="badge text-white bg-primary m-1" style="opacity:80%;font-size:60%">' + JSClock () + '</sup>'
    } else {
        for (i of [
            'active', 'btn-lg', 'card', 'list-group-item', 'list-group-item-action'
        ])
            c.classList.add (i)
        c.innerHTML = message + '&nbsp;<sup class="badge text-muted bg-secondary m-1" style="opacity:80%;font-size:60%">' + JSClock () + '</sup>'
    }

    ui ("mcontainer").appendChild (c)
}

function chat_register_token () {
    console.log ("registering token")
    const messaging = firebase.messaging();
    messaging.getToken({vapidKey: "BJg5Xgr89Wyhd6JEXgKdemj4AcU-w7edy2tkaH0W32WBXvbYOfJ7Lu5ySjSX-LiTBZevfHm2ieWSUVLOjYu-kX4"})
    .then((currentToken) => {
        if (currentToken) {
          // Send the token to your server and update the UI if necessary
          // ...
          data = {
              update: {
                  token: currentToken
              },
              where: {
                  tokenId: "notification"
              }
          }

          db ("tokens", "updatei", data,
            function () {console.log (data)},
            function () {console.log (data)}
          )
        } else {
          // Show permission request UI
          console.log('No registration token available. Request permission to generate one.');
          // ...
        }
      }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
        // ...
      });

}

function chat_init () {
    const messaging = firebase.messaging ();
    messaging.onMessage((payload) => {
        console.log('Message received. ', payload);
        if(data.sender == to && data.type == 'message')
            chat_message (false, payload.notification.body)
        ui ("message").scrollIntoView ()
        // ...
      });
          
}

function JSClock() {
    var time = new Date();
    var hour = time.getHours();
    var minute = time.getMinutes();
    var second = time.getSeconds();
    var temp = '' + ((hour > 12) ? hour - 12 : hour);
    if (hour == 0)
      temp = '12';
    temp += ((minute < 10) ? ':0' : ':') + minute;
    // temp += ((second < 10) ? ':0' : ':') + second;
    temp += (hour >= 12) ? ' pm' : ' am';
    return temp;
}

function chat_attach_file (input) {
    console.log (input)
    ui ("image-attach").classList.remove ("d-none")
    ui ("image-remove").classList.remove ("d-none")
    if (FileReader && input.files && input.files.length) {
        var fr = new FileReader();
        fr.onload = function () {
            document.getElementById("image-attach").src = fr.result;
        }
        
        fr.readAsDataURL(input.files[0]);
    }
}

function chat_remove_attachment () {
    ui ("image-attach").src = ""
    ui ("image-attach").classList.add ("d-none")
    ui ("image-remove").classList.add ("d-none")
    ui ("image-input").value = ""
}