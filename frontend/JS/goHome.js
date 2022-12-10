function goHome()
{
    sessionStorage.removeItem('surveyId')

    if(sessionStorage['surveyId'] == undefined)
    {
        window.location.href = "./homePage.html"
    }
}