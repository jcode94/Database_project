

function getSurveys(jsonSurveys, type)
{
    console.log( "Session Storage", sessionStorage )

    // let jsonSurveys = {
    //     ...authored,
    //     ...participant
    // }

    console.log(jsonSurveys)

    let surveyList = document.getElementById('surveyList')

    let surveyIds=[]
    let surveyTitles=[]
    let surveyQuestionNumbers=[]
    let surveyStatuses=[]

    Object.keys(jsonSurveys).forEach( (index) => {
        
            surveyIds.push(jsonSurveys[index].survey_id)

            surveyTitles.push(jsonSurveys[index].title)
            
            surveyQuestionNumbers.push(jsonSurveys[index].number_of_questions)

            if( type == "authored" ) { surveyStatuses.push("Authored") }
            else { surveyStatuses.push(jsonSurveys[index].status) }
    })

    console.log(
        "Post Read",
        surveyIds,
        surveyTitles,
        surveyQuestionNumbers,
        surveyStatuses
    )

    for( let idx = 0; idx < surveyIds.length; idx++ )
    {
        surveyList.innerHTML = surveyList.innerHTML + 
        '<div class="col-auto box clickable bd" onclick="openSurvey(' + surveyIds[idx] + ')">\n' +
            '\t<div class="row"><p>' + surveyTitles[idx] + '</p></div>\n' +
            '\t<div class="row"><p>Questions: ' + surveyQuestionNumbers[idx] + '</p></div>\n'+
            '\t<div class="row"><p>' + surveyStatuses[idx] + '</p></div>\n'+
        '</div>\n'
    }    
}

function getSurveysAPICall() {

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

    let url = urlBase + "/GetAllSurveys" + extension;
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
                    getSurveys( jsonObject.authored, "authored" )
                    // getSurveys( jsonObject.participant )
                }
                catch
                {
                    let ret = [
                    {
                        surveyName : "Failed to fetch surveys",
                        surveyQuestionNumbers : "",
                        surveyStatus : "Error"
                    }]

                    getSurveys( ret, ret )
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
