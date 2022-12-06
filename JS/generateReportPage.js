

function createReportHTML()
{
    let jsonReport = {
        title:"Title",
        description:"Desc",
        startDate:"2022/12/01",
        endDate:"2022/12/01",
        number_of_questions:2,
        questions:[{type:1,statement:"statement",number:1}],
        responses:[{type:1,statement:"statement",number:1}],
    }
    console.log("Create Report HTML")

    let surveyMeta = document.getElementById('surveyMeta')

    surveyMeta.innerHTML = '' +
        '<div class="col">' +

            '<div class="row">' +
                '<h1 id="surveyName">' + jsonReport.title + '</h1>' +
            '</div>' +

            '<div class="row">' +
                '<h4 id="surveyDesc">' + jsonReport.description + '</h4>' +
            '</div>' +

            '<div class="row center">' +
                '<div class="col-auto"><p id="surveyDate">' + jsonReport.startDate + ' - ' + jsonReport.endDate + '&nbsp;|</p></div>' +
                '<div class="col-auto"><p id="surveyDate"></p></div>' +
                '<div class="col-auto"><p id="numQuestions">&nbsp;' + jsonReport.number_of_questions + ' Questions</p></div>'   +                                
            '</div>' +

        '</div>'
    
    let surveyQuestionsArea = document.getElementById('surveyQuestionsArea')

    for( let i = 0; i < jsonReport.number_of_questions; i++)
    {
        surveyQuestionsArea.innerHTML = surveyQuestionsArea.innerHTML +
        '<div class="row space">' +

            '<div id="" class="col space bd">' +
                '<div id="" class="row box questionInner bd">' +

                    '<div class="col">' +

                        '<div class="row">' +
                            '<div class="col-auto box"><p>1)</p></div'> +
                        '</div>' +

                        '<div class="row">' +
                            '<div class="col-auto box"><p>What is your favorite food</p></div>' +
                        '</div>' +

                        '<div class="row">' +
                            '<div class="col-auto box"><p>Free Response</p></div>' +
                        '</div>' +

                    '</div>' +

                '</div>' +
            '</div>' +

            '<div id="" class="col overflow space bd">' +

                '<div class="row answersList">' +

                    '<div class="col">' +

                        '<div id="answer1" class="row box bd">' +
                            '<div class="col-auto box"><p>Strawberries</p></div>' +
                        '</div>' +

                        '<div id="answer2" class="row box bd">' +
                            '<div class="col-auto box"><p>Strawberries</p></div>' +
                        '</div>' +

                        '<div id="answer3" class="row box bd">' +
                            '<div class="col-auto box"><p>Strawberries</p></div>' +
                        '</div>' +

                        '<div id="answer4" class="row box bd">' +
                            '<div class="col-auto box"><p>Strawberries</p></div>' +
                        '</div>' +

                    '</div>' +

                '</div>' +

            '</div>' +

        '</div>'

    }
}