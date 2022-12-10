

function generateEmailList()
{
    console.log("generateEmailList")

    let numOfEmails = document.getElementById('totalNumberOfEmails').value

    if (numOfEmails <= 0 ) {return}

    document.getElementById("invisEmail").classList.remove('invisable')

    let emailPromptsArea = document.getElementById("emailPromptsArea")

    let innerHTMLString = ''

    for( let idx = 0; idx < numOfEmails; idx++ )
    {
        innerHTMLString = innerHTMLString +
            '<div class="col-auto bd">' +
                '<div class="row box">' +
                    '<div class="col box">' +
                        '<p>Email</p>' +
                    '</div>' +
                    '<div class="col box">' +
                        '<input id="email' + idx + '" type="text" placeholder="Email">' +
                    '</div>' +
                '</div>' +
            '</div>'
    }

    document.getElementById('totalNumberOfEmails').value = ''

    emailPromptsArea.innerHTML = innerHTMLString

}