function doLogin() {
    let urlBase = "http://157.245.93.19/backend/api";
    let extension = ".php";

    let email = document.getElementById("email");
    let password = document.getElementById("password");

    if (fieldIsEmpty(email)) {
        setFieldErrorState(email);
        return;
    } else if (fieldIsEmpty(password)) {
        setFieldErrorState(password);
        return;
    } else {
        email = email.value;
        password = password.value;
    }

    //* Variables for the http request to login with the login api
    let jsonPayLoad = JSON.stringify({
        email: email,
        password: password,
    });

    console.log("JSON Package", jsonPayLoad);

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
                console.log("Received", xhr.responseText);

                let jsonObject = JSON.parse(xhr.responseText);

                console.log("JSON Received", jsonObject);

                if (jsonObject.valid == "valid") {
                    sessionStorage["userEmail"] = email;
                    console.log("Session Storage", sessionStorage);

                    document.getElementById("loginResult").innerText = "Successful Login";

                    window.location.href = "./frontend/HTML/homePage.html";
                } else {
                    document.getElementById("loginResult").innerText = jsonObject.valid;
                }
            }
        };

        xhr.send(jsonPayLoad);
    } catch (err) {
        document.getElementById("loginResult").innerHTML = err.message;
    }
}

function fieldIsEmpty(field) {
    return field.value.trim() == "";
}

function setFieldErrorState(field) {
    field.value = "";

    field.style.borderColor = "red";

    field.addEventListener("focus", () => {
        field.style.borderColor = "black";
    });
}
