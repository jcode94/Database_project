function doLogin() {
    console.log("Login Button Clicked")

    window.location.href = "./HTML/homePage.html";
    return

    urlBase = "http://157.245.93.191/backend/api";
    extension = ".php";

    this.email = document.getElementById("email").value;
    this.password = document.getElementById("password").value;

    //* Variables for the http request to login with the login api
    let jsonPayLoad = JSON.stringify({
        email: this.email,
        password: this.password,
    });

    let url = this.urlBase + "/login" + this.extension;
    let method = "POST";

    //* Opening the connection to the login api file with the login & password typed in
    let xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    try {
        let that = this;
        xhr.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let jsonObject = JSON.parse(xhr.responseText);

                if (that.userId < 1) {
                    document.getElementById("loginResult").innerHTML =
                        "Username or Password are incorrect";
                    return;
                }

                let Cookie = new cookie(that.email);
                Cookie.saveCookie();

                window.location.href = "./HTML/homePage.html";
            }
        };

        xhr.send(jsonPayLoad);
    } catch (err) {
        document.getElementById("loginResult").innerHTML = err.message;
    }
}
