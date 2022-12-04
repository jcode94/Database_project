

function generateSurvey()
{
    console.log("generateSurvey")

    let survey =
    {
        title:"Food Survey",
        desc:"Just A Few Questions On Food",
        startD:"12/2022/1",
        endD:"12/2022/5",
        numQuestions:3
    }

    let questions = [
        {
            number:1,
            type:2,
            statement:"Name Your Favorite Food"
        },
        {
            number:2,
            type:1,
            statement:"Fish Is You Favorite Food"
        },
        {
            number:3,
            type:2,
            statement:"List Your Favorite Foods"
        }
    ]

    let responses = [
        {
            number:1,
            value:'Strawberries'
        },
        {
            number:2,
            value:'3',
        },
        {
            number:3,
            value:''
        }
    ]

    let surveyMetaDataArea = document.getElementById('surveyMetaDataArea')

    surveyMetaDataArea.innerHTML = '' +
        '<div class="col">' +

            '<div class="row">' +
                '<h1 id="surveyName">' + survey.title + '</h1>' +
            '</div>' +

            '<div class="row">' +
                '<h4 id="surveyDesc">' + survey.desc + '</h4>' +
            '</div>' +

            '<div class="row center">' +
                '<div class="col-auto"><p id="surveyDate">' + survey.startD + ' - ' + survey.endD + '&nbsp;|</p></div>' +
                '<div class="col-auto"><p id="surveyDate"></p></div>' +
                '<div class="col-auto"><p id="numQuestions">&nbsp;' + survey.numQuestions + ' Questions</p></div>'   +                                
            '</div>' +

        '</div>'

    let scaleLabels = [ 'Strong Disagree', 'Disagree', 'Neutral', 'Agree', 'Strong Agree' ]
    let scaleLabelsNum = [ '1', '2', '3', '4', '5' ]

    let surveyQuestionsArea = document.getElementById('surveyQuestionsArea')

    innerHTMLString = ''

    for( let idx = 0; idx < questions.length; idx++ )
    {
        if( questions[ idx ].type == 2)
        {
            console.log( 'Scale:', questions[idx].type )

            innerHTMLString = innerHTMLString +
                '<div id="question' + questions[idx].number + 'Example" class="row box bd">' +

                    '<div class="col">' +

                        '<div class="row">' +
                            '<div class="col-auto box"><p>' + questions[idx].number + ')</p></div>' +
                            '<div class="col-auto box"><p>' + questions[idx].statement + '</p></div>' +
                        '</div>' +

                        '<div class="row box">' +
                            '<input id="question' + (idx+1) + 'Free" placeholder="Your Answer Goes Here">' +
                        '</div>' +

                    '</div>' +

                '</div>'
        }
        else
        {
            console.log( 'Free Response:', questions[idx].type )

            let startStr = '' +
            '<div id="question' + questions[idx].number + 'Example" class="row box bd">' +
                '<div class="col">' +

                    '<div class="row">' +
                        '<div class="col-auto box"><p>' + questions[idx].number + ')</p></div>' +
                        '<div class="col-auto box"><p>' + questions[idx].statement + '</p></div>' +
                    '</div>' +

                    '<div class="row">'
            
            let endStr = '</div></div></div>'

            let midStr = ''

            for( let i = 0; i < 5; i++ )
            {
                
                midStr = midStr +
                    '<div class="col-auto box">' +
                        '<div class="row">' +
                            '<label>' + scaleLabels[i] + '</label>' +
                        '</div>' +
                        '<div class="row">' +
                            '<input id="question' + (idx+1) + 'Scale' + i + '" name="scale' + (idx+1) +'" type="radio" value="' + (idx+1) + '">' +
                        '</div>' +
                    '</div>'
            }

            innerHTMLString = innerHTMLString + startStr + midStr + endStr
        }
    }

    surveyQuestionsArea.innerHTML = innerHTMLString

    

    for( let idx = 0; idx < responses.length; idx++ )
    {
        if( responses[idx].value != '')
        {
            if( scaleLabelsNum.includes( responses[idx].value ) )
            {
                scaleValueNum = scaleLabelsNum.indexOf( responses[idx].value )

                let scaleInputElm = document.getElementById('question' + responses[idx].number + 'Scale' + scaleValueNum )
                scaleInputElm.checked = true
            }
            else
            {
                let freeResponseInputElm = document.getElementById('question' + responses[idx].number + 'Free' )
                freeResponseInputElm.value = responses[idx].value
            }
        }
    }

}