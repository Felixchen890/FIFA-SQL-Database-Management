'use strict';

function tableDroplistChange() {
    const selectTableElem = document.getElementById('table');
    const selectedOption = selectTableElem.options[selectTableElem.selectedIndex].innerText;
    const selectAttributeElem = document.getElementById('attribute');
    console.log(selectTableElem);
    console.log(selectedOption);

    if (selectedOption === "Team") {
        console.log(selectedOption);
        selectAttributeElem.options.length = 0;
        selectAttributeElem.options[0] = new Option('--Select an attribute--', '');
        selectAttributeElem.options[1] = new Option('teamID', 'teamID');
        selectAttributeElem.options[2] = new Option('tName', 'tName');
        selectAttributeElem.options[3] = new Option('city', 'city');
        selectAttributeElem.options[4] = new Option('states', 'states');
        selectAttributeElem.options[5] = new Option('all attributes', '*');
    } else if (selectedOption === "Player") {
        console.log(selectedOption);
        selectAttributeElem.options.length = 0;
        selectAttributeElem.options[0] = new Option('--Select an attribute--', '');
        selectAttributeElem.options[1] = new Option('playerID', 'playerID');
        selectAttributeElem.options[2] = new Option('firstName', 'firstName');
        selectAttributeElem.options[3] = new Option('lastName', 'lastName');
        selectAttributeElem.options[4] = new Option('jerseyNumber', 'jerseyNumber');
        selectAttributeElem.options[5] = new Option('all attributes', '*');
    } else {
        console.log("No table is selected");
        selectAttributeElem.options.length = 0;
    }
}

function aggregationWithGroupByChange() {
    console.log('hello world');
    const selectTableElem = document.getElementById('aggregationWithGroupByTable');
    const selectedOption = selectTableElem.options[selectTableElem.selectedIndex].innerText;
    const selectAttributeElem = document.getElementById('aggregationWithGroupByAttribute');
    const selectAggregateOperatorElem = document.getElementById('aWithGroupByAggregateOperator');

    if (selectedOption === 'Team') {
        console.log('team selected');
        selectAttributeElem.options.length = 0;
        selectAttributeElem.options[0] = new Option('--Select an attribute--', '');
        selectAttributeElem.options[1] = new Option('states', 'states');

        selectAggregateOperatorElem.options.length = 0;
        selectAggregateOperatorElem.options[0] = new Option('--Select an aggregate attribute', '');
        selectAggregateOperatorElem.options[1] = new Option('count', 'count');
    } else if (selectedOption === 'Player') {
        console.log('player selected');
        selectAttributeElem.options.length = 0;
        selectAttributeElem.options[0] = new Option('--Select an attribute--', '');
        selectAttributeElem.options[1] = new Option('jerseyNumber', 'jerseyNumber');

        selectAggregateOperatorElem.options.length = 0;
        selectAggregateOperatorElem.options[0] = new Option('--Select an aggregate attribute', '');
        selectAggregateOperatorElem.options[1] = new Option('count', 'count');
    } else {
        console.log('no table is selected');
        selectAttributeElem.options.length = 0;
        selectAggregateOperatorElem.options.length = 0;
    }
}
