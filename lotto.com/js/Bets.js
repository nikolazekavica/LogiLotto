function deleteListOfBets()
{
    var elements = document.getElementsByClassName("insertRow");
    while (elements.length > 0)
    {
        elements[0].parentNode.removeChild(elements[0]);
    }
}

function createBet()
{
    //Create request
    var hr = new XMLHttpRequest();
    var url = "create-bet";

    //Values
    var clientId = document.getElementById('id').value;
    var stakeAmount = document.getElementById('cash').value;
    var data = {'clientId': clientId, 'stakeAmount': stakeAmount, 'combination': combination};
    var vars = JSON.stringify(data);

    //Start post request
    hr.open("POST", url, true);
    hr.setRequestHeader("Content-type", "application/json");
    hr.send(vars);

    //Response
    hr.onreadystatechange = function ()
    {
        if (hr.readyState === 4 && hr.status === 200)
        {
            var returnData = JSON.parse(hr.responseText);

            if(returnData['success']) {
                resetBallsColor();
            }

            message(returnData)
        }
    };
}

function listOfBets()
{
    this.deleteListOfBets();

    //Create request
    var hr = new XMLHttpRequest();
    var url = "list-of-bets";

    //Values
    var clientId = document.getElementById('id').value;
    var data = {'clientId': clientId};
    var vars = JSON.stringify(data);

    //Start post request
    hr.open("POST", url, true);
    hr.setRequestHeader("Content-type", "application/json");
    hr.send(vars);

    //Response and creating list of bets
    hr.onreadystatechange = function () 
    {
        if (hr.readyState === 4 && hr.status === 200)
        {
            var returnData = JSON.parse(hr.response);

            if (!message(returnData))
            {
                var result  = JSON.stringify(returnData['listOfBets']);
                var jsonObj = JSON.parse(result);

                for (i = 0; i < jsonObj.length; i++) {
                    var node = document.createElement('div');
                    node.style.borderBottom = "1px solid black";
                    node.className = "insertRow";

                    node.innerHTML = '<label id="betId' + i + '"  class="statusId">' + jsonObj[i]['id'] + '</label>\n' +
                        '<label id="combination' + i + '"  class="statusCombination">' + jsonObj[i]['combination'] + '</label>\n' +
                        '<label id="winCombination' + i + '"  class="statusCombination">' + jsonObj[i]['winCombination'] + '</label>\n' +
                        '<label id="betAmount' + i + '"  class="statusBetAmount">' + jsonObj[i]['stake_amount'] + '</label>\n' +
                        '<label id="status' + i + '"  class="statusStatus">' + jsonObj[i]['status'] + '</label>\n' +
                        '<label id="win' + i + '"  class="statusWinAmount">' + jsonObj[i]['win_amount'] + '</label>\n' +
                        '<label id="date' + i + '"  class="statusDate">' + jsonObj[i]['placed_date'] + '</label>\n';

                    document.getElementById('statusRow').appendChild(node);

                    if(jsonObj[i]['status']==='ACTIVE')
                    {
                        document.getElementById('status' + i).style.backgroundColor="#abab22";
                    }
                    else if(jsonObj[i]['status']==="LOSE")
                    {
                        document.getElementById('status' + i).style.backgroundColor='#b71717';
                    }
                    else if(jsonObj[i]['status']==="WIN")
                    {
                        document.getElementById('status' + i).style.backgroundColor='#22ab4b';
                    }
                }

                if (jsonObj.length > 8) {
                    document.getElementById("statusRow").classList.add("scroll");
                } else {
                    document.getElementById("statusRow").classList.remove("scroll");
                }
            }
        }
    };
}