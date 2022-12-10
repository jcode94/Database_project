

function openSurvey(surveyId)
{        
    console.log("Survey Button Clicked", surveyId)

    sessionStorage['surveyId'] = surveyId

    window.location.href = "./takeSurvey.html";
}