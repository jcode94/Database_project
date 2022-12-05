

function createSurveyObject()
{
    console.log("Create Survey")

    let urlBase = "http://157.245.93.19/backend/api";
    let extension = ".php";

    let flag = false

    let jsonSurvey = {}

    let jsonQuestions = []

    let jsonNumbers = []
    let jsonTypes = []
    let jsonPrompts = []
    let jsonEmails = []

    let surveyName = document.getElementById('surveyName')
    let surveyDesc = document.getElementById('surveyDesc')


    // Check If the Title and Desc are filled in mark if not and set values if they are
    if( surveyName.value.trim() == '' )
    {
        flag = true
        surveyName.style.borderColor = 'red'
        
        surveyName.addEventListener("focus", ()=>{
            surveyName.style.borderColor = 'black'
        })
    }
    else
    {
        surveyName = surveyName.value
    }

    if( surveyDesc.value.trim() == '' )
    {
        flag = true
        surveyDesc.style.borderColor = 'red'
        
        surveyDesc.addEventListener("focus", ()=>{
            surveyDesc.style.borderColor = 'black'
        })
    }
    else
    {
        surveyDesc = surveyDesc.value
    }

    let questionCreationList = document.getElementById("questionCreationList")
    let emailPromptsArea = document.getElementById("emailPromptsArea")

    //Start Iteration Through Questions marking incomplete and adding completed to appropriate lists
    let questions = questionCreationList.childNodes[0].childNodes

    if (questions.length == 0)
    {
        let numQuestionsInputBox = document.getElementById('totalNumberOfQuestions')
        numQuestionsInputBox.style.borderColor = 'red'
        
        numQuestionsInputBox.addEventListener("focus", ()=>{
            numQuestionsInputBox.style.borderColor = 'black'
        })

        flag = true
    }
    else
    {
        questions.forEach(elm=>{

            let number = elm.getAttribute('id').split('question')[1]
            let type = NaN
            let prompt = ''


            // Everything In Question Regarding The Two Radios
            let radios = $("input[type='radio'][name=type" + number + "]" )
            
            if ( !radios[0].checked && !radios[1].checked )
            {
                console.log("here")
                let radioLabel1 = document.getElementById('question' + number + 'RatingLabel')
                let radioLabel2 = document.getElementById('question' + number + 'FreeLabel')

                radioLabel1.style.color = 'red'

                radios[0].addEventListener("focus", ()=>{
                    radioLabel1.style.color = 'black'
                    radioLabel2.style.color = 'black'
                })

                radioLabel2.style.color = 'red'

                radios[1].addEventListener("focus", ()=>{
                    radioLabel1.style.color = 'black'
                    radioLabel2.style.color = 'black'
                })

                flag = true
                console.log("No Radio Selected")
            }
            else
            {
                type = $("input[type='radio'][name=type" + number + "]:checked").val()
            }


            // Everything In The Question Regarding Prompt
            let promptArea = document.getElementById('question' + number + 'Prompt')

            if (promptArea.value.trim() == '')
            {
                promptArea.style.borderColor = 'red'

                promptArea.addEventListener("focus", ()=>{
                    promptArea.style.borderColor = 'black'
                })

                flag = true
                console.log('No Prompt For Question ' + number);
            }
            else
            {
                prompt = promptArea.value
            }


            // Check Values For Output
            console.log( number, type, flag)
            jsonNumbers.push(number)
            jsonTypes.push(type)
            jsonPrompts.push(prompt)

        })

        console.log(jsonNumbers, jsonTypes, jsonPrompts)
    }


    //Start Iteration Through Emails marking incomplete and adding completed to appropriate lists
    let emails = emailPromptsArea.childNodes

    if(emails.length == 0)
    {
        let numEmailsInputBox = document.getElementById('totalNumberOfEmails')
        numEmailsInputBox.style.borderColor = 'red'
        
        numEmailsInputBox.addEventListener("focus", ()=>{
            numEmailsInputBox.style.borderColor = 'black'
        })

        flag = true
    }
    else
    {
       for( let i = 0; i < emails.length; i++)
        {
            let email = document.getElementById('email' + i)

            if( /(.+)@(.+){2,}\.(.+){2,}/.test(email.value) ){
                console.log(email.value,'Is Valid')
                jsonEmails.push(email.value)
            }
            else
            {
                flag = true
                console.log(email.value,'Is Invalid')

                email.style.borderColor = 'red'
        
                email.addEventListener("focus", ()=>{
                    email.style.borderColor = 'black'
                })
            }
        } 

        console.log(jsonEmails)
    }

    // Check Dates mark if not filled set value if filled
    let startD = document.getElementById('startDate')
    let endD = document.getElementById('endDate')

    let parsed = Date.parse( startD.value )

    if( startD.value.trim() == '' || isNaN( Date.parse( startD.value ) ) )
    {
        flag = true
        startD.style.borderColor = 'red'
        
        startD.addEventListener("focus", ()=>{
            startD.style.borderColor = 'black'
        })
    }
    else
    {
        startD = startD.value
    }

    if( endD.value.trim() == '' || isNaN( Date.parse( endD.value ) ) )
    {
        flag = true
        endD.style.borderColor = 'red'
        
        endD.addEventListener("focus", ()=>{
            endD.style.borderColor = 'black'
        })
    }
    else
    {
        endD = endD.value

        console.log(startD,endD)
    }



    if ( flag )
    {  
        // Missing Data / Incomplete Form / Bad Data
        return
    }
    else
    {
        /* Send Survey Packet To API */
        let url = urlBase + "/CreateSurvey" + extension;
        let method = 'POST';

       
     
        // setting up the jsonSurvey
        jsonSurvey['title'] = surveyName
        jsonSurvey['desc'] = surveyDesc
        jsonSurvey['numOfQuestions'] = questions.length
        jsonSurvey['emails'] = jsonEmails
        jsonSurvey['startD'] = startD
        jsonSurvey['endD'] = endD

      


        for( let i = 0; i < questions.length; i++)
        {
            let tmpQuestion = {}

            
            tmpQuestion['type'] = jsonTypes[i]
            tmpQuestion['statement'] = jsonPrompts[i]
            tmpQuestion['number'] = jsonNumbers[i]
            
            jsonQuestions.push(tmpQuestion)
        }

        jsonSurvey['questions'] = jsonQuestions

        let jsonPayLoad = JSON.stringify({
            author: sessionStorage['userEmail'],
            emails: jsonSurvey['emails'],
            title: jsonSurvey['title'],
            description:   jsonSurvey['desc'],
            startD: jsonSurvey['startD'], 
            endD:   jsonSurvey['endD'], 
            numOfQuestions: jsonSurvey['numOfQuestions'],
            questions: jsonSurvey['questions']
        });
        console.log(jsonPayLoad);
        let xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

        xhr.send(jsonPayLoad);
    }
   
    // If no flag was triggered return jsonSurvey
    console.log(jsonSurvey)
    
    return jsonSurvey
}