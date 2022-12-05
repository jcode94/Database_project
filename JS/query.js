


// This code is for the date picker, don't touch plz
$(document).ready(function(){

    $(function () {
        $('.datepicker').datepicker({
            format: 'yyyy/mm/dd' 
        });
    });

    console.log($('.answersList').height(), $('.questionInner').height())

    $( function () {
        $('.answersList').height( $('.questionInner').height() )
    })

    console.log($('.answersList').height(), $('.questionInner').height())
    
});


    