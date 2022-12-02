

function getSurveys()
{
    console.log("Here")

    let surveyArea = document.getElementById('surveyArea')

    for (let index = 0; index < 10; index++) {
        surveyArea.innerHTML = surveyArea.innerHTML + 
        '<div id="" class="col-auto color">'+
            '<div class="col">'+
                '<div>'+
                    '<p>Survey Name</p>'+
                '</div>'+
                
            '</div>'+
            '<div class="col">'+
                '<p>Questions: ###</p>'+
                '<p>New</p>'+
            '</div>'+
       ' </div>'       
    }
}