// JUSTIN - function for getting specific pilot and all of his data
// function getPilot(id) {
//     $.ajax({
//         type: "POST",
//         url: "../get_pilot.php",
//         data: { id: id },
//         success: function (data) {
//             var res = JSON.parse(data);
//             res["admin"][0] = parseInt(res["admin"][0]);
//             var i = 0;
//             var pilotIsMe = (res.id[0] == myPilotID);
//             var body = "";
//             body += "<div class='inner-bottom-sm'><div class='errors'></div>"
//             $("#pilot_info_section").children("h2").html(res["fname"][i] + " " + res["lname"][i] + " <button class='btn btn-primary pull-right' id='printPilotBtn' data-toggle='modal' data-target='#printPilotModal'>Export XLSX</button>");
//             $("#printPilotBtn").show().attr("data-pk", res.id[i]);

//             body += "<div class='col-md-11 center-block outer-bottom-xs'><h4 class='page-header'>Personal Information</h4>";
//             body += "<div class='col-md-6'><ul id='personalInfo'>";
//             body += "<li>Name: <div id=\"name\"><span class='infoEdit' data-pk='" + res.id[i] + "' data-name='fname'>" + res["fname"] + "</span> <div style='margin-left: 0.5em; display: inline;'><span class='infoEdit' data-name='lname' data-pk='" + res.id[i] + "'>" + res["lname"] + "</span></div></div></li>";
//             body += "<li>Username: <div id=\"username\"><span class='infoEdit' data-pk='" + res.id[i] + "' data-name='username'>" + res["username"][i] + "</span></div></li>";
//             body += "<li>Nationality: <div id=\"nationality\"><span class='infoEdit' data-pk='" + res.id[i] + "' data-name='nationality'>" + res["nationality"] + "</span></div></li>";
//             if (res["comandante"] == 1) {
//                 var pos = "Comandante";
//             } else {
//                 var pos = "Piloto";
//             }
//             if ([0, 1, 2, 3, 8].indexOf(res["admin"][i]) != -1) {
//                 body += "<li>Current Position: <div id=\"pos\"><span class='infoEdit' data-type='select' data-pk='" + res.id[i] + "' data-name='comandante'>" + pos + "<span></div></li>";
//                 body += "<li>" + accountNationality + " License: <div id=\"angLic\"><span class='infoEdit' data-pk='" + res.id[i] + "' data-name='ang_license'>" + res["ang_license"] + "</span></div></li>";
//                 body += "<li>Foreign License: <div id=\"forLic\"><span class='infoEdit' data-pk='" + res.id[i] + "' data-name='for_license'>" + res["for_license"] + "</span></div></li>";
//             }
//             body += "<li>E-mail: <div id=\"persEmail\"><span class='infoEdit' data-pk='" + res.id[i] + "' data-name='email'>" + res["email"] + "</span></div></li>";
//             body += "<li>Phone: <div id=\"persPhone\"><span class='infoEdit' data-pk='" + res.id[i] + "' data-name='phone' data-placeholder='1 555-555-5555'>" + res["phone"] + "</span></div></li>";
//             body += "<li>Secondary Phone: <div id=\"persPhoneTwo\"><span class='infoEdit' data-pk='" + res.id[i] + "' data-name='phonetwo' data-placeholder='1 555-555-5555'>" + res["phonetwo"] + "</span></div></li>";
//             body += "</ul>";
//             //permissions

//             body += "</div>";

//             body += "<div class='col-md-6 outer-bottom-xs'><div class='lbl no-margin'>Profile Picture</div><div id=\"profile-picture-container\">";
//             body += "<img src=\"uploads/pictures/" + (res.profile_picture[i] != null ? res.profile_picture[i] : "default_picture.jpg") + "\" id=\"profile-picture\" width=\"100%\"/>";
//             if (admin > 0 || pilotIsMe)
//                 body += "<div id=\"profile-picture-overlay\" data-toggle=\"modal\" data-target=\"#change-profile-picture\"><div class=\"fa fa-3x fa-pencil\"></div></div>";
//             body += "</div></div>";

//             if ([0, 1, 2, 3, 8].indexOf(res["admin"][i]) != -1) {
//                 var Now = new Date().getTime();
//                 var OnDuty = false;
//                 //ON OFF
//                 var onOffAr = res["onOff"][i];
//                 var onTbl = "<table class='table table-condensed table-bordered' style='-webkit-box-shadow: 0px 0px 0px 0px rgba(0,0,0,0); -moz-box-shadow: 0px 0px 0px 0px rgba(0,0,0,0); box-shadow: 0px 0px 0px 0px rgba(0,0,0,0);'><thead><th>On Duty</th><th>Off Duty</th><th>Scheduled</th></thead>";
//                 for (var o = 0; o < onOffAr.length; o++) {
//                     if (onOffAr[o]["inSched"]) {
//                         var inSchedStr = "<td class='active'>True</td><th class=\"text-center\"><div class=\"btn btn-sm btn-warning removeAvailDates\" data-on='1' data-id='" + res["id"][i] + "'>-</div></th></tr>";
//                         var oclss = "dateEditable";
//                     } else {
//                         var inSchedStr = "<td class='info'>False</td><th class=\"text-center\"><div class=\"btn btn-sm btn-warning removeAvailDates\" data-on='0' data-id='" + res["id"][i] + "'>-</div></th></tr>";
//                         var oclss = "dateEditable";
//                     }
//                     onTbl += "<tr><td><span class='on-date " + oclss + "' data-name='on' data-pk='" + res["id"][i] + "'>" + onOffAr[o]["on"] + "</span></td><td><span class='off-date " + oclss + "' data-name='off' data-pk='" + res["id"][i] + "'>" + onOffAr[o]["off"] + "</span></td>" + inSchedStr;
//                 }
//                 onTbl += "<tr id='addDateRow'><td><input type='text' placeholder='YYYY-mm-dd' class='on-date form-control' /></td><td><input type='text'  placeholder='YYYY-mm-dd' class='off-date form-control' /></td><td></td><th class=\"text-center\"><div class=\"btn btn-sm btn-primary addOnOffDate\" data-id='" + res["id"][i] + "'>+</div></th></tr>";
//                 onTbl += "</table><p class='outer-bottom-sm'><strong>NOTE: Deleting or modifying the availability of a pilot who is in the schedule will result in the truncation of the schedule to that date.</strong></p>";
//                 body += onTbl;

//                 //VALIDITY TABLE
//                 var array = [[]];
//                 var counter = 0;
//                 var c = 0;
//                 for (var key in res["validity"][i]) {
//                     if (key != "id") {
//                         array[c].push({ header: key, val: res["validity"][i][key] })
//                         counter++;
//                         if (counter != 0 && counter % 7 == 0) {
//                             array.push([]);
//                             c++;
//                         }
//                     }
//                 }

//                 var str = "";
//                 var headerStr, bodyStr;
//                 for (var k = 0; k < array.length; k++) {
//                     headerStr = "<thead>";
//                     bodyStr = "<tr>";
//                     for (var j = 0; j < array[k].length; j++) {
//                         headerStr += "<th>" + getTestName(array[k][j].header) + "</th>";
//                         if (array[k][j].val != null) {
//                             var cur = new Date();
//                             var vald = new Date(array[k][j].val + "T12:00:00");
//                             var cls = "tint-bg";
//                             var dateStr = vald.getFullYear() + "-" + returnAbrvMonth(vald.getMonth()) + "-" + doubleDigit(vald.getDate());
//                             var content = "<span class='valText'>Valid</span><br/><span class='validityDate' data-type='date' data-name='" + array[k][j].header + "' data-pk='" + res["id"][i] + "'>" + dateStr + "</span>";
//                             if (vald.getTime() < cur.getTime()) {
//                                 cls = "alert alert-danger"
//                                 content = "<span class='valText'>EXPIRED</span><br/><span class='validityDate' data-type='date' data-name='" + array[k][j].header + "' data-pk='" + res["id"][i] + "'>" + dateStr + "</span>";
//                             } else if (vald.getTime() - cur.getTime() <= (4 * 7 * 24 * 60 * 60 * 1000)) {
//                                 cls = "alert alert-warning";
//                                 content = "<span class='valText'>Expires Soon</span><br/><span class='validityDate' data-type='date' data-name='" + array[k][j].header + "' data-pk='" + res["id"][i] + "'>" + dateStr + "</span>";
//                             }
//                         } else {
//                             var cls = "alert-null";
//                             var content = "<span class='valText'></span><br/><span class='validityDate' data-type='date' data-name='" + array[k][j].header + "' data-pk='" + res["id"][i] + "'>Select Expiry Date</span>";
//                         }
//                         bodyStr += "<td class='" + cls + "'>" + content + "</td>";
//                     }
//                     headerStr += "</thead>";
//                     bodyStr += "</tr>";
//                     str += "<div class='col-md-12'><table class='val_table'>" + headerStr + bodyStr + "</table></div>";
//                 }
//                 body += str;
//             }
//             // if([0,1,2,3,8].indexOf(res["admin"][i]) != -1){
//             // 	
//             // }
//             body += "</div>";
//             $("#pilot_info").html("<div class='row outer-left-xxs outer-right-xxs'>" + body + "</div>")

//             $("#adminTypeSelect").val(res["admin"][i]);

//             $("#personalInfo li").children().css("display", "inline");

//             $("#picture-pilot-id").val(res.id[i]);
//         },
//         error: function (xhr, status, error) {
//             console.error("Error fetching pilot data:", status, error);
//             showNotification("error", "Failed to retrieve pilot information.");
//         }
//     });
// }

// ==========================================================================================


// =========== BELOW DO NOT REMOVE OR DELETE - DISPLAYED pilot.php =====================

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search_pilot');
    const sortSelect = document.getElementById('sortBy');
    const craftSelect = document.getElementById('craftType');
    const showNonPilotsSelect = document.getElementById('showNonPilots');
    const pilotsList = document.getElementById('pilots_list');
    const pilotInfoSection = document.getElementById('pilot_info_section');

    // Initialize autocomplete
    initAutocomplete();

    // Load pilots on page load
    loadPilots();

    // Event listeners for search and filters
    [searchInput, sortSelect, craftSelect, showNonPilotsSelect].forEach(element => {
        element.addEventListener('input', loadPilots);
        element.addEventListener('change', loadPilots);
    });

    // Pilot click handler
    pilotsList.addEventListener('click', function (e) {
        const pilotItem = e.target.closest('.pilot-item');
        if (pilotItem) {
            const pilotId = pilotItem.dataset.pilotId;
            loadPilotDetails(pilotId);
        }
    });

    async function loadPilots() {
        const params = new URLSearchParams({
            search: searchInput.value,
            sort: sortSelect.value,
            craft: craftSelect.value,
            showNonPilots: showNonPilotsSelect.value
        });

        try {
            const response = await fetch('pilot_list.php?' + params.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            pilotsList.innerHTML = data.html;
            addPilotClickHandlers();
        } catch (error) {
            console.error('Error loading pilots:', error);
        }
    }

    function addPilotClickHandlers() {
        document.querySelectorAll('.pilot-item').forEach(item => {
            // item.addEventListener('click', showPilotDetails);
            // item.addEventListener('click', loadPilotDetails);
        });
    }

    async function loadPilotDetails(pilotId) {
        try {
            pilotInfoSection.innerHTML = '<div class="loading">Loading pilot details...</div>';
            const response = await fetch(`get_pilot_info.php?id=${pilotId}`);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to load pilot details');
            }

            displayPilotInfo(data.data);
            // initDatepickers();
            // initializeValidityTable();
        } catch (error) {
            pilotInfoSection.innerHTML = `<div class="error">Error loading pilot details: ${error.message}</div>`;
            console.error('Pilot details error:', error);
        }
    }

    function displayPilotInfo(pilotData) {
        const pilotInfoHtml = `
            <div class="pilot-card">
                <div class="pilot-header">
                    <h3>${pilotData.firstname} ${pilotData.lastname}</h3>
                    <span class="badge ${pilotData.access_level > 0 ? 'manager' : 'pilot'}">
                        ${pilotData.access_level > 0 ? 'Manager' : 'Pilot'}
                    </span>
                </div>
                <div class="pilot-details-grid">
                    <div class="detail-group">
                        <h4>Personal Information-ajax</h4>
                        <p><strong>Username1:</strong> ${pilotData.username}</p>
                        <p><strong>Nationality:</strong> ${pilotData.user_nationality || 'N/A'}</p>
                        <p><strong>Position:</strong> ${pilotData.job_position || 'N/A'}</p>
                        <p><strong>Member Since:</strong> ${pilotData.created_at || 'N/A'}</p>
                    </div>
                    <div class="detail-group">
                        <h4>Licenses</h4>
                        <p><strong>National License:</strong> ${pilotData.nal_license || 'N/A'}</p>
                        <p><strong>Foreign License:</strong> ${pilotData.for_license || 'N/A'}</p>
                    </div>
                    <div class="detail-group">
                        <h4>Contact Information</h4>
                        <p><strong>Email:</strong> <a href="mailto:${pilotData.email}">${pilotData.email}</a></p>
                        <p><strong>Phone:</strong> ${pilotData.phone || 'N/A'}</p>
                        ${pilotData.phonetwo ? `<p><strong>Secondary Phone:</strong> ${pilotData.phonetwo}</p>` : ''}
                    </div>
                </div>
            </div>

        
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Certification Validity</h3>
                </div>
                <div class="panel-body">
                    <div id="notification-container"></div>
                    <table class="table table-condensed table-bordered no-shadow" id="validityTable">
                        <thead>
                            <tr>
                                <th>Certification987</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generateValidityRows(pilotData)}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
        pilotInfoSection.innerHTML = pilotInfoHtml;
    }

    function generateValidityRows(data) {
        const validityFields = [
            'for_lic', 'passport', 'nal_visa', 'us_visa', 'instruments', 'booklet',
            'train_rec', 'flight_train', 'base_check', 'night_cur', 'night_check',
            'ifr_cur', 'ifr_check', 'line_check', 'hoist_check', 'hoist_cur',
            'crm', 'hook', 'herds', 'dang_good', 'huet', 'english', 'faids',
            'fire', 'avsec'
        ];

        return validityFields.map(field => {
            const label = getValidityLabel(field);
            const expiryDate = data[field] || '';
            const today = new Date().toISOString().split('T')[0];
            // const statusClass = (expiryDate && expiryDate >= new Date().toISOString().split('T')[0]) ? 'text-success' : 'text-danger';
            // const statusText = (expiryDate && expiryDate >= new Date().toISOString().split('T')[0]) ? 'Valid' : 'Expired';
            const statusClass = expiryDate && expiryDate >= today ? 'text-success' : 'text-danger';
            const statusText = expiryDate && expiryDate >= today ? 'Valid' : 'Expired';

            return `
                <tr>
                    <td><strong>${label}</strong></td>
                    <td>${expiryDate || 'N/A'}</td>
                    <td class="${statusClass}">${statusText}</td>
            </tr>
            `;
        }).join('');
    }

    function getValidityLabel(field) {
        const labels = {
            'for_lic': 'Foreign License',
            'passport': 'Passport',
            'nal_visa': 'National Visa',
            'us_visa': 'USA Visa',
            'instruments': 'Instrument Rating',
            'booklet': 'Flight Log Book',
            'train_rec': 'Training Records',
            'flight_train': 'Flight Training',
            'base_check': 'Base Check',
            'night_cur': 'Night Currency',
            'night_check': 'Night Check',
            'ifr_cur': 'IFR Currency',
            'ifr_check': 'IFR Check',
            'line_check': 'Line Check',
            'hoist_check': 'Hoist Currency',
            'hoist_cur': 'Hoist Currency',
            'crm': 'CRM Certification',
            'hook': 'Hook Operation',
            'herds': 'HERDS Training',
            'dang_good': 'Dangerous Goods',
            'huet': 'HUET Certification',
            'english': 'English Proficiency',
            'faids': 'First Aid',
            'fire': 'Fire Fighting',
            'avsec': 'AVSEC Certification'
        };
        return labels[field] || field;
    }

    function generateValidityRows(data) {
        const validityFields = [
            'for_lic', 'passport', 'nal_visa', 'us_visa', 'instruments', 'booklet',
            'train_rec', 'flight_train', 'base_check', 'night_cur', 'night_check',
            'ifr_cur', 'ifr_check', 'line_check', 'hoist_check', 'hoist_cur',
            'crm', 'hook', 'herds', 'dang_good', 'huet', 'english', 'faids',
            'fire', 'avsec'
        ];

        return validityFields.map(field => {
            const label = getValidityLabel(field);
            const expiryDate = data[field] || '';
            const today = new Date().toISOString().split('T')[0];
            // const statusClass = (expiryDate && expiryDate >= new Date().toISOString().split('T')[0]) ? 'text-success' : 'text-danger';
            // const statusText = (expiryDate && expiryDate >= new Date().toISOString().split('T')[0]) ? 'Valid' : 'Expired';
            const statusClass = expiryDate && expiryDate >= today ? 'text-success' : 'text-danger';
            const statusText = expiryDate && expiryDate >= today ? 'Valid' : 'Expired';

            return `
                <tr>
                <td><strong>${label}</strong></td>
                <td>${expiryDate || 'N/A'}</td>
                <td class="${statusClass}">${statusText}</td>
            </tr>
            `;
        }).join('');
    }

    function getValidityLabel(field) {
        const labels = {
            'for_lic': 'Foreign License',
            'passport': 'Passport',
            'nal_visa': 'National Visa',
            'us_visa': 'USA Visa',
            'instruments': 'Instrument Rating',
            'booklet': 'Flight Log Book',
            'train_rec': 'Training Records',
            'flight_train': 'Flight Training',
            'base_check': 'Base Check',
            'night_cur': 'Night Currency',
            'night_check': 'Night Check',
            'ifr_cur': 'IFR Currency',
            'ifr_check': 'IFR Check',
            'line_check': 'Line Check',
            'hoist_check': 'Hoist Check',
            'hoist_cur': 'Hoist Currency',
            'crm': 'CRM Certification',
            'hook': 'Hook Operation',
            'herds': 'HERDS Training',
            'dang_good': 'Dangerous Goods',
            'huet': 'HUET Certification',
            'english': 'English Proficiency',
            'faids': 'First Aid',
            'fire': 'Fire Fighting',
            'avsec': 'AVSEC Certification'
        };
        return labels[field] || field;
    }

    // Initialize datepicker
    function initDatepickers() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function (dateText, inst) {
                const row = $(this).closest('tr');
                const statusCell = row.find('.status-cell');
                const currentDate = new Date();
                const selectedDate = new Date(dateText);

                statusCell.removeClass('text-success text-danger text-muted')
                    .text(selectedDate > currentDate ? 'Valid' : 'Expired')
                    .addClass(selectedDate > currentDate ? 'text-success' : 'text-danger');
            }
        });
    }

    // Initialize event listeners for the validity table
    function initializeValidityTable() {
        // Save single validity
        $('#validityTable').on('click', '.save-validity', function () {
            const row = $(this).closest('tr');
            const isNew = row.hasClass('new-validity');
            const data = {
                field: row.data('field') || row.find('.validity-name').val(),
                value: row.find('.validity-date').val()
            };

            $.ajax({
                url: 'update_validity.php',
                method: 'POST',
                data: data,
                success: function (response) {
                    if (response.success) {
                        showNotification('success', 'The date was saved successfully');
                        if (isNew) {
                            row.removeClass('new-validity')
                                .find('.validity-name').replaceWith(`<strong>${data.field}</strong>`);
                        }
                    } else {
                        showNotification('error', response.error || 'Error saving validity');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error saving validity date:', status, error);
                    showNotification('error', 'An error occurred while saving the validity date.');
                }
            });
        });

        // Add new validity row
        $('#addValidityRow').click(function () {
            const newRow = $('#validityTemplate').clone()
                .removeAttr('id')
                .addClass('new-validity')
                .show();
            $('#validityTable tbody').append(newRow);
            initDatepickers();
        });

        // Remove validity row 
        $('#validityTable').on('click', '.remove-validity', function () {
            const row = $(this).closest('tr');
            if (row.hasClass('new-validity')) {
                row.remove();
            } else {
                if (confirm('Are you sure you want to delete this validity?')) {

                    $.ajax({
                        url: 'delete_validity.php',
                        method: 'POST',
                        data: { field: row.data('field') },
                        success: function (response) {
                            if (response.success) {
                                row.remove();
                                showNotification('success', 'Validity removed successfully');
                            } else {
                                showNotification('error', response.error || 'Error removing validity');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error removing validity date:', status, error);
                            showNotification('error', 'An error occurred while removing the validity date.');
                        }
                    });
                }
            }
        });
    }

    // Function to show notifications
    function showNotification(type, message) {
        const notification = $('<div>')
            .addClass(`alert alert-${type}`)
            .text(message)
            .append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

        $('#notification-container').append(notification);

        setTimeout(function () {
            notification.alert('close');
        }, 3000);
    }

    // ====== ABOVE - ENd Certification Validity ============================

    // =========== AUTOCOMPLETE =====================

    function initAutocomplete() {
        const searchInput = document.getElementById('search_pilot');
        const resultsContainer = document.querySelector('.autocomplete-results');
        let debounceTimer;

        searchInput.addEventListener('input', function (e) {
            clearTimeout(debounceTimer);
            const term = e.target.value.trim();

            if (term.length > 1) {
                debounceTimer = setTimeout(() => {
                    fetch(`autocomplete.php?term=${encodeURIComponent(term)}`)
                        .then(response => response.json())
                        .then(data => {
                            showResults(data);
                        });
                }, 300);
            } else {
                resultsContainer.style.display = 'none';
            }
        });

        function showResults(items) {
            resultsContainer.innerHTML = '';
            if (items.length > 0) {
                items.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'autocomplete-item';
                    div.innerHTML = highlightMatch(item.label, searchInput.value);
                    div.addEventListener('click', () => {
                        searchInput.value = item.value;
                        resultsContainer.style.display = 'none';
                        loadPilots(); // Trigger your existing search
                    });
                    resultsContainer.appendChild(div);
                });
                resultsContainer.style.display = 'block';
            } else {
                resultsContainer.style.display = 'none';
            }
        }

        function highlightMatch(text, match) {
            const regex = new RegExp(`(${match})`, 'gi');
            return text.replace(regex, '<span class="highlight">$1</span>');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.autocomplete-wrapper')) {
                resultsContainer.style.display = 'none';
            }
        });
    }
});

// =========== BELOW DO NOT REMOVE OR DELETE - DISPLAYED pilot.php =====================