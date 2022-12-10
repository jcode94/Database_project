
function logout()
{
    console.log( sessionStorage.clear() )
    window.location.href = "./homePage.html"
}