function chat_send_message () {
    ui ("send-spinner").classList.remove ("d-none")
    msg = $("#message").val () ;
    image = ui ("image-input").files [0] ;

    data = {}
    if (image != null) {
        data = new FormData () ;
        data .append ("files", image)
        data .append ("message", msg)

        data.append ("type", "image")
        data.append ("sender", to)
        script = "setperm $files " + to + " read" // this is fantastic
        data.append ("__script__", script);
    } else {
        if (msg == '')
            return ;
        data = {
            "message": msg,
            "type": "message",
            "sender": to
        }
    }

    console.log (data)
    db ("chat", "insert|notify", data, function (data) {
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
        chat_remove_attachment () ;
        ui ("send-spinner").classList.add ("d-none")
    },function (data) {
        console.log (data)
        Swal.fire({
            icon: 'error',
            title: 'Message not sent.',
            text: data.responseText
          })
          ui ("send-spinner").classList.add ("d-none")          
    });

    /*
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
      */
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

function chat_message (own, message, file = null, type = null) {
    console.log ("Appending message", true)
    c = uic ("a")
    if (own) {
        for (i of [
            'text-end', 'btn-lg', 'list-group-item', 'list-group-item-action'
        ]) {
            c.classList.add (i)
        }
         
        c.innerHTML = message + '&nbsp;<sup class="badge text-white bg-primary m-1" style="opacity:80%;font-size:60%">' + JSClock () + '</sup>'

        var fr = new FileReader();
        image = ui ("image-input").files [0]
        img_container = uic ("div");
        if (image != null) {
            img_element = uic ("img")
            img_element.setAttribute ("height", "256px");
            fr.onload = function () {
                img_element.src = fr.result;
            }
    
            fr.readAsDataURL(image);
            img_container.appendChild (img_element)
            c.prepend (img_container)
        }

    } else {
        for (i of [
            'active', 'btn-lg', 'card', 'list-group-item', 'list-group-item-action'
        ])
            c.classList.add (i)
        c.innerHTML = message + '&nbsp;<sup class="badge text-muted bg-secondary m-1" style="opacity:80%;font-size:60%">' + JSClock () + '</sup>'

        if (file != null && type == "image") {
            img_element = uic ("img")
            img_element.setAttribute ("height", "256px");
            img_element.src = "/anneli/api/file?file="+ file + "&user=" + to

            c.prepend (img_element)
        }
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
        /*  This is apparently reversed here.
            Sender means whom it is intended for
        */

        if(payload.data.sender == uid && payload.data.uid == to && payload.data.type == 'message') {
            chat_message (false, payload.notification.body)
        }
        else if(payload.data.sender == uid && payload.data.uid == to && payload.data.type == 'image') {
            if (payload.notification.body == null)
                payload.notification.body = ""
            link = null ;
            files = JSON.parse (payload.data.files)
            for (i in files)
                link = files [i]
            chat_message (false, payload.notification.body, link, "image")
        }

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