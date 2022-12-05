function doLogin() {

    let flag = false

    let urlBase = "http://157.245.93.19/backend/api";
    let extension = ".php";

    let email = document.getElementById("email");
    let password = document.getElementById("password");

    if( email.value.trim() == '' )
    {
        flag=true

        email.value = ''

        email.style.borderColor = 'red'
        
        email.addEventListener("focus", ()=>{
            email.style.borderColor = 'black'
        })
    }
    else
    {
        email = email.value
    }

    if( password.value.trim() == '' )
    {
        flag=true

        password.value = ''

        password.style.borderColor = 'red'
        
        password.addEventListener("focus", ()=>{
            password.style.borderColor = 'black'
        })
    }
    else
    {
        password = password.value
    }

    // Missing either email or password -> return
    if(flag) {return}

    //* Variables for the http request to login with the login api
    let jsonPayLoad = JSON.stringify({
        email: email,
        password: password,
    });

    console.log('JSON Package', jsonPayLoad)

    let url = urlBase + "/Login" + extension;
    let method = "POST";

    //* Opening the connection to the login api file with the login & password typed in
    let xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        xhr.onreadystatechange = function () {

            // If server pinged and a response is sent back
            if (this.readyState == 4 && this.status == 200) {

                let jsonObject = JSON.parse(xhr.responseText);

                console.log('JSON Received', jsonObject)

                if ( jsonObject.valid == "valid")
                {
                    sessionStorage['userEmail'] = 'email'
                    console.log( "Session Storage", sessionStorage )

                    document.getElementById('loginResult').innerText = "Successful Login"

                    window.location.href = "./HTML/homePage.html";
                }
                else
                {
                    document.getElementById('loginResult').innerText = jsonObject.valid
                }
            }
        };

        xhr.send(jsonPayLoad);
    } catch (err) {
        document.getElementById("loginResult").innerHTML = err.message;
    }
}
