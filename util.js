var ui = document.getElementById.bind(document);
var uic = document.createElement.bind(document);

function transliterate (text, output, input = '') {
    console.log ("api.php?text=" + text + 
    "&lang=" + output + '&source=' + input)
    return $.ajax({
        url: "api.php?text=" + text + 
             "&lang=" + output + '&source=' + input, 
        async: false
    }).responseText.trim () ;
}

function transliterate_edit (button, modal, word, base, langa, source) {
    // console.log (button)
    div_word = uic ("div")
    div_word.classList.add (lang)
    div_word.classList.add ("d-flex")
    div_word.classList.add ("flex-row")
    for (w of word) {
        sel_div = uic ("div")
        sel_div.classList.add ("flex-column")
        sel_div.classList.add ("d-flex")
        sel_title = uic ("label")
        sel_title.classList.add ("text-primary")
        sel_title.classList.add (lang)
        sel_title.classList.add ("text-center")
        sel_div.appendChild (sel_title)
        sel = uic ("select")
        optgroup = uic ("optgroup")
        sel_div.appendChild (sel)
        // sel.classList.add ("btn")
        sel.classList.add (lang)
        sel.classList.add ("m-1")
        // sel.appendChild (optgroup)
        optgroup.classList.add (lang)
        // sel.classList.add ("p-1")
        // sel.classList.add ("btn-sm")
        // sel.style.width = '100'
        // sel.style.margin = '10'
        // for (o of [w, '']) {
        items = []
        for (i = charset [lang][0] ; i < charset [lang][1] ; i ++) {
            // console.log  (i-0x11800 + 1, String.fromCodePoint (  i))
            option = uic ("option")
            option.classList.add (lang)
            option.value = String.fromCodePoint (i)
            option.innerText = String.fromCodePoint (i)
            optgroup.appendChild (option)
            items.push ({value: String.fromCodePoint (i), label :String.fromCodePoint (i)})
        }

        choices = new Choices (sel, {
          choices: items
        })
        choices.setChoiceByValue (w)
        // choices.classList.add (lang)
        sel_title.innerText = w
        div_word.appendChild (sel_div)
    }

    body = $("#" + modal).find (".modal-body")
    // $("#" + modal).find (".modal-title").text (base + '   🠖   ' + word)
    $("#" + modal).find (".modal-title").text (base + '  ->   ' + word)
    body.html ("")
    body.append (div_word)
    $("#" + modal).data ("word", word)
    $("#" + modal).data ("base", base)
    $("#" + modal).modal ("show")
}

function spinner_on (text = null) {
    if (text)
        $("#spinner").find (".modal-title").text (text)
    $("#spinner").modal ("show")
    $("#spinner").find (".progress").hide ()
    $("#spinner").find (".progress-bar").attr ("aria-valuenow", 0)
    $("#spinner").find (".progress-bar").css ("width", '0%')
}

function spinner_set_progress (progress) {
    // console.log (progress)
    $("#spinner").find (".progress").show ()
    $("#spinner").find (".progress-bar").attr ("aria-valuenow", progress)
    $("#spinner").find (".progress-bar").css ("width", progress + '%')
}

function spinner_off () {
    $("#spinner").find (".modal-title").text ("Please Wait")
    $("#spinner").modal ("hide")
}

function transliterate_modify (modal, reset = false) {
    word = $("#" + modal).data ("word")    
    base = $("#" + modal).data ("base")    

    new_word = ''
    for (i of $("#" + modal).find ("select")) {
        new_word += i.value
    }

    if (reset)
        new_word = word

    w = document.getElementsByName (base)
    for (i of w) {
        i.innerText = new_word
        if (!reset)
            i.classList.add ("bg-warning")
        else
            i.classList.remove ("bg-warning")
        // i.classList.add ("text-white")
    }

}

function logout () {
    firebase.auth().signOut().then(function() {
      // Sign-out successful.
      // alert ("You have been logged out.")
      // if (module != 'daybook')
      setCookie ("token", null, 10, 'auth')
      Swal.fire('Goodbye!', 'You have been logged out.', 'success').then (function () {
        location.href = "/"

      })

      // else
      //     location.reload ()
    }).catch(function(error) {
      // An error happened.
      alert ("Unable to log out")
      console.error (error)
    });
}

var fireuser = null ;
var token = null ;
function init () {
  console.log ("init", location.href)
//   firebase_init ()
  $('#colors').on('show.bs.modal', function (e) {
    s = document.getElementById ("color-schemes")
    s.innerHTML = ''
    for (c in themes) {
      o = uic ("option")
      o.value = c
      o.innerText = themes [c]
      s.appendChild (o)
    }

    s.value = theme ;

    s = document.getElementById ("fonts")
    s.innerHTML = '<option value="">Default</option>'
    for (c of fonts) {
      o = uic ("option")
      o.value = c
      o.innerText = c
      s.appendChild (o)
    }

    s.value = font ;
    s = document.getElementById ("skin")
    s.value = skin ;

  });

  firebase.auth().onAuthStateChanged(async function(user) {
    if (user) {
      fireuser = user
      console.log (user)
      // console.log (analytics)
      firebase.analytics().logEvent ("[load] " + user.email + ": " + location.href)
      // User is signed in.
      try {
        document.getElementById ("menu-login").classList.add ('d-none')
        document.getElementById ("menu-account").classList.remove ('d-none')
        // document.getElementById ("menu-logout").classList.remove ('d-none')
        document.getElementById ("email").innerText = fireuser.email //.split ("@")[0]

        for (i of document.getElementsByClassName("email")) {
          i.innerText = fireuser.email
        }
  

        for (i of document.getElementsByClassName("profile")) {
          i.src = fireuser.photoURL
        }
  

        // if (append_uid) {
        //   var searchParams = new URLSearchParams(location.href);
        //   if (!searchParams.has ("uid")) {
        //       searchParams.append ("uid", fireuser.uid)
        //       location.href = location.href + '?' + searchParams.toString ();
        //   }
            
        // }
      } catch (err) {
          console.log (err)
      }

      await firebase.auth().currentUser.getIdTokenResult()
      .then((idTokenResult) => {
        console.log (idTokenResult)
        token = idTokenResult
        // document.getElementById ("token").value = token.token;
        setCookie ("token", token.token, 10, 'auth')
        setCookie ("email", fireuser.email, 10, 'auth')
        // document.cookie ['token'] = token.token
        // document.cookie ['uid'] = user.uid
      })

      const analytics = firebase.analytics();
      firebase.analytics().logEvent('login: ' + fireuser.email + ' @ ' + location.pathname);
            
    } else {
      // No user is signed in.
      console.warn ("No user signed in")
      setCookie ("token", null, 10, 'auth')
      var ui = new firebaseui.auth.AuthUI(firebase.auth());
      ui.start('#firebaseui-auth-container', uiConfig);
      document.getElementById ("menu-login").classList.remove ('d-none')
      for (i of document.getElementsByClassName("btn-login")) {
        i.classList.remove ("d-none")
      }


    }

    document.getElementById ("login-spinner").remove ()
  });
  
}

function setCookie(cname, cvalue, exdays, path="/") {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=" + path;
}

function post_modified_words () {
  m = document.getElementsByClassName ("bg-warning")
  modified = {}
  if (m.length == 0) {
    Swal.fire(
      'No words selected',
      'Modify a word or words to save.',
      'info'
    )
    return
  }
  spinner_on ()
  for (i of m) {
    word = {}
    word.word = i.parentElement.dataset.word
    word.trans = i.parentElement.dataset.trans
    word.trans_modified = i.innerText
    word.lang = lang
    word.source = source
    word.article = article
    modified [word.word] = word
  }
  
  // console.log (modified);
  
  var result = $.ajax({
    type: 'POST',
    url: 'post.php?mode=add-word&quiet=1',
    // data: '{"name":"jonas"}', // or JSON.stringify ({name: 'jonas'}),
    data: JSON.stringify (modified),
    error: function(response) { 
      if (response == null)
        return
      response = response.responseText.trim ()
        try {
        response = JSON.parse (response)
      }
      catch (er) {
        console.warn (response)
        console.error(er);
        response = {response: er}
      }
    
        // response = JSON.parse (response)
      console.log (response);
      
      spinner_off ()
      if (response.error == null)
        Swal.fire('Saved!', 'Your changes were saved successfully', 'success')
      else
        Swal.fire({
          icon: 'error',
          title: 'Failed to save changes',
          text: response ['error'],
          footer: response ['sql']
        })
      },
    contentType: "application/json",
    dataType: 'jsonp',
    async: true
  })

  response = result.responseText
  console.log (response);
  if (response == null) return
  response = JSON.parse (response)
  // if (response == null) response = {error: "Error"}
  spinner_off ()
  if (response.response == "ok")
    Swal.fire('Saved!', 'Your changes were saved successfully', 'success')
  else
    Swal.fire({
      icon: 'error',
      title: 'Failed to save changes',
      text: response ['error'],
      footer: response ['sql']
    })

  return false
}

function preview_theme () {
  theme = document.getElementById ("color-schemes").value
  font = document.getElementById ("fonts").value
  skin = document.getElementById ("skin").value
  el = uic ("link")
  el.setAttribute ("href", '/anneli/themer2.php?theme=' + theme + '&font=' + font + '&skin=' + skin)
  el.setAttribute ("type", "text/css")
  el.setAttribute ("rel", "stylesheet")
  document.body.appendChild (el)
}

function set_theme () {
  theme = document.getElementById ("color-schemes").value
  font = document.getElementById ("fonts").value
  skin = document.getElementById ("skin").value

  setCookie ("skin", skin, 10, 'settings')
  setCookie ("font", font, 10, 'settings')
  setCookie ("theme", theme, 10, 'settings')
  Swal.fire('Saved', 'Your settings have been saved.', 'success')
}

function view_convert () {
  l = document.getElementById ("lang").value
  s = document.getElementById ("source").value

  url = "/view.php?file=" + article + "&lang=" + l + "&source=" + s
  location.href = url
}

function delete_word (button) {
  id = button.dataset.id
  Swal.fire({
    title: 'Are you sure you want to delete this word?',
    text: "Words deleted cannot be recovered. Be careful.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e51c23',
    // cancelButtonColor: '#d33',
    confirmButtonText: 'Delete'
  }).then((result) => {
    if (result.isConfirmed) {
      cmd = "/post.php?mode=delete-word&id=" + id
      location.href = cmd
    }
  })
  
}

function delete_article (button) {
  id = button.dataset.id
  Swal.fire({
    title: 'Are you sure you want to delete this article?',
    text: "Deleted articles deleted cannot be recovered. Be careful.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e51c23',
    // cancelButtonColor: '#d33',
    confirmButtonText: 'Delete'
  }).then((result) => {
    if (result.isConfirmed) {
      cmd = "/post.php?mode=delete-article&id=" + id
      location.href = cmd
    }
  })
  
}

function byteToHexString(uint8arr) {
  if (!uint8arr) {
    return '';
  }
  
  var hexStr = '';
  for (var i = 0; i < uint8arr.length; i++) {
    var hex = (uint8arr[i] & 0xff).toString(16);
    hex = (hex.length === 1) ? '0' + hex : hex;
    hexStr += hex;
  }
  
  return hexStr.toUpperCase();
}

function hexStringToByte(str) {
  if (!str) {
    return new Uint8Array();
  }
  
  var a = [];
  for (var i = 0, len = str.length; i < len; i+=2) {
    a.push(parseInt(str.substr(i,2),16));
  }
  
  return new Uint8Array(a);
}

function transliterate_site () {
    source = ui ("source").value
    target = ui ("target").value
    const urlParams = new URLSearchParams(window.location.search);
    const q = urlParams.get('q');

    cmd = "/api.php?q=" + q + '&source=' + source + '&lang=' + target
    location.href = cmd
}

function transliterate_site_from_main () {
  url = ui ("site-url").value
  lang = ui ("site-lang").value

  cmd = "/api.php?q=" + url + '&lang=' + lang
  location.href = cmd
}

function db (table, action, data, success_func, error_func, mode = "db") {
  // if (mode == "json") data ["module"] = location.pathname
  $.ajax({
    type: "POST",
    url: "/anneli/api/db.php?table=" + table +"&action=" + action + "&mode=" + mode,
    data: data,
    processData:false,
    contentType:false,
    // contentType:'multipart/form-data',
    success: function (data) {
        console.log (data)
        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Message not sent.',
                text: data.error
              })

        }
        if (success_func)
          success_func (data)
    },
    error: function (data) {
        // console.log (data)
        Swal.fire({
            icon: 'error',
            title: 'Message not sent.',
            text: data.responseText
          })
        
        if (error_func)
          error_func (data)
    },

    dataType: "json"
  });
}

function form_to_json (id, data_prefix = null) {
  form = ui (id) ;
  data = new FormData ();
  data_json = {}
  

  for (i of form.querySelectorAll ("input")) {
    if (i.type == "file")
      for (x of i.files)
        data.append (i.id, x)
    else {
      if (data_prefix == null)
        data.append (i.id, i.value)
      else
        data_json [i.id] = i.value
    }
  }

  if (data_prefix)
    data.append (data_prefix, data_json)
  
  // console.log (data)
  return data ;
}

function ajax_post (table, action, data, success_func, error_func, mode = "db") {
  let xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      alert(this.responseText);
      if (success_func) success_func () ;
    } else {
      console.log(this.status);
      if (error_func) error_func () ;
    }
  };

  ajaxurl = "/anneli/api/db.php?table=" + table +"&action=" + action + "&mode=" + mode ;

  xhttp.open('POST', ajaxurl, true);
  //xhttp.setRequestHeader("Content-type","multipart/form-data");
  xhttp.send(data);
}

function basename (path) {
  return path.split(/[\\/]/).pop();  
}

function uin (name) {
  return document.getElementsByName (name)[0]
}