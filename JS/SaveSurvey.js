function download(filename) {


    let tmp = ['1','2','3'];

    let text2 = document.getElementById("answer1").textContent;
    let text1 = document.getElementById("FR").textContent;
    let mean = document.getElementById("Mean").textContent;
    let variance = document.getElementById("Variance").textContent;
    
    

    const text = [text1+text2+text3+text4+mean+variance];

    var pom = document.createElement('a');
    pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
    pom.setAttribute('download', filename);

    if (document.createEvent) {
        var event = document.createEvent('MouseEvents');
        event.initEvent('click', true, true);
        pom.dispatchEvent(event);
    }
    else {
        pom.click();
    }
}
//not working atm ignore
function getResonseAPICall() {

    let flag = false

    let urlBase = "http://157.245.93.19/backend/api";
    let extension = ".php";

    let email = sessionStorage['userEmail']
    if( email == undefined )
    {
        console.log('No Email Found');

        window.location.href = "../index.html";
        flag = true
    }

    // Missing either email or password -> return
    if(flag) {return}

    //* Variables for the http request to login with the login api
    let jsonPayLoad = JSON.stringify({
        email: email,
    });

    console.log('JSON Package', jsonPayLoad)

    let url = urlBase + "/GetReport" + extension;
    let method = "POST";

    //* Opening the connection to the getAllSurveys api file with the email typed in
    let xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    

    try
    {
        xhr.onreadystatechange = function () {

            // If server pinged and a response is sent back
            if (this.readyState == 4 && this.status == 200) {

                console.log("Response Text : ", xhr.responseText)

                let jsonObject = JSON.parse(xhr.responseText);

                console.log('JSON Received', jsonObject)

                let authored = []
                let participant = []

                try
                {
                    getSurveys( jsonObject.authored, "authored", 'getReport' )
                    getSurveys( jsonObject.participant, "participant", 'getSurvey' )
                }
                catch
                {
                    let ret = [
                    {
                        surveyName : "Failed to fetch surveys",
                        surveyQuestionNumbers : "",
                        surveyStatus : "Error"
                    }]

                    getSurveys( ret, '', '' )
                }
            }
        };

        xhr.onerror = () =>
        {
            console.log('XHR On Error')
            error = true
            let ret = [
            {
                surveyName : "Failed to fetch surveys",
                surveyQuestionNumbers : "",
                surveyStatus : "Error"
            }]

            getSurveys( ret, ret )
        }

        xhr.send(jsonPayLoad)
    }
    catch (err)
    {
        console.log("Unknown Error", err)
        let ret = [
        {
            surveyName : "Failed to fetch surveys",
            surveyQuestionNumbers : "",
            surveyStatus : "Error"
        }]

        getSurveys( ret, ret )
    }
}
