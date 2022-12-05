

function doRegister()
    {        
        console.log("Register Button Clicked")        

        let urlBase = "http://157.245.93.19/backend/api";
        let extension = '.php';

        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;
        
        
        //* Variables for the http request to login with the login api
        let jsonPayLoad = JSON.stringify(
            {
                email:email,
                password:password
            });

        // logging the payload being sent 
        console.log("Stringify sent: " + jsonPayLoad);

        let url = urlBase + "/Register" + extension;
        let method = "POST";

        //* Opening the connection to the login api file with the login & password typed in
        let xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

        try {
            xhr.onreadystatechange = function () {
    
                // If server pinged and a response is sent back
                if (this.readyState == 4 && this.status == 200) {
    
                    console.log('Received', xhr.responseText)
    
                    let jsonObject = JSON.parse(xhr.responseText);
    
                    console.log('JSON Received', jsonObject)
    
                    if ( jsonObject.valid == "valid")
                    {
                        sessionStorage['userEmail'] = email
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
    
            xhr.send(jsonPayLoad)
    
        } catch (err) {
            document.getElementById("loginResult").innerHTML = err.message;
        }
    }