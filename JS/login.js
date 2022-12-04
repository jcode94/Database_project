function doLogin() {
    // console.log("Login Button Clicked")

    // window.location.href = "./HTML/homePage.html";
    // return

    let urlBase = "http://157.245.93.19/backend/api";
    let extension = ".php";

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    console.log(email, password)

    //* Variables for the http request to login with the login api
    let jsonPayLoad = JSON.stringify({
        email: email,
        pass: password,
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
            if (this.readyState == 4 && this.status == 200) {

                let jsonObject = JSON.parse(xhr.responseText);

                console.log('JSON Received', jsonObject)

                sessionStorage['userEmail'] = 'email'
                console.log( sessionStorage['email'] )

                window.location.href = "./HTML/homePage.html";
            }
        };

        xhr.send(jsonPayLoad);
    } catch (err) {
        document.getElementById("loginResult").innerHTML = err.message;
    }
}
