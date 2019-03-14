function deleteMessage()
{
    var errorElements = document.getElementById("message");
    if (errorElements) {
        document.getElementById("message").remove();
    }
}

function message(message)
{
    this.deleteMessage();

    if (message['error'])
    {
        var errorMessage = document.createElement('div');
        errorMessage.id = "message";
        errorMessage.innerHTML =
            '<label id="errorMessage" class="errorMessage">' + message['error'] + '</label>\n';

        document.getElementById('responseMessage').appendChild(errorMessage);
    }
    else if(message['success'])
    {
        var accessMessage = document.createElement('div');
        accessMessage.id = "message";
        accessMessage.innerHTML =
            '<label id="successMessage" class="successMessage">' + message['success'] + '</label>\n';

        document.getElementById('responseMessage').appendChild(accessMessage);
    }else{
        return false;
    }
}
