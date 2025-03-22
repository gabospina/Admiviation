// ========== DO NOT TOUCH OR REMOVE THESE FUNCTION BELOW - Work with hangar.php (Feb-15-24) =============

function addDateFunctionality() {
	$(document).on('click', ".addOnOffDate", function (e) {
		e.stopPropagation();
		var $this = $(this);
		var $row = $this.closest('tr');
		var on = $row.find(".on-date").val();
		var off = $row.find(".off-date").val();
		var id = $this.data("id");

		if (!on) {
			$row.find(".on-date").focus();
			return;
		}
		if (!off) {
			$row.find(".off-date").focus();
			return;
		}

		var inputOn = new Date(on + "T12:00:00");
		var inputOff = new Date(off + "T12:00:00");
		var already = false, error = [];

		$this.closest("tbody").children().each(function () {
			if ($(this).attr('id') === 'addDateRow') return; // Skip the input row
			var tempOn = new Date($(this).find(".on-date").text() + "T12:00:00");
			var tempOff = new Date($(this).find(".off-date").text() + "T12:00:00");
			if (dates.inRange(inputOn, tempOn, tempOff)) {
				error.push("Date ranges may not overlap. Your STARTING date is already scheduled.");
				already = true;
			} else if (dates.inRange(inputOff, tempOn, tempOff)) {
				error.push("Date ranges may not overlap. Your ENDING date is already scheduled.");
				already = true;
			}
		});

		if (already) {
			error.forEach(function (err) {
				showNotification("error", err);
			});
			return;
		}

		$.ajax({
			type: "POST",
			url: "insert_on_off.php",
			data: { id: id, on: on, off: off },
			dataType: "json",
			cache: false,
			success: function (response) {
				if (response && response.success) {
					showNotification("success", "You successfully added the date");
					//UPDATE THE ROW DATA, REMOVE TIME VALUES
					var rowstr = "<tr><td><span class='dateEditable on-date' data-name='on_date'>" + on + "</span></td><td><span class='dateEditable off-date' data-name='off_date'>" + off + "</span></td><td class='info'>False</td><th class=\"text-center\"><div class=\"btn btn-sm btn-warning removeAvailDates\" data-on='" + on + "' data-off='" + off + "' data-id='" + id + "'>-</div></th></tr>";
					$(rowstr).insertBefore($this.closest('tr'));
					// Clear the input dates
					$row.find(".on-date").val("");
					$row.find(".off-date").val("");

					// Display the success message
					$('<div class="alert alert-success" role="alert">Dates Saved Successfully!</div>')
						.insertBefore($this.closest('th'))
						.delay(3000) // Fade out after 3 seconds (optional)
						.fadeOut();
				} else {
					showNotification("error", response.error || "Failed to insert new entry.");
				}
			},
			error: function (xhr, status, error) {
				showNotification("error", "AJAX error: " + status + " - " + error);
				console.error("AJAX error:", status, error, xhr.responseText);
			}
		});
	});
}

function removeDateFunctionality() {
	$(document).on('click', ".removeAvailDates", function (e) {
		e.stopPropagation();
		var $this = $(this);
		var id = $this.data("id");
		var on = $this.data("on");
		var off = $this.data("off");

		$.ajax({
			type: "POST",
			url: "delete_on_off.php", // Create this file
			data: { id: id, on: on, off: off },
			dataType: "json",
			cache: false,
			success: function (response) {
				if (response && response.success) {
					showNotification("success", "Date removed successfully.");
					$this.closest("tr").remove();  // Remove the row from the table
				} else {
					showNotification("error", response.error || "Failed to remove date.");
				}
			},
			error: function (xhr, status, error) {
				showNotification("error", "AJAX error: " + status + " - " + error);
				console.error("AJAX error:", status, error, xhr.responseText);
			}
		});
	});
}

function loadAvailability() {
	var userId = $(".addOnOffDate").data("id"); // Assuming the ID is available this way
	$.ajax({
		type: "GET",
		url: "get_availability.php",
		dataType: "json",
		cache: false,
		success: function (data) {
			if (data && Array.isArray(data)) {
				data.forEach(function (item) {
					var rowstr = "<tr><td><span class='dateEditable on-date' data-name='on_date'>" + item.on_date + "</span></td><td><span class='dateEditable off-date' data-name='off_date'>" + item.off_date + "</span></td><td class='info'>False</td><th class=\"text-center\"><div class=\"btn btn-sm btn-warning removeAvailDates\" data-on='" + item.on_date + "' data-off='" + item.off_date + "' data-id='" + userId + "'>-</div></th></tr>";
					$("#addDateRow").before(rowstr); // Add before the input row

				});
				// removeDateFunctionality(); // Initialize remove functionality after loading
				// enableDateEditing();
			} else {
				console.log("No availability data or error:", data);
			}
		},
		error: function (xhr, status, error) {
			showNotification("error", "AJAX error: " + status + " - " + error);
			console.error("AJAX error:", status, error, xhr.responseText);
		}
	});
}

$(document).ready(function () {
	addDateFunctionality();
	loadAvailability();
	removeDateFunctionality();

});

// ========== ABOVE - DO NOT TOUCH OR REMOVE THESE FUNCTION ABOVE - Work with hangar.php (Feb-19-24) ===========

// ========== BELOW DO NOT TOUCH OR REMOVE THESE FUNCTION BELOW - Work with hangar.php (Feb-19-24) =============

$(document).ready(function () {
	// Dropzone.autoDiscover = false;

	$("#confpass").keydown(function (e) {
		if (e.which == 13) {
			$("#changePassBtn").trigger("click");
		}
	});

	$("body").addClass("body-bg");

	$.ajax({
		type: "POST",
		url: "checkAdmin.php",
		success: function (data) {
			$(".sidebar-list a[href='hangar.php'] li").addClass("active");

			$("#submitThoughts").click(function () {
				var email = $("#thoughtsEmail").val();
				var name = $("#thoughtsName").val();
				var msg = $("#thoughtsMessage").val();

				if (msg != "" && msg != null) {
					//submit & email us
					var that = this;
					$.ajax({
						type: "POST",
						url: "assets/php/submit_thoughts.php",
						data: { msg: msg, name: name, email: email },
						success: function (result) {
							if (result == "success") {
								$("#thoughtsMessage").val("");
								showNotification("success", "Thank you for your message.");
							} else {
								showNotification("error", result);
							}
						}
					});
				} else {
					showNotification("error", "Please fill in the text area with your message");
				}
			});

			// Function to save personal information
			function savePersonalInfo(data, modalId) {
				$.ajax({
					type: "POST",
					url: "update_personal_info.php",
					data: data,
					success: function (result) {
						if (result == "success") {
							showNotification("success", "Personal information updated successfully.");
							$(modalId).modal('hide'); // Hide the modal
							loadPersonalInfo(); // Reload the personal info
							// location.reload(); // Refresh the page
						} else {
							showNotification("error", "Failed to update personal information.");
						}
					},
					error: function () {
						showNotification("error", "An error occurred while updating personal information.");
					}
				});
			}

			// Function to load personal information
			function loadPersonalInfo() {
				$.ajax({
					type: "GET",
					url: "get_personal_info.php",
					dataType: "json",
					success: function (data) {
						if (data && !data.error) {
							// Populate the modal with user data
							$("#name").val(data.firstname);
							$("#name").val(data.lastname);
							$("#usernameInput").val(data.username);
							$("#user_nationality").val(data.user_nationality);
							$("#comandante").val(data.job_position);
							$("#nal_license").val(data.nal_license);
							$("#for_license").val(data.for_license);
							$("#email").val(data.email);
							$("#phone").val(data.phone);
							$("#phonetwo").val(data.phonetwo);

							// Update the display on the page
							$("#displayName").text(data.firstname + ' ' + data.lastname);
							$("#displayUsername").text(data.username);
							$("#nationality").text(data.user_nationality);
							$("#pos").text(data.job_position);
							$("#nalLic").text(data.nal_license);
							$("#forLic").text(data.for_license);
							$("#persEmail").text(data.email);
							$("#persPhone").text(data.phone);
							$("#persPhoneTwo").text(data.phonetwo);

						} else {
							showNotification("error", data.error || "Failed to load personal information.");
						}
					},
					error: function () {
						showNotification("error", "An error occurred while loading personal information.");
					}
				});
			}
			// Load personal information on page load
			loadPersonalInfo();

			// Save the information to the database
			$(".savePersonalInfo").click(function () {
				var firstname = $("#firstname").val();
				var lastname = $("#lastname").val();
				var username = $("#usernameInput").val();
				var user_nationality = $("#user_nationality").val();
				var comandante = $("#comandante").val();
				var nal_license = $("#nal_license").val();
				var for_license = $("#for_license").val();
				var email = $("#email").val();
				var phone = $("#phone").val();
				var phonetwo = $("#phonetwo").val();

				// Create a data object to hold all the values
				var data = {
					firstname: firstname,
					lastname: lastname,
					username: username,
					user_nationality: user_nationality,
					comandante: comandante,
					nal_license: nal_license,
					for_license: for_license,
					email: email,
					phone: phone,
					phonetwo: phonetwo
				};

				// Call the update query for database
				savePersonalInfo(data, "#personalInfoModal");
			});
		}, // SUCCESS FUNCTION FOR CHECKADMIN.PHP
		error: function (xhr, status, error) {
			console.error("checkAdmin.php AJAX error:", status, error, xhr.responseText);
		}
	});// AJAX CALL FOR CHECKADMIN.PHP
});

// ========== ABOVE - DO NOT TOUCH OR REMOVE THESE FUNCTION ABOVE - Work with hangar.php (Feb-19-24) ============

// ======= BELOW - Change or Edit Password - DO NOT TOUCH OR REMOVE THESE FUNCTION - hangar.php (Feb-20-24) ====

function changePass(oldPass, newPass, confPass) {
	var newP = $(newPass).val();
	var old = $(oldPass).val();
	var conf = $(confPass).val();
	if (old != "" || newP != "" || conf != "") {
		if (newP == conf) {
			if (newP.length >= 4) {
				var num = /^[0-9]+$/; // Only numbers
				// REMOVE DURING TESTING PROCESS 
				// var sym = /[!@#$%^&*()_+-=?<>{}~]/g;
				// var alph = /[A-Za-z]/g;
				// var num = /[0-9]/g;
				// if (sym.test(newP) && alph.test(newP) && num.test(newP)) {
				if (num.test(newP)) {
					// var pass = hex_sha512(newP);
					// var oldP = hex_sha512(old);
					$.ajax({
						type: "POST",
						// data: { pass: pass, old: oldP },
						data: { pass: newP, old: old }, //Send plane text to the server.
						url: "change_password.php",
						success: function (result) {
							if (result == "success") {
								showNotification("success", "You successfully changed your password.");
								$(".changepass").modal("toggle");
								$(oldPass).val("");
								$(newPass).val("");
								$(confPass).val("");
								$("body").animate({ scrollTop: 0 }, 900);
							} else if (result == "failed") {
								showNotification("error", "Changing your password was unsuccessful. Please try again later.");
								$(".changepass").modal("toggle");
								$(oldPass).val("");
								$(newPass).val("");
								$(confPass).val("");
								$("body").animate({ scrollTop: 0 }, 900);
							} else {
								$("#changePassError").text(result);
								$(oldPass).val("");
								$(newPass).val("");
								$(confPass).val("");
								$(oldPass).focus();
							}
						}
					})
				} else {
					$("#changePassError").text("Your new password does not contain the required characters.");
					$(newPass).val("");
					$(confPass).val("");
					$(newPass).focus();
				}
			} else {
				$("#changePassError").text("Your new password is too short.");
				$(newPass).val("");
				$(confPass).val("");
				$(newPass).focus();
			}
		} else {
			$("#changePassError").text("Your new passwords do not match");
			$(newPass).val("");
			$(confPass).val("");
			$(newPass).focus();
		}
	} else {
		$("#changePassError").text("Please fill in all fields");
	}
}

// Validity Date Saving Functionality
// $(document).ready(function () {
// 	// Initialize datepickers
// 	$('.validity-date').datepicker({
// 		dateFormat: 'yy-mm-dd'
// 	});

// 	// Save validity dates handler
// 	$('#saveValidityDates').off('click').on('click', function () {
// 		const updates = [];
// 		$('.validity-date').each(function () {
// 			updates.push({
// 				field: $(this).data('field'),
// 				value: $(this).val()
// 			});
// 		});

// 		$.ajax({
// 			url: 'update_validity.php',
// 			method: 'POST',
// 			data: { updates: updates },
// 			success: function (response) {
// 				const res = JSON.parse(response);
// 				if (res.success) {
// 					showNotification('success', 'Validity dates updated successfully');
// 					setTimeout(() => location.reload(), 1500);
// 				} else {
// 					showNotification('error', res.error || 'Error saving dates');
// 				}
// 			},
// 			error: function (xhr) {
// 				showNotification('error', 'AJAX Error: ' + xhr.statusText);
// 			}
// 		});
// 	});
// });

// // ======= NEW VALIDITY SAVED IN THE DATABASE =============================

// let newFieldCounter = 1;

// $('#addValidityRow').click(function () {
// 	const newRow = `
//     <tr class="new-validity">
//         <td>
//             <input type="text" 
//                    class="form-control validity-name" 
//                    placeholder="New Certification">
//         </td>
//         <td>
//             <div class="input-group" style="width: 220px;">
//                 <input type="text" 
//                        class="form-control datepicker validity-date" 
//                        placeholder="YYYY-MM-DD">
//                 <span class="input-group-btn" style="margin-left: 5px;">
//                     <button class="btn btn-primary save-validity" type="button">
//                         <i class="fa fa-save"></i>
//                     </button>
//                 </span>
//             </div>
//         </td>
//         <td class="text-muted status-cell">New</td>
//         <td>
//             <button class="btn btn-xs btn-danger remove-validity">
//                 <i class="fa fa-minus"></i>
//             </button>
//         </td>
//     </tr>`;

// 	$('#validityTable tbody').append(newRow);
// 	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
// });

// //  ============= DELETE VALIDITY FROM THE DATABASE =====================

// $('#validityTable').on('click', '.remove-validity', function () {
// 	const row = $(this).closest('tr');
// 	const fieldName = row.data('field');

// 	if (confirm('Are you sure you want to remove this certification?')) {
// 		$.ajax({
// 			url: 'delete_validity.php',
// 			method: 'POST',
// 			data: { field: fieldName },
// 			success: function (response) {
// 				const res = JSON.parse(response);
// 				if (res.success) {
// 					row.remove();
// 					showNotification('success', 'Certification removed');
// 				} else {
// 					showNotification('error', res.error);
// 				}
// 			}
// 		});
// 	}
// });

// =======================  Le Chat ADD DELETE VALIDITIES FROM TABLE =======================

document.addEventListener('DOMContentLoaded', function () {
	const validityTable = document.getElementById('validityTable');
	const addValidityRowButton = document.getElementById('addValidityRow');
	const validityTemplate = document.getElementById('validityTemplate');

	// Initialize datepicker
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});

	// Add new row
	addValidityRowButton.addEventListener('click', function () {
		const newRow = validityTemplate.cloneNode(true);
		newRow.style.display = '';
		newRow.querySelector('.validity-name').value = '';
		newRow.querySelector('.validity-date').value = '';
		validityTable.querySelector('tbody').appendChild(newRow);
	});

	// Save validity date
	validityTable.addEventListener('click', function (event) {
		if (event.target.classList.contains('save-validity')) {
			const row = event.target.closest('tr');
			const field = row.getAttribute('data-field');
			const dateValue = row.querySelector('.validity-date').value;

			console.log('Saving validity date for field:', field, 'with value:', dateValue);

			$.ajax({
				type: 'POST',
				url: 'update_validity.php',
				data: {
					field: field,
					value: dateValue
				},
				success: function (response) {
					console.log('Response:', response);
					if (response.success) {
						alert('Validity date saved successfully.');
						// Update the status cell
						const statusCell = row.querySelector('.status-cell');
						const statusClass = (dateValue && dateValue >= new Date().toISOString().split('T')[0]) ? 'text-success' : 'text-danger';
						const statusText = (dateValue && dateValue >= new Date().toISOString().split('T')[0]) ? 'Valid' : 'Expired';
						statusCell.className = statusClass;
						statusCell.textContent = statusText;
					} else {
						alert('Failed to save validity date: ' + response.error);
					}
				},
				error: function (xhr, status, error) {
					console.error('Error saving validity date:', status, error);
					console.error('Response:', xhr.responseText);
					alert('An error occurred while saving the validity date.');
				}
			});
		}
	});

	// Remove validity date
	validityTable.addEventListener('click', function (event) {
		if (event.target.classList.contains('remove-validity')) {
			const row = event.target.closest('tr');
			const field = row.getAttribute('data-field');

			$.ajax({
				type: 'POST',
				url: 'delete_validity.php',
				data: { field: field },
				success: function (response) {
					if (response.success) {
						alert('Validity date removed successfully.');
						// Remove the row from the table
						row.remove();
					} else {
						alert('Failed to remove validity date: ' + response.error);
					}
				},
				error: function (xhr, status, error) {
					console.error('Error removing validity date:', status, error);
					console.error('Response:', xhr.responseText);
					alert('An error occurred while removing the validity date.');
				}
			});
		}
	});
});



//===============================================================================================================


// ========== Clock - Time Zone PENDING =======================================

// var TZ = {
// 	"Afghanistan Standard Time": { "Display": "(UTC+04:30) Kabul", "Dlt": "", "Std": "Afghanistan Standard Time", "Bias": "-270", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Alaskan Standard Time": { "Display": "(UTC-09:00) Alaska", "Dlt": "", "Std": "Alaskan Standard Time", "Bias": "540", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Nov", "DltDate": "2:00:00 AM, 2nd Sun in Mar" }, "Arab Standard Time": { "Display": "(UTC+03:00) Kuwait, Riyadh", "Dlt": "", "Std": "Arab Standard Time", "Bias": "-180", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Arabian Standard Time": { "Display": "(UTC+04:00) Abu Dhabi, Muscat", "Dlt": "", "Std": "Arabian Standard Time", "Bias": "-240", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Arabic Standard Time": {
// 		"Display": "(UTC+03:00) Baghdad", "Dlt": "", "Std": "Arabic Standard Time", "Bias": "-180", "StdBias": "0",
// 		"DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established."
// 	}, "Argentina Standard Time": { "Display": "(UTC-03:00) Buenos Aires", "Dlt": "", "Std": "Argentina Standard Time", "Bias": "180", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Atlantic Standard Time": { "Display": "(UTC-04:00) Atlantic Time (Canada)", "Dlt": "", "Std": "Atlantic Standard Time", "Bias": "240", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Nov", "DltDate": "2:00:00 AM, 2nd Sun in Mar" }, "AUS Central Standard Time": { "Display": "(UTC+09:30) Darwin", "Dlt": "", "Std": "AUS Central Standard Time", "Bias": "-570", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "AUS Eastern Standard Time": { "Display": "(UTC+10:00) Canberra, Melbourne, Sydney", "Dlt": "", "Std": "AUS Eastern Standard Time", "Bias": "-600", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, 1st Sun in Apr", "DltDate": "2:00:00 AM, 1st Sun in Oct" },
// 	"Azerbaijan Standard Time": { "Display": "(UTC+04:00) Baku", "Dlt": "", "Std": "Azerbaijan Standard Time", "Bias": "-240", "StdBias": "0", "DltBias": "-60", "StdDate": "5:00:00 AM, last Sun in Oct", "DltDate": "4:00:00 AM, last Sun in Mar" }, "Azores Standard Time": { "Display": "(UTC-01:00) Azores", "Dlt": "", "Std": "Azores Standard Time", "Bias": "60", "StdBias": "0", "DltBias": "-60", "StdDate": "1:00:00 AM, last Sun in Oct", "DltDate": "12:00:00 AM, last Sun in Mar" }, "Bahia Standard Time": { "Display": "(UTC-03:00) Salvador", "Dlt": "", "Std": "Bahia Standard Time", "Bias": "180", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Bangladesh Standard Time": { "Display": "(UTC+06:00) Dhaka", "Dlt": "", "Std": "Bangladesh Standard Time", "Bias": "-360", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Belarus Standard Time": {
// 		"Display": "(UTC+03:00) Minsk", "Dlt": "", "Std": "Belarus Standard Time", "Bias": "-180", "StdBias": "0", "DltBias": "-60",
// 		"StdDate": "0 - No date established.", "DltDate": "0 - No date established."
// 	}, "Canada Central Standard Time": { "Display": "(UTC-06:00) Saskatchewan", "Dlt": "", "Std": "Canada Central Standard Time", "Bias": "360", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Cape Verde Standard Time": { "Display": "(UTC-01:00) Cape Verde Is.", "Dlt": "", "Std": "Cape Verde Standard Time", "Bias": "60", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Caucasus Standard Time": { "Display": "(UTC+04:00) Yerevan", "Dlt": "", "Std": "Caucasus Standard Time", "Bias": "-240", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Cen. Australia Standard Time": { "Display": "(UTC+09:30) Adelaide", "Dlt": "", "Std": "Cen. Australia Standard Time", "Bias": "-570", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, 1st Sun in Apr", "DltDate": "2:00:00 AM, 1st Sun in Oct" }, "Central America Standard Time": {
// 		"Display": "(UTC-06:00) Central America",
// 		"Dlt": "", "Std": "Central America Standard Time", "Bias": "360", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established."
// 	}, "Central Asia Standard Time": { "Display": "(UTC+06:00) Astana", "Dlt": "", "Std": "Central Asia Standard Time", "Bias": "-360", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Central Brazilian Standard Time": { "Display": "(UTC-04:00) Cuiaba", "Dlt": "", "Std": "Central Brazilian Standard Time", "Bias": "240", "StdBias": "0", "DltBias": "-60", "StdDate": "11:59:59 PM, 3rd Sat in Feb", "DltDate": "11:59:59 PM, 3rd Sat in Oct" }, "Central Europe Standard Time": { "Display": "(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague", "Dlt": "", "Std": "Central Europe Standard Time", "Bias": "-60", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, last Sun in Mar" }, "Central European Standard Time": {
// 		"Display": "(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb", "Dlt": "",
// 		"Std": "Central European Standard Time", "Bias": "-60", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, last Sun in Mar"
// 	}, "Central Pacific Standard Time": { "Display": "(UTC+11:00) Solomon Is., New Caledonia", "Dlt": "", "Std": "Central Pacific Standard Time", "Bias": "-660", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Central Standard Time": { "Display": "(UTC-06:00) Central Time (US & Canada)", "Dlt": "", "Std": "Central Standard Time", "Bias": "360", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Nov", "DltDate": "2:00:00 AM, 2nd Sun in Mar" }, "Central Standard Time (Mexico)": { "Display": "(UTC-06:00) Guadalajara, Mexico City, Monterrey", "Dlt": "", "Std": "Central Standard Time (Mexico)", "Bias": "360", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, 1st Sun in Apr" }, "China Standard Time": {
// 		"Display": "(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
// 		"Dlt": "", "Std": "China Standard Time", "Bias": "-480", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established."
// 	}, "Dateline Standard Time": { "Display": "(UTC-12:00) International Date Line West", "Dlt": "", "Std": "Dateline Standard Time", "Bias": "720", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "E. Africa Standard Time": { "Display": "(UTC+03:00) Nairobi", "Dlt": "", "Std": "E. Africa Standard Time", "Bias": "-180", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "E. Australia Standard Time": { "Display": "(UTC+10:00) Brisbane", "Dlt": "", "Std": "E. Australia Standard Time", "Bias": "-600", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "E. Europe Standard Time": {
// 		"Display": "(UTC+02:00) E. Europe", "Dlt": "", "Std": "E. Europe Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60",
// 		"StdDate": "3:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, last Sun in Mar"
// 	}, "E. South America Standard Time": { "Display": "(UTC-03:00) Brasilia", "Dlt": "", "Std": "E. South America Standard Time", "Bias": "180", "StdBias": "0", "DltBias": "-60", "StdDate": "11:59:59 PM, 3rd Sat in Feb", "DltDate": "11:59:59 PM, 3rd Sat in Oct" }, "Eastern Standard Time": { "Display": "(UTC-05:00) Eastern Time (US & Canada)", "Dlt": "", "Std": "Eastern Standard Time", "Bias": "300", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Nov", "DltDate": "2:00:00 AM, 2nd Sun in Mar" }, "Egypt Standard Time": { "Display": "(UTC+02:00) Cairo", "Dlt": "", "Std": "Egypt Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "11:59:59 PM, last Thu in Sep", "DltDate": "11:59:59 PM, 3rd Thu in May" }, "Ekaterinburg Standard Time": {
// 		"Display": "(UTC+05:00) Ekaterinburg (RTZ 4)", "Dlt": "", "Std": "Russia TZ 4 Standard Time", "Bias": "-300", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct",
// 		"DltDate": "12:00:00 AM, 1st Wed in Jan"
// 	}, "Fiji Standard Time": { "Display": "(UTC+12:00) Fiji", "Dlt": "", "Std": "Fiji Standard Time", "Bias": "-720", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 3rd Sun in Jan", "DltDate": "2:00:00 AM, 4th Sun in Oct" }, "FLE Standard Time": { "Display": "(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius", "Dlt": "", "Std": "FLE Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "4:00:00 AM, last Sun in Oct", "DltDate": "3:00:00 AM, last Sun in Mar" }, "Georgian Standard Time": { "Display": "(UTC+04:00) Tbilisi", "Dlt": "", "Std": "Georgian Standard Time", "Bias": "-240", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "GMT Standard Time": { "Display": "(UTC) Dublin, Edinburgh, Lisbon, London", "Dlt": "", "Std": "GMT Standard Time", "Bias": "0", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "1:00:00 AM, last Sun in Mar" },
// 	"Greenland Standard Time": { "Display": "(UTC-03:00) Greenland", "Dlt": "", "Std": "Greenland Standard Time", "Bias": "180", "StdBias": "0", "DltBias": "-60", "StdDate": "11:00:00 PM, last Sat in Oct", "DltDate": "10:00:00 PM, last Sat in Mar" }, "Greenwich Standard Time": { "Display": "(UTC) Monrovia, Reykjavik", "Dlt": "", "Std": "Greenwich Standard Time", "Bias": "0", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "GTB Standard Time": { "Display": "(UTC+02:00) Athens, Bucharest", "Dlt": "", "Std": "GTB Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "4:00:00 AM, last Sun in Oct", "DltDate": "3:00:00 AM, last Sun in Mar" }, "Hawaiian Standard Time": { "Display": "(UTC-10:00) Hawaii", "Dlt": "", "Std": "Hawaiian Standard Time", "Bias": "600", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "India Standard Time": {
// 		"Display": "(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi", "Dlt": "",
// 		"Std": "India Standard Time", "Bias": "-330", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established."
// 	}, "Iran Standard Time": { "Display": "(UTC+03:30) Tehran", "Dlt": "", "Std": "Iran Standard Time", "Bias": "-210", "StdBias": "0", "DltBias": "-60", "StdDate": "11:59:59 PM, 3rd Mon in Sep", "DltDate": "11:59:59 PM, 3rd Sat in Mar" }, "Israel Standard Time": { "Display": "(UTC+02:00) Jerusalem", "Dlt": "", "Std": "Jerusalem Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, last Fri in Mar" }, "Jordan Standard Time": { "Display": "(UTC+02:00) Amman", "Dlt": "", "Std": "Jordan Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "1:00:00 AM, last Fri in Oct", "DltDate": "11:59:59 PM, last Thu in Mar" }, "Kaliningrad Standard Time": {
// 		"Display": "(UTC+02:00) Kaliningrad (RTZ 1)", "Dlt": "", "Std": "Russia TZ 1 Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct",
// 		"DltDate": "12:00:00 AM, 1st Wed in Jan"
// 	}, "Kamchatka Standard Time": { "Display": "(UTC+12:00) Petropavlovsk-Kamchatsky - Old", "Dlt": "", "Std": "Kamchatka Standard Time", "Bias": "-720", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, last Sun in Mar" }, "Korea Standard Time": { "Display": "(UTC+09:00) Seoul", "Dlt": "", "Std": "Korea Standard Time", "Bias": "-540", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Libya Standard Time": { "Display": "(UTC+02:00) Tripoli", "Dlt": "", "Std": "Libya Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Line Islands Standard Time": { "Display": "(UTC+14:00) Kiritimati Island", "Dlt": "", "Std": "Line Islands Standard Time", "Bias": "-840", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Magadan Standard Time": {
// 		"Display": "(UTC+10:00) Magadan", "Dlt": "",
// 		"Std": "Magadan Standard Time", "Bias": "-600", "StdBias": "0", "DltBias": "-120", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "12:00:00 AM, 1st Wed in Jan"
// 	}, "Mauritius Standard Time": { "Display": "(UTC+04:00) Port Louis", "Dlt": "", "Std": "Mauritius Standard Time", "Bias": "-240", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Mid-Atlantic Standard Time": { "Display": "(UTC-02:00) Mid-Atlantic - Old", "Dlt": "", "Std": "Mid-Atlantic Standard Time", "Bias": "120", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Sep", "DltDate": "2:00:00 AM, last Sun in Mar" }, "Middle East Standard Time": { "Display": "(UTC+02:00) Beirut", "Dlt": "", "Std": "Middle East Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "11:59:59 PM, last Sat in Oct", "DltDate": "11:59:59 PM, last Sat in Mar" }, "Montevideo Standard Time": {
// 		"Display": "(UTC-03:00) Montevideo", "Dlt": "", "Std": "Montevideo Standard Time", "Bias": "180", "StdBias": "0", "DltBias": "-60",
// 		"StdDate": "2:00:00 AM, 2nd Sun in Mar", "DltDate": "2:00:00 AM, 1st Sun in Oct"
// 	}, "Morocco Standard Time": { "Display": "(UTC) Casablanca", "Dlt": "", "Std": "Morocco Standard Time", "Bias": "0", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, last Sun in Mar" }, "Mountain Standard Time": { "Display": "(UTC-07:00) Mountain Time (US & Canada)", "Dlt": "", "Std": "Mountain Standard Time", "Bias": "420", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Nov", "DltDate": "2:00:00 AM, 2nd Sun in Mar" }, "Mountain Standard Time (Mexico)": { "Display": "(UTC-07:00) Chihuahua, La Paz, Mazatlan", "Dlt": "", "Std": "Mountain Standard Time (Mexico)", "Bias": "420", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, 1st Sun in Apr" }, "Myanmar Standard Time": { "Display": "(UTC+06:30) Yangon (Rangoon)", "Dlt": "", "Std": "Myanmar Standard Time", "Bias": "-390", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." },
// 	"N. Central Asia Standard Time": { "Display": "(UTC+06:00) Novosibirsk (RTZ 5)", "Dlt": "", "Std": "Russia TZ 5 Standard Time", "Bias": "-360", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "12:00:00 AM, 1st Wed in Jan" }, "Namibia Standard Time": { "Display": "(UTC+01:00) Windhoek", "Dlt": "", "Std": "Namibia Standard Time", "Bias": "-60", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Apr", "DltDate": "2:00:00 AM, 1st Sun in Sep" }, "Nepal Standard Time": { "Display": "(UTC+05:45) Kathmandu", "Dlt": "", "Std": "Nepal Standard Time", "Bias": "-345", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "New Zealand Standard Time": { "Display": "(UTC+12:00) Auckland, Wellington", "Dlt": "", "Std": "New Zealand Standard Time", "Bias": "-720", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, 1st Sun in Apr", "DltDate": "2:00:00 AM, last Sun in Sep" }, "Newfoundland Standard Time": {
// 		"Display": "(UTC-03:30) Newfoundland", "Dlt": "",
// 		"Std": "Newfoundland Standard Time", "Bias": "210", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Nov", "DltDate": "2:00:00 AM, 2nd Sun in Mar"
// 	}, "North Asia East Standard Time": { "Display": "(UTC+08:00) Irkutsk (RTZ 7)", "Dlt": "", "Std": "Russia TZ 7 Standard Time", "Bias": "-480", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "12:00:00 AM, 1st Wed in Jan" }, "North Asia Standard Time": { "Display": "(UTC+07:00) Krasnoyarsk (RTZ 6)", "Dlt": "", "Std": "Russia TZ 6 Standard Time", "Bias": "-420", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "12:00:00 AM, 1st Wed in Jan" }, "Pacific SA Standard Time": { "Display": "(UTC-04:00) Santiago", "Dlt": "", "Std": "Pacific SA Standard Time", "Bias": "240", "StdBias": "0", "DltBias": "-60", "StdDate": "11:59:59 PM, last Sat in Apr", "DltDate": "11:59:59 PM, 1st Sat in Sep" }, "Pacific Standard Time": {
// 		"Display": "(UTC-08:00) Pacific Time (US & Canada)", "Dlt": "", "Std": "Pacific Standard Time", "Bias": "480",
// 		"StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Nov", "DltDate": "2:00:00 AM, 2nd Sun in Mar"
// 	}, "Pacific Standard Time (Mexico)": { "Display": "(UTC-08:00) Baja California", "Dlt": "", "Std": "Pacific Standard Time (Mexico)", "Bias": "480", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, 1st Sun in Apr" }, "Pakistan Standard Time": { "Display": "(UTC+05:00) Islamabad, Karachi", "Dlt": "", "Std": "Pakistan Standard Time", "Bias": "-300", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Paraguay Standard Time": { "Display": "(UTC-04:00) Asuncion", "Dlt": "", "Std": "Paraguay Standard Time", "Bias": "240", "StdBias": "0", "DltBias": "-60", "StdDate": "11:59:59 PM, 4th Sat in Mar", "DltDate": "11:59:59 PM, 1st Sat in Oct" }, "Romance Standard Time": {
// 		"Display": "(UTC+01:00) Brussels, Copenhagen, Madrid, Paris", "Dlt": "", "Std": "Romance Standard Time", "Bias": "-60", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, last Sun in Oct",
// 		"DltDate": "2:00:00 AM, last Sun in Mar"
// 	}, "Russia Time Zone 10": { "Display": "(UTC+11:00) Chokurdakh (RTZ 10)", "Dlt": "", "Std": "Russia TZ 10 Standard Time", "Bias": "-660", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Russia Time Zone 11": { "Display": "(UTC+12:00) Anadyr, Petropavlovsk-Kamchatsky (RTZ 11)", "Dlt": "", "Std": "Russia TZ 11 Standard Time", "Bias": "-720", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Russia Time Zone 3": { "Display": "(UTC+04:00) Izhevsk, Samara (RTZ 3)", "Dlt": "", "Std": "Russia TZ 3 Standard Time", "Bias": "-240", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Russian Standard Time": { "Display": "(UTC+03:00) Moscow, St. Petersburg, Volgograd (RTZ 2)", "Dlt": "", "Std": "Russia TZ 2 Standard Time", "Bias": "-180", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "12:00:00 AM, 1st Wed in Jan" },
// 	"SA Eastern Standard Time": { "Display": "(UTC-03:00) Cayenne, Fortaleza", "Dlt": "", "Std": "SA Eastern Standard Time", "Bias": "180", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "SA Pacific Standard Time": { "Display": "(UTC-05:00) Bogota, Lima, Quito, Rio Branco", "Dlt": "", "Std": "SA Pacific Standard Time", "Bias": "300", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "SA Western Standard Time": { "Display": "(UTC-04:00) Georgetown, La Paz, Manaus, San Juan", "Dlt": "", "Std": "SA Western Standard Time", "Bias": "240", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Samoa Standard Time": { "Display": "(UTC+13:00) Samoa", "Dlt": "", "Std": "Samoa Standard Time", "Bias": "-780", "StdBias": "0", "DltBias": "-60", "StdDate": "1:00:00 AM, 1st Sun in Apr", "DltDate": "12:00:00 AM, last Sun in Sep" }, "SE Asia Standard Time": {
// 		"Display": "(UTC+07:00) Bangkok, Hanoi, Jakarta", "Dlt": "",
// 		"Std": "SE Asia Standard Time", "Bias": "-420", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established."
// 	}, "Singapore Standard Time": { "Display": "(UTC+08:00) Kuala Lumpur, Singapore", "Dlt": "", "Std": "Malay Peninsula Standard Time", "Bias": "-480", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "South Africa Standard Time": { "Display": "(UTC+02:00) Harare, Pretoria", "Dlt": "", "Std": "South Africa Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Sri Lanka Standard Time": { "Display": "(UTC+05:30) Sri Jayawardenepura", "Dlt": "", "Std": "Sri Lanka Standard Time", "Bias": "-330", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Syria Standard Time": {
// 		"Display": "(UTC+02:00) Damascus", "Dlt": "", "Std": "Syria Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60",
// 		"StdDate": "11:59:59 PM, last Thu in Oct", "DltDate": "11:59:59 PM, 1st Thu in Apr"
// 	}, "Taipei Standard Time": { "Display": "(UTC+08:00) Taipei", "Dlt": "", "Std": "Taipei Standard Time", "Bias": "-480", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Tasmania Standard Time": { "Display": "(UTC+10:00) Hobart", "Dlt": "", "Std": "Tasmania Standard Time", "Bias": "-600", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, 1st Sun in Apr", "DltDate": "2:00:00 AM, 1st Sun in Oct" }, "Tokyo Standard Time": { "Display": "(UTC+09:00) Osaka, Sapporo, Tokyo", "Dlt": "", "Std": "Tokyo Standard Time", "Bias": "-540", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Tonga Standard Time": { "Display": "(UTC+13:00) Nuku'alofa", "Dlt": "", "Std": "Tonga Standard Time", "Bias": "-780", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Turkey Standard Time": {
// 		"Display": "(UTC+02:00) Istanbul", "Dlt": "",
// 		"Std": "Turkey Standard Time", "Bias": "-120", "StdBias": "0", "DltBias": "-60", "StdDate": "4:00:00 AM, last Sun in Oct", "DltDate": "3:00:00 AM, last Mon in Mar"
// 	}, "Ulaanbaatar Standard Time": { "Display": "(UTC+08:00) Ulaanbaatar", "Dlt": "", "Std": "Ulaanbaatar Standard Time", "Bias": "-480", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "US Eastern Standard Time": { "Display": "(UTC-05:00) Indiana (East)", "Dlt": "", "Std": "US Eastern Standard Time", "Bias": "300", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, 1st Sun in Nov", "DltDate": "2:00:00 AM, 2nd Sun in Mar" }, "US Mountain Standard Time": { "Display": "(UTC-07:00) Arizona", "Dlt": "", "Std": "US Mountain Standard Time", "Bias": "420", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "UTC": { "Display": "(UTC) Coordinated Universal Time", "Dlt": "", "Std": "Coordinated Universal Time", "Bias": "0", "StdBias": "0", "DltBias": "0", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." },
// 	"UTC+12": { "Display": "(UTC+12:00) Coordinated Universal Time+12", "Dlt": "", "Std": "UTC+12", "Bias": "-720", "StdBias": "0", "DltBias": "0", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "UTC-02": { "Display": "(UTC-02:00) Coordinated Universal Time-02", "Dlt": "", "Std": "UTC-02", "Bias": "120", "StdBias": "0", "DltBias": "0", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "UTC-11": { "Display": "(UTC-11:00) Coordinated Universal Time-11", "Dlt": "", "Std": "UTC-11", "Bias": "660", "StdBias": "0", "DltBias": "0", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Venezuela Standard Time": { "Display": "(UTC-04:30) Caracas", "Dlt": "", "Std": "Venezuela Standard Time", "Bias": "270", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Vladivostok Standard Time": {
// 		"Display": "(UTC+10:00) Vladivostok, Magadan (RTZ 9)", "Dlt": "", "Std": "Russia TZ 9 Standard Time", "Bias": "-600", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct",
// 		"DltDate": "12:00:00 AM, 1st Wed in Jan"
// 	}, "W. Australia Standard Time": { "Display": "(UTC+08:00) Perth", "Dlt": "", "Std": "W. Australia Standard Time", "Bias": "-480", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "W. Central Africa Standard Time": { "Display": "(UTC+01:00) West Central Africa", "Dlt": "", "Std": "W. Central Africa Standard Time", "Bias": "-60", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "W. Europe Standard Time": { "Display": "(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna", "Dlt": "", "Std": "W. Europe Standard Time", "Bias": "-60", "StdBias": "0", "DltBias": "-60", "StdDate": "3:00:00 AM, last Sun in Oct", "DltDate": "2:00:00 AM, last Sun in Mar" }, "West Asia Standard Time": { "Display": "(UTC+05:00) Ashgabat, Tashkent", "Dlt": "", "Std": "West Asia Standard Time", "Bias": "-300", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." },
// 	"West Pacific Standard Time": { "Display": "(UTC+10:00) Guam, Port Moresby", "Dlt": "", "Std": "West Pacific Standard Time", "Bias": "-600", "StdBias": "0", "DltBias": "-60", "StdDate": "0 - No date established.", "DltDate": "0 - No date established." }, "Yakutsk Standard Time": { "Display": "(UTC+09:00) Yakutsk (RTZ 8)", "Dlt": "", "Std": "Russia TZ 8 Standard Time", "Bias": "-540", "StdBias": "0", "DltBias": "-60", "StdDate": "2:00:00 AM, last Sun in Oct", "DltDate": "12:00:00 AM, 1st Wed in Jan" }
// };

// // Time Zone functions ========================

// timezoneOptions = "";
// $.each(TZ, function (i, val) {
// 	timezoneOptions += "<option value='" + val.Bias + "'>" + val.Display + "</option>";
// });
// $("#clock-timezone").html(timezoneOptions);

// var c = $("#clock-timezone option");
// c.sort(function (a, b) {
// 	if (parseInt($(a).attr("value")) > parseInt($(b).attr("value"))) {
// 		return 1;
// 	} else if (parseInt($(a).attr("value")) < parseInt($(b).attr("value"))) {
// 		return -1;
// 	}
// 	return 0;
// });
// $("#clock-timezone").empty();
// $("#clock-timezone").html(c);

// $("#saveClockSettings").click(function () {
// 	var that = this;
// 	if ($("#clock-name").val() != "") {
// 		$.ajax({
// 			url: "assets/php/save_clock.php",
// 			type: "POST",
// 			data: { timezone: $("#clock-timezone").val(), name: $("#clock-name").val() },
// 			success: function (result) {
// 				console.log(result);
// 				if (result == "success") {
// 					showNotification("success", "You successfully changed your clock.");
// 					resetUserClock();
// 				} else {
// 					showNotification("error", "Changing your clock failed.");
// 				}
// 			}
// 		})
// 	}
// });

// $.ajax({
// 	type: "GET",
// 	url: "assets/php/get_clock_settings.php",
// 	success: function (result) {
// 		console.log(result);
// 		if (result.charAt(0) == "{" || result.charAt(0) == "[") {
// 			res = JSON.parse(result);
// 			$("#clock-timezone").val(res.tz);
// 			$("#clock-name").val(res.name);
// 		}
// 	}
// });

// $("#change-profile-picture").on("hidden.bs.modal", function () {
// 	$(".dz-preview").remove();
// 	$(".dropzone").removeClass("dz-started");
// })

// $("#uploadDocuments").dropzone({
// 	url: "assets/php/change-profile-picture.php",
// 	maxFilesize: 1.5,
// 	clickable: true,
// 	acceptedFiles: ".jpg,.jpeg,.png,.gif,.bmp,.JPG,.JPEG,.PNG,.GIF,.BMP",
// 	previewTemplate: '<div class="dz-preview dz-file-preview"><div class="dz-details"><div class="dz-filename"><span data-dz-name></span></div><div class="dz-size" data-dz-size></div><img data-dz-thumbnail /></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-success-mark"><span class="fa fa-check-circle-o fa-2x"></span></div><div class="dz-error-mark"><span class="fa fa-times-circle-o fa-2x"></span></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div>',
// 	init: function () {
// 		this.on("success", function (file, response) {
// 			console.log(file, response);
// 			if (response.substring(0, 7) == "success") {
// 				$("#change-profile-picture").modal("hide");
// 				filename = response.substring(8);
// 				$("#profile-picture").attr("src", "uploads/pictures/" + filename);
// 			}
// 		});
// 	}
// });