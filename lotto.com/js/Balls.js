var combinationLength = 0;
const maxCombinationLength = 7;
var combination = [];

//Check if user select max seven numbers
function countValidator(count, id) {
    if (count === maxCombinationLength)
    {
        alert("You can chose 7 numbers!")
        return false;
    } else {
        document.getElementById(id).style.background = "limegreen";
        return true;
    }
}

//On click change button color.
function ballsColor(ballNumber)
{
    var buttonBackgroundColor = document.getElementById(ballNumber).style.background;

    if (buttonBackgroundColor === "limegreen")
    {
        document.getElementById(ballNumber).style.background = "black";

        var index = combination.indexOf(ballNumber);

        combination.splice(index, 1);
        combinationLength--;
    } else if (this.countValidator(combinationLength, ballNumber))
    {
        combinationLength++;
        combination.push(ballNumber);
    }
}

function resetBallsColor()
{
    elements = document.getElementsByClassName("button-balls");
    for (var i = 0; i < elements.length; i++) {
        elements[i].style.backgroundColor = "black";
    }
    combinationLength=0;
    combination = [];
}