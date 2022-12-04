

function getSurveys()
{
    console.log( "Session Storage", sessionStorage )

    let surveyList = document.getElementById('surveyList')

    let jsonSurveys = {
        0:{
            surveyName:"Zero",
            numberOfQuestions:10,
            completionStatus:"Pending"
        },
        1:{
            surveyName:"One",
            numberOfQuestions:25,
            completionStatus:"Completed"
        },
        2:{
            surveyName:"Two",
            numberOfQuestions:16,
            completionStatus:"In-Progress"
        },
        3:{
            surveyName:"Three",
            numberOfQuestions:30,
            completionStatus:"Pending"
        }
    }

    let surveyIds=[]
    let surveyNames=[]
    let surveyQuestionNumbers=[]
    let surveyStatuses=[]

    Object.keys(jsonSurveys).forEach(surveyId=>(
            surveyIds.push(surveyId),

            surveyNames.push(jsonSurveys[surveyId].surveyName),
            surveyQuestionNumbers.push(jsonSurveys[surveyId].numberOfQuestions),
            surveyStatuses.push(jsonSurveys[surveyId].completionStatus)

        ))

    console.log(
        surveyIds,
        surveyNames,
        surveyQuestionNumbers,
        surveyStatuses
    )

    for( let idx = 0; idx < surveyIds.length; idx++ )
    {
        surveyList.innerHTML = surveyList.innerHTML + 
        '<div class="col-auto box clickable bd" onclick="openSurvey()">\n' +
            '\t<div class="row"><p>' + surveyNames[idx] + '</p></div>\n' +
            '\t<div class="row"><p>Questions: ' + surveyQuestionNumbers[idx] + '</p></div>\n'+
            '\t<div class="row"><p>' + surveyStatuses[idx] + '</p></div>\n'+
        '</div>\n'
    }
    console.log(surveyList)
    
}