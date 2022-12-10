

function doDeleteSurveyAPI()
{
    let surveyId = sessionStorage['surveyId']
    let email = sessionStorage['userEmail']

    if( email == undefined || surveyId == undefined ){ console.log("No Survey ID or Email"); return; }

    let urlBase = "http://157.245.93.19/backend/api";
    let extension = ".php";

    let jsonPayLoad = JSON.stringify({
        survey_id:surveyId,
        email:email
    });

    console.log('JSON Package', jsonPayLoad)

    let url = urlBase + "/DeleteSurveyParticipant" + extension;
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
                    sessionStorage.removeItem('surveyId')
                    if(sessionStorage['surveyID'] == undefined) { window.location.href = "./homePage.html"; }
                }
            }
        };

        xhr.send(jsonPayLoad)

    } catch (err) {
        document.getElementById("loginResult").innerHTML = err.message;
    }
}