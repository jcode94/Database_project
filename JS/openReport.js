

function openReport(surveyId)
{        
    console.log("Survey Button Clicked", surveyId)

    sessionStorage['surveyId'] = surveyId

    window.location.href = "./surveyReport.html";
}