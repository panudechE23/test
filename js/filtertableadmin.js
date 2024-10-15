
function filterTable() {
    var input, filter, table, tr, tdName, tdId, i, txtValueName, txtValueId;
    input = document.getElementById("searchInput");
    filter = input.value.toLowerCase();
    table = document.getElementById("productTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        tdId = tr[i].getElementsByTagName("td")[0];  
        tdName = tr[i].getElementsByTagName("td")[1]; 

        if (tdId || tdName) {
            txtValueId = tdId.textContent || tdId.innerText;
            txtValueName = tdName.textContent || tdName.innerText;
            if (txtValueId.toLowerCase().indexOf(filter) > -1 || txtValueName.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            }
        }
    }
}


function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("productTable");
    switching = true;
    dir = "asc";

    while (switching) {
        switching = false;
        rows = table.rows;

        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];

            var xValue = x.innerHTML.toLowerCase();
            var yValue = y.innerHTML.toLowerCase();
            if (n === 0) {
                xValue = parseFloat(x.innerHTML) || 0;
                yValue = parseFloat(y.innerHTML) || 0;
            }

            if (n === 7) {
                xValue = parseFloat(x.innerHTML) || 0;
                yValue = parseFloat(y.innerHTML) || 0;
            }

            if (dir === "asc") {
                if (xValue > yValue) {
                    shouldSwitch = true;
                    break;
                }
            } else if (xValue < yValue) {
                shouldSwitch = true;
                break;
            }
        }

        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount === 0 && dir === "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}

function filterTableByType() {
    var typeFilter = document.getElementById("typeFilter").value.toLowerCase();
    var table = document.getElementById("productTable");
    var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

    for (var i = 0; i < rows.length; i++) {
        var typeCell = rows[i].getElementsByTagName("td")[3];
        if (typeCell) {
            var typeText = typeCell.textContent || typeCell.innerText;
            if (typeText.toLowerCase().indexOf(typeFilter) > -1 || typeFilter === "") {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}


function toggleDropdown() {
    var dropdown = document.getElementById('typeDropdown');
    if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
    } else {
        dropdown.classList.add('show');
    }
}

window.onclick = function(event) {
    if (!event.target.matches('.dropdown-toggle') && !event.target.closest('#typeDropdown')) {
        var dropdowns = document.getElementsByClassName('dropdown-filter');
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}

document.getElementById('typeDropdown').addEventListener('click', function(event) {
    event.stopPropagation();
});