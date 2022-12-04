

function submitSurvey()
{

    let jsonSurvey = {}

    let surveyId = 1
    let answers = []
    let email = 'email'

    let surveyQuestionsArea = document.getElementById('surveyQuestionsArea')

    let questions = surveyQuestionsArea.childNodes

    console.log(questions.length)

    for( let i = 1; i <= questions.length; i++ )
    {
        let inputBox = $('input[id^="question' + i +'"]')[0]


        if(inputBox.id.split('question' + i)[1].startsWith('Free'))
        {
            // Free Response
            answers.push(inputBox.value)
        }
        else
        {
            answers.push(inputBox.value)

        }
    }

    jsonSurvey['surveyId'] = surveyId
    jsonSurvey['email'] = email
    jsonSurvey['answers'] = answers


    console.log(jsonSurvey)
    return jsonSurvey
}