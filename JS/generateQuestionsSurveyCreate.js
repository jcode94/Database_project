

function generateQuestionsSurveyCreate()
{
    console.log("populateQuestionsAreaInSurveyCreate")

    let numOfQuestions = document.getElementById('totalNumberOfQuestions').value

    if (numOfQuestions <= 0 ) {return}

    let questionCreationList = document.getElementById("questionCreationList")

    let innerHTMLString = '<div class="col">'

    for( let idx = 0; idx < numOfQuestions; idx++ )
    {
        innerHTMLString = innerHTMLString +
            '<!-- Single Question Row -->' +
            '<div id="question' + (idx+1) + '" class="row box bd">' +
                '<div class="col">' +
                    '<!-- Choose The Type Of Question -->' +
                    '<div class="row left">' +
                        '<p>Question ' + (idx+1) + ')</p>' +
                    '</div>' +
                    '<div class="row left">' +
                        '<div class="col-auto box">' +
                            '<p>Type Of Question:</p>' +
                        '</div>' +
                        '<div class="col-auto box">' +
                            '<input id="question' + (idx+1) + 'Rating" name="type" type="radio">' +
                            '<label>Rating</label>' +
                        '</div>' +
                        '<div class="col-auto box">' +
                            '<input id="question' + (idx+1) + 'Free" name="type" type="radio">' +
                            '<label>Free Response</label>' +
                        '</div>' +
                    '</div>' +
                    '<!-- Create The Prompt -->' +
                    '<div class="row left">' +
                        '<div class="col-auto box"><p>Question Prompt:</p></div>' +
                        '<div class="col box bd">' +
                            '<input id="question' + (idx+1) + 'Prompt" class="fill" type="text" placeholder="Question Prompt">' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>'
    }

    innerHTMLString = innerHTMLString + '</div>'

    document.getElementById('totalNumberOfQuestions').value = ''

    questionCreationList.innerHTML = innerHTMLString

}